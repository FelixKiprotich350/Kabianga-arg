<?php

namespace App\Http\Controllers;

use App\Models\ResearchFunding;
use App\Models\ResearchProgress;
use App\Models\ResearchProject;
use App\Models\User;
use App\Traits\NotifiesUsers;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    use NotifiesUsers;
 

    public function fetchallactiveprojects(Request $request)
    {
        $scope = $request->query('scope', 'all');
        $user = auth()->user();

        if ($scope === 'my') {
            $allprojects = ResearchProject::where('projectstatus', ResearchProject::STATUS_ACTIVE)
                ->with(['proposal.applicant'])
                ->whereHas('proposal', function ($query) use ($user) {
                    $query->where('useridfk', $user->userid);
                })
                ->get();
        } else {
            if (! $user->haspermission('canviewallprojects')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
            }
            $allprojects = ResearchProject::where('projectstatus', ResearchProject::STATUS_ACTIVE)
                ->with(['proposal.applicant'])
                ->get();
        }

        try {
            $data = $allprojects->map(function ($project) {
                return [
                    'researchid' => $project->researchid,
                    'researchnumber' => $project->researchnumber,
                    'title' => $project->proposal->researchtitle ?? 'Untitled',
                    'projectstatus' => $project->projectstatus,
                    'researcher' => $project->proposal->applicant->name ?? 'N/A',
                    'created_at' => $project->created_at,
                ];
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function fetchallprojects(Request $request)
    {
        $scope = $request->query('scope', 'all');
        $user = auth()->user();

        if ($scope === 'my') {
            $allprojects = ResearchProject::with(['proposal.applicant'])
                ->whereHas('proposal', function ($query) use ($user) {
                    $query->where('useridfk', $user->userid);
                })
                ->get();
        } else {
            if (! $user->haspermission('canviewallprojects')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
            }
            $allprojects = ResearchProject::with(['proposal.applicant'])->get();
        }

        try {
            $data = $allprojects->map(function ($project) {
                return [
                    'researchid' => $project->researchid,
                    'researchnumber' => $project->researchnumber,
                    'title' => $project->proposal->researchtitle ?? 'Untitled',
                    'description' => $project->proposal->objectives ?? '',
                    'status' => $project->projectstatus ?? 'ACTIVE',
                    'researcher_name' => $project->proposal->applicant->name ?? 'N/A',
                    'progress' => 0,
                    'start_date' => $project->proposal->commencingdate ?? $project->created_at,
                    'created_at' => $project->created_at,
                ];
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function fetchsearchallprojects(Request $request)
    {
        if (! auth()->user()->hasPermission('canviewallprojects')) {
            return response()->json([]);
        }

        $searchTerm = $request->input('search');

        // Fetch projects where the applicant's name or project status matches the search term
        $allprojects = ResearchProject::with(['proposal', 'applicant'])
            ->whereHas('applicant', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%'.$searchTerm.'%');
            })
            ->orWhere('projectstatus', 'like', '%'.$searchTerm.'%')
            ->get();

        return response()->json($allprojects);
    }

    public function viewanyproject($id)
    {
        if (! auth()->user()->hasPermission('canreadanyproject')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to view this Project!'], 403);
        }

        $project = ResearchProject::with(['proposal.applicant', 'proposal.department', 'applicant'])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $project]);
    }

    public function submitmyprogress(Request $request, $id)
    {
        $project = ResearchProject::with(['proposal.applicant'])->findOrFail($id);
        if (auth()->user()->userid != $project->applicant->userid) {
            return response()->json(['success' => false, 'message' => 'You are not the owner of this Project!'], 403);
        }
        // Validate incoming request data if needed
        // Define validation rules
        $rules = [
            'report' => 'required|string', // Example rules, adjust as needed
            'researchidfk' => 'required',
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
        $item = new ResearchProgress;
        $item->researchidfk = $request->input('researchidfk');
        $item->reportedbyfk = $request->input('reportedbyfk');
        $item->report = $request->input('report');
        $item->save();

        // Notify supervisors and admins using new system
        $this->notifyProgressSubmitted($project, $item);

        // Optionally, return a response or redirect
        return response(['message' => 'Report Submitted Successfully!!', 'type' => 'success']);

    }

    public function assignme(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('canassignmonitoringperson')) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to Assign M & E!'], 403);
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

        // Notify using new system
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $supervisor = User::findOrFail($request->input('supervisorfk'));
        $this->notifyProjectAssigned($project, $supervisor);

        return response()->json(['success' => true, 'message' => 'Supervisor assigned successfully']);

    }

    public function pauseproject(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('canpauseresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);

        if ($item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project already paused'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_PAUSED;
        $item->ispaused = true;
        $item->save();

        // Notify project owner
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $this->notifyProjectStatusChanged($project, 'PAUSED');

        return response()->json(['success' => true, 'message' => 'Project paused successfully']);
    }

    public function resumeproject(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('canresumeresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);
        if (! $item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project is not paused'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_ACTIVE;
        $item->ispaused = false;
        $item->save();

        // Notify project owner
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $this->notifyProjectStatusChanged($project, 'ACTIVE');

        return response()->json(['success' => true, 'message' => 'Project resumed successfully']);
    }

    public function cancelproject(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('cancancelresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $item = ResearchProject::findOrFail($id);

        if ($item->projectstatus != ResearchProject::STATUS_ACTIVE) {
            return response()->json(['success' => false, 'message' => 'Project must be active to cancel'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_CANCELLED;
        $item->save();

        // Notify project owner
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $this->notifyProjectStatusChanged($project, 'CANCELLED');

        return response()->json(['success' => true, 'message' => 'Project cancelled successfully']);
    }

    public function completeproject(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('cancompleteresearchproject')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $item = ResearchProject::findOrFail($id);

        if ($item->projectstatus != ResearchProject::STATUS_ACTIVE || $item->ispaused) {
            return response()->json(['success' => false, 'message' => 'Project must be active and not paused to complete'], 400);
        }
        $item->projectstatus = ResearchProject::STATUS_COMPLETED;
        $item->save();

        // Notify project owner
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $this->notifyProjectStatusChanged($project, 'COMPLETED');

        return response()->json(['success' => true, 'message' => 'Project completed successfully']);
    }

    public function fetchprojectprogress($id)
    {
        try {
            $progresshistory = ResearchProgress::where('researchidfk', $id)->get();
            
            return response()->json([
                'success' => true,
                'data' => $progresshistory,
                'message' => 'Progress history retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch progress history',
                'data' => []
            ], 500);
        }
    }

    public function addfunding(Request $request, $id)
    {
        if (! auth()->user()->hasPermission('canaddprojectfunding')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to Add funds to this Project!'], 403);
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
                return response(['message' => 'You must wait until ['.$commencingDatePlusSixMonths->toDateString().'] to get the second Tranch of Funding!', 'type' => 'danger']);
            }
        }
        if ($tranches == 2) {
            $commencingDatePlusNineMonths = $commencingDate->addMonths(9);
            if (Carbon::now()->isBefore($commencingDatePlusNineMonths)) {
                return response(['message' => 'You must wait until ['.$commencingDatePlusNineMonths->toDateString().'] to get the Third Tranch of Funding!', 'type' => 'danger']);
            }
        }
        $item = new ResearchFunding;
        $item->researchidfk = $id;
        $item->createdby = Auth::user()->userid;
        $item->amount = $request->input('amount');
        $item->save();

        // Send notification using new system
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($id);
        $this->notifyFundingAdded($project, $request->input('amount'));

        // Optionally, return a response or redirect
        return response(['message' => 'Funding Release Submitted Successfully!!', 'type' => 'success']);

    }

    public function fetchprojectfunding($id)
    {
        $project = ResearchProject::with(['applicant'])->findOrFail($id);

        if (! auth()->user()->hasPermission('canviewprojectfunding') && $project->applicant->userid != auth()->user()->userid) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to view this Project Funding!'], 403);
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
