<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinYearController extends Controller
{
    public function index()
    {
        if (!auth()->user()->haspermission('canviewgrants')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to view Financial Years!'], 403);
        }
        
        $finyears = FinancialYear::all();
        return response()->json(['success' => true, 'data' => $finyears]);
    }

    //
    public function postnewfinyear(Request $request)
    {
        if(!auth()->user()->isadmin){
            return response()->json(['success' => false, 'message'=> 'You do not have permission to Add Financial Year!!'], 403);
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'finyear' => 'required|string', // Example rules, adjust as needed
            'startdate' => 'required|date', // Adjust data types as per your schema
            'enddate' => 'required|date',
        ];




        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response()->json(['success' => false, 'message' => 'Fill all the required Fields!'], 400);

        } 
        if(FinancialYear::where('finyear',$request->input('finyear'))->exists()){
            return response()->json(['success' => false, 'message'=> 'The Financial Year already Exists!!'], 409);
        }
        // Assuming you're retrieving grantno, departmentid, and userid from the request
        $year = new FinancialYear(); // Ensure the model name matches your actual model class name
        // Assign values from the request
        $year->startdate = $request->input('startdate');
        $year->finyear = $request->input('finyear');
        $year->enddate = $request->input('enddate');
        $year->save();

        // Optionally, return a response or redirect
        // return response()->json(['message' => 'Proposal created successfully'], 201);
        return response()->json(['success' => true, 'message'=> 'Financial Year Saved Successfully!!', 'data' => $year], 201);


    }

    public function fetchallfinyears()
    {
        try {
            $data = FinancialYear::all();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
}
