<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotifiableUser;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // For generating UUIDs

class UsersController extends Controller
{
    //
    public function viewallusers()
    {
        if (!auth()->user()->haspermission('canviewallusers')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to View Users!");
        }
        $allusers = User::all();
        return view('pages.users.index', compact('allusers'));
    }

    public function updateuserpermissions(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Change User Role or Right!");
        }
        // Find the user by ID or fail with a 404 error
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,pid'
        ]);
        $newPermissions = $request->input('permissions');
        $user = User::findOrFail($id);
        if (!$user->issuperadmin()) {
            DB::transaction(function () use ($user, $id, $newPermissions) {
                $user->permissions()->detach();
                DB::table('userpermissions')->where('useridfk', $id)->delete();
                // Add new permissions for the user
                if ($user->role != 2) {
                    foreach ($newPermissions as $permissioncode) {
                        $permission = Permission::where('pid', $permissioncode)->firstOrFail();
                        if ($permission->targetrole != 2) {
                            $user->permissions()->attach($permission->pid, ['id' => (string) Str::uuid()]);
                        }
                    }
                }
                else {
                    $applicantpermissions = Permission::where('targetrole', $user->role)->get();
                    foreach ($applicantpermissions as $permission) {
                        $user->permissions()->attach($permission->pid, ['id' => (string) Str::uuid()]);
                    }
                }
            });
            return response()->json(['message' => 'Permissions updated successfully.', 'type' => "success"]);
        }
        else {
            return response()->json(['message' => 'Administrator has exclusively all rights!', 'type' => 'warning']);
        }

    }
    public function updaterole(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to change user role or rights!");
        }

        // Find the user by ID or fail with a 404 error
        $user = User::findOrFail($id);

        if ($user->issuperadmin()) {
            return response()->json(['message' => 'Super Administrator has exclusively all rights!', 'type' => 'warning']);
        }

        DB::transaction(function () use ($user, $request) {
            // Detach all permissions
            $user->permissions()->detach();
            DB::table('userpermissions')->where('useridfk', $user->userid)->delete();

            // Update role and admin status
            if ($request->has('isadmin') && $request->input('isadmin') == 'on') {
                $user->isadmin = true;
                $user->role = 1;
            }
            elseif ($request->has('userrole')) {
                $user->role = (int) $request->input('userrole');
                $user->isadmin = false;
            }
            else {
                $user->isadmin = false;
            }

            // Update active status
            $user->isactive = $request->has('userisactive') && $request->input('userisactive') == 'on';

             

            $user->saveOrFail();
        });

        return response()->json(['message' => 'Role updated successfully!', 'type' => 'success']);
    }

    public function getnonapplicantdefaultrights()
    {
        $names = ['canviewallapplications', 'canviewreports', 'canviewadmindashboard', 'canreadproposaldetails', 'canviewofficeuse', 'canproposechanges'];
        $permissions = [];
        $permissions = Permission::whereIn('shortname', $names)->get();
        return $permissions;
    }
    public function updatebasicdetails(Request $request, $id)
    {

        if (Auth::user()->userid != $id && !Auth::user()->haspermission('canedituserprofile')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Edit this User!");
        }
        // Define validation rules
        $rules = [
            'fullname' => 'required|string',
            'email' => 'required|string',
            'phonenumber' => 'required|string',
            'pfno' => 'required|string',
        ];
        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        // Find the user by ID or fail with a 404 error
        $user = User::findOrFail($id);
        $user->name = $request->input('fullname');
        $user->email = $request->input('email');
        $user->pfno = $request->input('pfno');
        $user->phonenumber = $request->input('phonenumber');
        $user->save();
        return response()->json(['message' => 'User Updated Successfully!', 'type' => 'success']);



    }

    public function viewsingleuser($id)
    {
        if (!auth()->user()->haspermission('canedituserprofile')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Edit this User!");
        }
        // Find the user by ID or fail with a 404 error
        $user = User::findOrFail($id);
        $userStats = [
            'proposals' => 0,
            'approved' => 0, 
            'pending' => 0,
            'projects' => 0
        ];
        $departments = [];
        // Return the view with the proposal data
        return view('pages.users.show', compact('user', 'userStats', 'departments'));
    }

    public function geteditsingleuserpage($id)
    {
        if (!auth()->user()->haspermission('canedituserprofile')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Edit this User!");
        }
        // Find the proposal by ID or fail with a 404 error
        $prop = User::findOrFail($id);
        $isreadonlypage = false;
        $isadminmode = true;
        $grants = [];
        $departments = [];
        $themes = [];
        // Return the view with the proposal data
        return view('pages.users.proposalform', compact('prop', 'isreadonlypage', 'isadminmode', 'departments', 'grants', 'themes'));
    }

    public function fetchallusers()
    {
        if (!auth()->user()->haspermission('canviewallusers')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }
        
        try {
            $data = User::all()->map(function ($user) {
                return [
                    'userid' => $user->userid,
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'pfno' => $user->pfno ?? '',
                    'phonenumber' => $user->phonenumber ?? '',
                    'role' => $user->role ?? 0,
                    'isadmin' => $user->isadmin ?? false,
                    'isactive' => $user->isactive ?? true,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            });
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    public function fetchsearchusers(Request $request)
    {
        if (!auth()->user()->haspermission('canviewallusers')) {
            return response()->json([]);
        }
        else {
            $searchTerm = $request->input('search');
            $data = User::where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('pfno', 'like', '%' . $searchTerm . '%')
                ->orWhere('isactive', 'like', '%' . $searchTerm . '%')
                ->get();
            return response()->json($data); // Return filtered data as JSON
        }

    }


    ////////
    //Notifications functions
    ////////
    public function managenotificationtype($id)
    {
        if (!auth()->user()->haspermission('canviewnotificationtypestab')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Edit this User!");
        }
        // Find the user by ID or fail with a 404 error
        $notificationtype = NotificationType::findOrFail($id);
        $currentnotifiableusers = NotifiableUser::where('notificationfk', $id)->get();
        $allusers = User::all();
        // Get the IDs of all currently notifiable users
        $currentNotifiableUserIds = $currentnotifiableusers->pluck('useridfk')->toArray();

        // Filter out the currently notifiable users from all users
        $nonNotifiableUsers = $allusers->whereNotIn('userid', $currentNotifiableUserIds);
        // Return the view with the proposal data
        return view('pages.users.usernotifications', compact('notificationtype', 'nonNotifiableUsers'));
    }
    public function addnotifiableusers(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canaddorremovenotifiableuser')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Edit a Notifiable User!");
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'users' => 'required|array',
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);

        }
        //user submitted ids
        $users = $request->input('users');
        // Fetch existing user IDs for the given notification
        $existingUsers = NotifiableUser::where('notificationfk', $id)
            ->whereIn('useridfk', $users)
            ->pluck('useridfk')
            ->toArray();

        // Filter out existing user IDs
        $newUsers = array_diff($users, $existingUsers);

        // Prepare data for bulk insert
        $notifiableUsers = [];
        foreach ($newUsers as $userid) {
            $notifiableUsers[] = [
                'useridfk' => $userid,
                'notificationfk' => $id,
            ];
        }

        // Bulk insert only new records
        if (!empty($notifiableUsers)) {
            NotifiableUser::insert($notifiableUsers);
        }
        // Optionally, return a response or redirect 
        return response(['message' => 'Notifiable Users Added Successfully!!', 'type' => 'success']);


    }

    public function removenotifiableuser(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canaddorremovenotifiableuser')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Remove a Notifiable User!");
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'users' => 'required|array',
            'users.*' => 'exists:notifiableusers,useridfk' // Ensure each user ID exists in the notifiableusers table
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);

        }
        //user submitted ids
        $userIds = $request->input('users');
        NotifiableUser::where('notificationfk', $id)->whereIn('useridfk', $userIds)->delete();
        // Optionally, return a response or redirect 
        return response(['message' => 'Notifiable Users Removed Successfully!!', 'type' => 'success']);


    }
    public function fetchallnotificationtypes()
    {
        if (!auth()->user()->haspermission('canviewnotificationtypestab')) {
            return response()->json([]);
        }
        else {
            $data = NotificationType::all();
            return response()->json($data); // Return  data as JSON
        }
    }

    public function fetchtypewiseusers($id)
    {
        if (!auth()->user()->haspermission('canviewnotificationtypestab')) {
            return response()->json([]);
        }
        $data = NotifiableUser::with('applicant')->where('notificationfk', $id)->get();
        return response()->json($data); // Return  data as JSON
    }

    // Public API methods (no authentication required)
    public function apiGetAllUsers()
    {
        $users = User::select('userid', 'name', 'email', 'pfno', 'phonenumber', 'role', 'isadmin', 'isactive', 'created_at')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $users,
            'count' => $users->count()
        ]);
    }

    public function apiGetUser($id)
    {
        $user = User::select('userid', 'name', 'email', 'pfno', 'phonenumber', 'role', 'isadmin', 'isactive', 'created_at')
            ->find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function createUser(Request $request)
    {
        if (!auth()->user()->haspermission('canaddnewuser')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'pfno' => 'required|string|unique:users,pfno',
            'phonenumber' => 'required|string|unique:users,phonenumber',
            'role' => 'required|integer',
            'password' => 'required|string|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->pfno = $request->input('pfno');
        $user->phonenumber = $request->input('phonenumber');
        $user->role = $request->input('role');
        $user->password = bcrypt($request->input('password'));
        $user->isactive = true;
        $user->save();

        return response()->json(['message' => 'User created successfully!', 'type' => 'success', 'user' => $user]);
    }

    public function disableUser($id)
    {
        if (!auth()->user()->haspermission('canresetuserpasswordordisablelogin')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $user = User::findOrFail($id);
        if ($user->issuperadmin()) {
            return response()->json(['message' => 'Cannot disable super administrator!', 'type' => 'warning']);
        }

        $user->isactive = false;
        $user->save();

        return response()->json(['message' => 'User disabled successfully!', 'type' => 'success']);
    }

    public function enableUser($id)
    {
        if (!auth()->user()->haspermission('canresetuserpasswordordisablelogin')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $user = User::findOrFail($id);
        $user->isactive = true;
        $user->save();

        return response()->json(['message' => 'User enabled successfully!', 'type' => 'success']);
    }

    public function showPermissions($id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return redirect()->route('pages.unauthorized');
        }

        $user = User::findOrFail($id);
        $roles = [1 => 'Admin', 2 => 'Researcher', 3 => 'Guest'];
        $roleNames = $roles;
        
        // Get role-based permissions
        $rolePermissions = Permission::where('targetrole', $user->role)->get();
        
        // Get available permissions for additional assignment
        $availablePermissions = Permission::where('targetrole', '!=', $user->role)
            ->orWhereNull('targetrole')
            ->get();
        
        // Get user's current additional permissions
        $userPermissions = $user->permissions;
        
        return view('pages.users.permissions', compact(
            'user', 'roles', 'roleNames', 'rolePermissions', 
            'availablePermissions', 'userPermissions'
        ));
    }

    public function updateUserRole(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        if ($user->issuperadmin() && !auth()->user()->issuperadmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot modify super administrator']);
        }

        $user->role = $request->input('role');
        $user->isactive = $request->input('isactive', 0);
        $user->isadmin = $request->has('isadmin');
        $user->save();

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    public function updatePermissions(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        if ($user->issuperadmin()) {
            return response()->json(['success' => false, 'message' => 'Super admin has all permissions']);
        }

        $permissions = $request->input('permissions', []);
        
        DB::transaction(function () use ($user, $permissions) {
            $user->permissions()->detach();
            
            foreach ($permissions as $permissionId) {
                $user->permissions()->attach($permissionId, ['id' => (string) Str::uuid()]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully']);
    }
}
