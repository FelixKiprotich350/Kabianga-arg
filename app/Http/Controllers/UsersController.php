<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotifiableUser;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\NotifiesUsers;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // For generating UUIDs

class UsersController extends Controller
{
    use ApiResponse, NotifiesUsers;
    //

    public function updatebasicdetails(Request $request, $id)
    {

        if (Auth::user()->userid != $id && !Auth::user()->haspermission('canedituserprofile')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Edit this User!";
        }
        // Define validation rules
        $rules = [
            'fullname' => 'required|string',
            'email' => 'required|string',
            'phonenumber' => 'required|string',
            'pfno' => 'required|string',
            'highqualification' => 'nullable|string',
            'officenumber' => 'nullable|string',
            'faxnumber' => 'nullable|string',
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
        $user->highqualification = $request->input('highqualification');
        $user->officenumber = $request->input('officenumber');
        $user->faxnumber = $request->input('faxnumber');
        $user->save();
        return response()->json(['message' => 'User Updated Successfully!', 'type' => 'success']);



    }



    public function fetchallusers()
    {
        if (!auth()->user()->haspermission('canviewallusers')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }
        
        try {
            $data = User::all()->map(function ($user) {
                return [
                    'userid' => $user->userid,
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'pfno' => $user->pfno ?? '',
                    'phonenumber' => $user->phonenumber ?? '',
                    'highqualification' => $user->highqualification ?? '',
                    'officenumber' => $user->officenumber ?? '',
                    'faxnumber' => $user->faxnumber ?? '',
                    'isadmin' => $user->isadmin ?? false,
                    'isactive' => $user->isactive ?? true,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            });
            return $this->successResponse($data, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 500);
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

    public function addnotifiableusers(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canaddorremovenotifiableuser')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Add or Edit a Notifiable User!";
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
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Add or Remove a Notifiable User!";
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
        $users = User::select('userid', 'name', 'email', 'pfno', 'phonenumber', 'isadmin', 'isactive', 'created_at')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $users,
            'count' => $users->count()
        ]);
    }

    public function apiGetUser($id)
    {
        $user = User::with('permissions:pid,shortname,description')
            ->select('userid', 'name', 'email', 'pfno', 'phonenumber', 'isadmin', 'isactive', 'created_at')
            ->find($id);
        
        if (!$user) {
            return $this->errorResponse('User not found', null, 404);
        }

        return $this->successResponse($user, 'User retrieved successfully');
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
            'password' => 'required|string|min:6',
            'highqualification' => 'nullable|string',
            'officenumber' => 'nullable|string',
            'faxnumber' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $password = $request->input('password');
        
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->pfno = $request->input('pfno');
        $user->phonenumber = $request->input('phonenumber');
        $user->password = bcrypt($password);
        $user->highqualification = $request->input('highqualification');
        $user->officenumber = $request->input('officenumber');
        $user->faxnumber = $request->input('faxnumber');
        $user->isactive = true;
        $user->save();
        
        // Notify new user
        $this->notifyUserCreated($user, $password);

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
        
        // Notify user of account disable
        $this->notifyUserDisabled($user);

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
        
        // Notify user of account enable
        $this->notifyUserEnabled($user);

        return response()->json(['message' => 'User enabled successfully!', 'type' => 'success']);
    }



    public function updatePermissions(Request $request, $id)
    {
        \Log::info('updatePermissions called', [
            'user_id' => $id,
            'request_data' => $request->all(),
            'permissions' => $request->input('permissions', [])
        ]);
        
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        if ($user->issuperadmin()) {
            return response()->json(['success' => false, 'message' => 'Super admin has all permissions']);
        }

        $permissions = $request->input('permissions', []);
        \Log::info('Permissions to save', ['permissions' => $permissions]);
        
        try {
            // Clear existing permissions
            $deletedCount = DB::table('userpermissions')->where('useridfk', $id)->delete();
            \Log::info('Deleted permissions', ['count' => $deletedCount]);
            
            // Add new permissions
            $insertedCount = 0;
            foreach ($permissions as $permissionId) {
                DB::table('userpermissions')->insert([
                    'id' => (string) Str::uuid(),
                    'useridfk' => $id,
                    'permissionidfk' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $insertedCount++;
                \Log::info('Inserted permission', ['permission_id' => $permissionId]);
            }
            
            // Verify permissions were saved
            $finalCount = DB::table('userpermissions')->where('useridfk', $id)->count();
            \Log::info('Final verification', ['final_count' => $finalCount]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Permissions updated successfully',
                'debug' => [
                    'deleted' => $deletedCount,
                    'inserted' => $insertedCount,
                    'final_count' => $finalCount,
                    'permissions_sent' => $permissions
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating permissions', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false, 
                'message' => 'Error updating permissions: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canchangeuserroleorrights')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        if ($user->issuperadmin() && !auth()->user()->issuperadmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot modify super administrator status']);
        }

        $user->isactive = $request->input('isactive', 0);
        $user->save();
        
        if ($user->isactive) {
            $this->notifyUserEnabled($user);
        } else {
            $this->notifyUserDisabled($user);
        }

        return response()->json(['success' => true, 'message' => 'User status updated successfully']);
    }

    public function updateSuperAdmin(Request $request, $id)
    {
        if (!auth()->user()->issuperadmin()) {
            return response()->json(['success' => false, 'message' => 'Only super administrators can modify super admin status'], 403);
        }

        $user = User::findOrFail($id);
        $oldAdmin = $user->isadmin;
        
        $user->isadmin = $request->has('isadmin');
        $user->save();
        
        if ($oldAdmin != $user->isadmin) {
            if ($user->isadmin) {
                $this->notifyUserRoleChanged($user, 'Super Administrator');
            } else {
                $this->notifyUserRoleChanged($user, 'User');
            }
        }

        return response()->json(['success' => true, 'message' => 'Super admin status updated successfully']);
    }
}
