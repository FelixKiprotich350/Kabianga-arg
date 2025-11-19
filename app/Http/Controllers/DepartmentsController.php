<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\School;
use App\Models\Grant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    use ApiResponse;
    //
    public function postnewdepartment(Request $request)
    {
        if(!auth()->user()->isadmin){
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Add or Edit a Department!";
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'shortname' => 'required|string', // Example rules, adjust as needed
            'description' => 'required|string',  
            'schoolfk' => 'required|string',  
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);

        }

        // Assuming you're retrieving grantno, departmentid, and userid from the request
        $dep = new Department(); // Ensure the model name matches your actual model class name
        // Assign values from the request
        $dep->shortname = $request->input('shortname');
        $dep->description = $request->input('description'); 
        $dep->schoolfk=$request->input('schoolfk');
        $dep->save();

        // Optionally, return a response or redirect 
        return response(['message'=> 'Department Saved Successfully!!','type'=>'success']);


    }

    public function updatedepartment(Request $request, $id)
    {
        if(!auth()->user()->isadmin){
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Add or Edit a Department!";
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'description' => 'required|string', // Example rules, adjust as needed
            'shortname' => 'required|string', // Adjust data types as per your schema 
            'schoolfk' => 'required|string', // Adjust data types as per your schema 
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);

        }

        // Assuming you're retrieving grantno, departmentid, and userid from the request
        $dep = Department::findOrFail($id); // Ensure the model name matches your actual model class name
        // Assign values from the request
        $dep->shortname = $request->input('shortname');
        $dep->description = $request->input('description'); 
        $dep->schoolfk = $request->input('schoolfk'); 
        $dep->save();

        // Optionally, return a response or redirect
        // return response()->json(['message' => 'Proposal created successfully'], 201);
        return response(['message'=> 'Department Updated Successfully!!','type'=>'success']);


    }


    public function fetchalldepartments()
    {
        try {
            
            $data = Department::with('school')->withCount('users as staff_count')->get();
            
            return $this->successResponse($data, 'Departments retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch departments', $e->getMessage(), 500);
        }
    }

    public function fetchdepartmentsforproposals()
    {
        try {
            // Allow all authenticated users to fetch departments for proposal creation
            $data = Department::select('depid', 'shortname', 'description')
                ->with('school:schoolid,shortname')
                ->get();
            
            return $this->successResponse($data, 'Departments for proposals retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch departments', $e->getMessage(), 500);
        }
    }

    public function fetchsearchdepartments(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            $data = Department::where('shortname', 'like', '%' . $searchTerm . '%') 
                ->orWhere('description', 'like', '%' . $searchTerm . '%')
                ->with('school')
                ->withCount('users as staff_count')
                ->get();
            return $this->successResponse($data, 'Departments search completed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to search departments', $e->getMessage(), 500);
        }
    }
}
