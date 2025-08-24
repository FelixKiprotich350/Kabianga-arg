<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Grant;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SchoolsController extends Controller
{
    //
    public function postnewschool(Request $request)
    {
        if(!auth()->user()->haspermission('canaddoreditschool')){
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Edit a Department!");
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'schoolname' => 'required|string', // Example rules, adjust as needed
            'description' => 'required|string',  
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);

        }
 
        $grant = new School(); 
        $grant->schoolname = $request->input('schoolname');
        $grant->description = $request->input('description'); 
        $grant->save();

        // Optionally, return a response or redirect 
        return response(['message'=> 'School Saved Successfully!!','type'=>'success']);


    }

    public function updateschool(Request $request, $id)
    {
        if(!auth()->user()->haspermission('canaddoreditschool')){
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Edit a Department!");
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'description' => 'required|string', // Example rules, adjust as needed
            'schoolname' => 'required|string', // Adjust data types as per your schema 
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);

        }

        // Assuming you're retrieving grantno, departmentid, and userid from the request
        $school = School::findOrFail($id); // Ensure the model name matches your actual model class name
        // Assign values from the request
        $school->schoolname = $request->input('schoolname');
        $school->description = $request->input('description'); 
        $school->save();

        // Optionally, return a response or redirect
        // return response()->json(['message' => 'Proposal created successfully'], 201);
        return response(['message'=> 'School Updated Successfully!!','type'=>'success']);


    }
    public function modernViewAllSchools()
    {
        if(!auth()->user()->haspermission('canviewdepartmentsandschools')){
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to View Schools!");
        }
        return view('pages.schools.index');
    }
    public function getviewschoolpage($id)
    {
        if(!auth()->user()->haspermission('canaddoreditdepartment')){
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Edit a Department!");
        }
        // Find the department by ID or fail with a 404 error
        $school = School::findOrFail($id);
        $isreadonlypage = true; 
        return view('pages.departments.schoolform', compact('school', 'isreadonlypage'));
    }
    public function geteditschoolpage($id)
    {
        if(!auth()->user()->haspermission('canaddoreditschool')){
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add or Edit a School!");
        }
        // Find the school by ID or fail with a 404 error
        $school = School::findOrFail($id);
        $isreadonlypage = false;
        $isadminmode = true; 
        // Return the view with the school data
        return view('pages.departments.schoolform', compact('isreadonlypage', 'isadminmode', 'school'));
    }

    public function fetchallschools()
    {
        try {
            $data = School::all();
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch schools'], 500);
        }
    }

    public function fetchsearchschools(Request $request)
    {
        $searchTerm = $request->input('search');
        $data = School::where('schoolname', 'like', '%' . $searchTerm . '%') 
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->withCount('departments')
            ->get();
        return response()->json(['data' => $data]);
    }
}
