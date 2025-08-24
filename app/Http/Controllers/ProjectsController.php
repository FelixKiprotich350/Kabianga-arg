<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ResearchFunding;
use App\Models\ResearchProgress;
use App\Models\ResearchProject;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    public function index()
    {
        return view('pages.projects.index');
    }
    
    //
    public function myprojects()
    {
        if (!auth()->user()->haspermission('canviewmyprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view your Projects!");
        }

        return view('pages.projects.my-projects');
    }
    public function fetchmyactiveprojects()
    {
        if (!auth()->user()->haspermission('canviewmyprojects')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }

        try {
            $userid = auth()->user()->userid;
            $myprojects = ResearchProject::with(['proposal.applicant'])
                ->whereHas('proposal', function ($query) use ($userid) {
                    $query->where('useridfk', $userid);
                })
                ->where('projectstatus', ResearchProject::STATUS_ACTIVE)
                ->get()
                ->map(function($project) {
                    return [
                        'researchid' => $project->researchid,
                        'researchnumber' => $project->researchnumber,
                        'title' => $project->proposal->researchtitle ?? 'Untitled',
                        'projectstatus' => $project->projectstatus,
                        'created_at' => $project->created_at
                    ];
                });
            return response()->json(['success' => true, 'data' => $myprojects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function fetchmyallprojects()
    {
        if (!auth()->user()->haspermission('canviewmyprojects')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }

        try {
            $userid = auth()->user()->userid;
            $myprojects = ResearchProject::with(['proposal.grantitem', 'proposal.applicant'])
                ->whereHas('proposal', function ($query) use ($userid) {
                    $query->where('useridfk', $userid);
                })
                ->get()
                ->map(function($project) {
                    return [
                        'researchid' => $project->researchid,
                        'researchnumber' => $project->researchnumber,
                        'title' => $project->proposal->researchtitle ?? 'Untitled Project',
                        'description' => $project->proposal->objectives ?? '',
                        'status' => $project->projectstatus ?? 'ACTIVE',
                        'researcher_name' => $project->proposal->applicant->name ?? 'N/A',
                        'progress' => 0,
                        'start_date' => $project->proposal->commencingdate ?? $project->created_at,
                        'created_at' => $project->created_at
                    ];
                });
            
            return response()->json(['success' => true, 'data' => $myprojects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function viewmyproject($id)
    {
        $project = ResearchProject::with(['proposal.applicant', 'proposal.department', 'applicant'])->findOrFail($id);
        return view('pages.projects.show', compact('project'));
    }

    public function allprojects()
    {
        if (!auth()->user()->haspermission('canviewallprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Projects!");
        }
        return view('pages.projects.index');
    }

    public function fetchallactiveprojects()
    {
        if (!auth()->user()->haspermission('canviewallprojects')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }

        try {
            $allprojects = ResearchProject::where('projectstatus', ResearchProject::STATUS_ACTIVE)
                ->with(['proposal.applicant'])
                ->get()
                ->map(function($project) {
                    return [
                        'researchid' => $project->researchid,
                        'researchnumber' => $project->researchnumber,
                        'title' => $project->proposal->researchtitle ?? 'Untitled',
                        'projectstatus' => $project->projectstatus,
                        'researcher' => $project->proposal->applicant->name ?? 'N/A',
                        'created_at' => $project->created_at
                    ];
                });
            return response()->json(['success' => true, 'data' => $allprojects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function fetchallprojects()
    {
        if (!auth()->user()->haspermission('canviewallprojects')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }

        try {
            $allprojects = ResearchProject::with(['proposal.applicant'])
                ->get()
                ->map(function($project) {
                    return [
                        'researchid' => $project->researchid,
                        'researchnumber' => $project->researchnumber,
                        'title' => $project->proposal->researchtitle ?? 'Untitled',
                        'description' => $project->proposal->objectives ?? '',
                        'status' => $project->projectstatus ?? 'ACTIVE',
                        'researcher_name' => $project->proposal->applicant->name ?? 'N/A',
                        'progress' => 0,
                        'start_date' => $project->proposal->commencingdate ?? $project->created_at,
                        'created_at' => $project->created_at
                    ];
                });
            return response()->json(['success' => true, 'data' => $allprojects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function fetchsearchallprojects(Request $request)
    {
        if (!auth()->user()->hasPermission('canviewallprojects')) {
            return response()->json([]);
        }

        $searchTerm = $request->input('search');

        // Fetch projects where the applicant's name or project status matches the search term
        $allprojects = ResearchProject::with(['proposal', 'applicant'])
            ->whereHas('applicant', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhere('projectstatus', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($allprojects);
    }

    public function viewanyproject($id)
    {
        if (!auth()->user()->hasPermission('canreadanyproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view this Project!");
        }

        $project = ResearchProject::with(['proposal.applicant', 'proposal.department', 'applicant'])->findOrFail($id);
        return view('pages.projects.show', compact('project'));
    }

    public function submitmyprogress(Request $request, $id)
    {
        $project = ResearchProject::with(['proposal.applicant'])->findOrFail($id);
        if (auth()->user()->userid != $project->applicant->userid) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not the owner of this Project!");
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'report' => 'required|string', // Example rules, adjust as needed
            'researchidfk' => 'required|string',
            'reportedbyfk' => 'required|string',
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);

        }
        $project = ResearchProject::with('applicant')->findOrFail($id);
        $item = new ResearchProgress();
        $item->researchidfk = $request->input('researchidfk');
        $item->reportedbyfk = $request->input('reportedbyfk');
        $item->report = $request->input('report');
        $item->save();
        //notify
        $mailingController = new MailingController();
        $url = route('pages.projects.viewanyproject', ['id' => $item->researchidfk]);
        $mailingController->notifyUsersOfProposalActivity('projectprogressreport', 'Project Progress!', 'success', ['Researcher ' . $project->applicant->name . ' has  Submitted his/her progress for this Project.', 'Project Refference : ' . $project->researchnumber], 'View Project', $url);

        // Optionally, return a response or redirect
        return response(['message' => 'Report Submitted Successfully!!', 'type' => 'success']);


    }
    public function assignme(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canassignmonitoringperson')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to Assign M & E!");
        }
        $rules = [
            'supervisorfk' => 'required|string',
        ];



        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $item = ResearchProject::findOrFail($id);

        $item->supervisorfk = $request->input('supervisorfk');
        $item->save();
        $mailingController = new MailingController();
        $url = route('pages.projects.viewanyproject', ['id' => $item->researchid]);
        $mailingController->notifyUsersOfProposalActivity('projectassignedmande', 'Project Monitoring Assignment!', 'success', ['This Project has been assigned M & E Team.'], 'View Project', $url);

        // Optionally, return a response or redirect
        return redirect(route('pages.projects.viewanyproject', ['id' => $id]));


    }
    public function pauseproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canpauseresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);

        if ($item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project already paused'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_PAUSED;
        $item->ispaused = true;
        $item->save();
        
        return response()->json(['success' => true, 'message' => 'Project paused successfully']);
    }
    public function resumeproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canresumeresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);
        if (!$item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project is not paused'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_ACTIVE;
        $item->ispaused = false;
        $item->save();

        return response()->json(['success' => true, 'message' => 'Project resumed successfully']);
    }
    public function cancelproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('cancancelresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $item = ResearchProject::findOrFail($id);

        if ($item->projectstatus != ResearchProject::STATUS_ACTIVE) {
            return response()->json(['success' => false, 'message' => 'Project must be active to cancel'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_CANCELLED;
        $item->save();
        
        return response()->json(['success' => true, 'message' => 'Project cancelled successfully']);
    }
    public function completeproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('cancompleteresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);

        if ($item->projectstatus != ResearchProject::STATUS_ACTIVE || $item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project must be active and not paused to complete'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_COMPLETED;
        $item->save();

        return response()->json(['success' => true, 'message' => 'Project completed successfully']);
    }
    public function fetchprojectprogress($id)
    {
        // Fetch projects where the related proposals' useridfk matches the current user
        $progresshistory = ResearchProgress::where('researchidfk', $id)->get();
        return response()->json($progresshistory);
    }

    public function addfunding(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canaddprojectfunding')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Add funds to this Project!");
        }

        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'amount' => 'required|int',
        ];

        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return response(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);

        }
        $tranches = ResearchFunding::where('researchidfk', $id)->count();
        if ($tranches >= 3) {                
            return response(['message' => 'This Project has reached the Maximum number of Funding Tranches!', 'type' => 'danger']);

        }
        $project = ResearchProject::with('proposal')->findOrFail($id);
        $commencingDate = Carbon::parse($project->proposal->commencingdate);
        if ($tranches == 1) {
            $commencingDatePlusSixMonths = $commencingDate->addMonths(6);
            if (Carbon::now()->isBefore($commencingDatePlusSixMonths)) {
                return response(['message' => 'You must wait until [' . $commencingDatePlusSixMonths->toDateString() . '] to get the second Tranch of Funding!', 'type' => 'danger']);
            }
        }
        if ($tranches == 2) {
            $commencingDatePlusNineMonths = $commencingDate->addMonths(9);
            if (Carbon::now()->isBefore($commencingDatePlusNineMonths)) {
                return response(['message' => 'You must wait until [' . $commencingDatePlusNineMonths->toDateString() . '] to get the Third Tranch of Funding!', 'type' => 'danger']);
            }
        }
        $item = new ResearchFunding();
        $item->researchidfk = $id;
        $item->createdby = Auth::user()->userid;
        $item->amount = $request->input('amount');
        $item->save();

        $mailingController = new MailingController();
        $url = route('pages.projects.viewanyproject', ['id' => $id]);
        $mailingController->notifyUsersOfProposalActivity('projectdfundingreleased', 'Project Funding Released!', 'success', ['This Project has received a funding of Ksh ' . $request->input('amount') . ', Dispatched by ' . auth()->user()->name], 'View Project', $url);

        // Optionally, return a response or redirect
        return response(['message' => 'Funding Release Submitted Successfully!!', 'type' => 'success']);


    }

    public function fetchprojectfunding($id)
    {
        $project = ResearchProject::with(['applicant'])->findOrFail($id);

        if (!auth()->user()->hasPermission('canviewprojectfunding') && $project->applicant->userid != auth()->user()->userid) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view this Project Funding!");
        }
        // Fetch projects where the related proposals' useridfk matches the current user
        $fundings = ResearchFunding::with('applicant')->where('researchidfk', $id)->get();
        $total = $fundings->sum('amount');
        $result = [
            'total' => $total,
            'fundingrows' => $fundings->count(),
            'fundingrecords' => $fundings,
        ];
        return response()->json($result);
    }
}
