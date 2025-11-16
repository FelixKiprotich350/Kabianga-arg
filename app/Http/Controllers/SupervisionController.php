<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\SupervisionProgress;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Services\DualNotificationService;

class SupervisionController extends Controller
{
    use ApiResponse;
    //


    public function viewmonitoringpage($id)
    {
        $project = ResearchProject::with(['proposal.applicant', 'proposal.department', 'applicant'])->findOrFail($id);

        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'data' => ['project' => $project]]);
    }
    public function fetchmonitoringreport($id)
    {
        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            // Fetch projects where the related proposals' useridfk matches the current user
            $reports = SupervisionProgress::where('researchidfk', $id)->get();

            return $this->successResponse($reports, 'Monitoring reports retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch monitoring reports', $e->getMessage(), 500);
        }
    }
    public function addreport(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to Add funds to this Project!";
        }

        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'report' => 'required|string',
            'remark' => 'required|string',
        ];

        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);

        }
        $project = ResearchProject::findOrFail($id);
        $item = new SupervisionProgress();
        $item->researchidfk = $id;
        $item->supervisorfk = Auth::user()->userid;
        $item->report = $request->input('report');
        $item->remark = $request->input('remark');
        $item->save();
        //notify
        $notificationService = new DualNotificationService();
        $notificationService->notifyUsersOfProposalActivity('projectmonitoringreportsubmitted', 'Monitoring Report!', 'success', ['New Monitoring Report has been Submitted for this Project.', 'Project Reference : ' . $project->researchnumber], 'View Project', null);

        // Optionally, return a response or redirect
        return response(['message' => 'Report Submitted Successfully!!', 'type' => 'success']);


    }
}
