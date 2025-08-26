<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\SupervisionProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class SupervisionController extends Controller
{
    //
    public function home()
    {
        if (!auth()->user()->hasPermission('cansupervise')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Monitor Projects!");
        }
        return view('pages.monitoring.index');
    }

    public function viewmonitoringpage($id)
    {
        $project = ResearchProject::with(['proposal.applicant', 'proposal.department', 'applicant'])->findOrFail($id);

        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Monitor this Project!");
        }

        return view('pages.monitoring.show', compact('project'));
    }
    public function fetchmonitoringreport($id)
    {

        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view this Project Funding!");
        }

        // Fetch projects where the related proposals' useridfk matches the current user
        $reports = SupervisionProgress::where('researchidfk', $id)->get();

        return response()->json($reports);
    }
    public function addreport(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canviewmonitoringpage')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add funds to this Project!");
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
        $url = route('pages.projects.viewanyproject', ['id' => $item->researchidfk]);
        $mailingController->notifyUsersOfProposalActivity('projectmonitoringreportsubmitted', 'Monitoring Report!', 'success', ['New Monitoring Report has been Submitted for this Project.', 'Project Refference : ' . $project->researchnumber], 'View Project', $url);

        // Optionally, return a response or redirect
        return response(['message' => 'Report Submitted Successfully!!', 'type' => 'success']);


    }
}
