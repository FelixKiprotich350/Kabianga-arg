<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\ApprovalStatus;
use App\Models\Collaborator;
use App\Models\Department;
use App\Models\Expenditureitem;
use App\Models\FinancialYear;
use App\Models\GlobalSetting;
use App\Models\Grant;
use App\Models\Proposal;
use App\Models\ProposalChanges;
use App\Models\Publication;
use App\Models\ReceivedStatus;
use App\Models\ResearchDesignItem;
use App\Models\ResearchProject;
use App\Models\ResearchTheme;
use App\Models\SubmittedStatus;
use App\Models\User;
use App\Models\Workplan;
use App\Services\DualNotificationService;
use App\Traits\ApiResponse;
use App\Traits\NotifiesUsers;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProposalsController extends Controller
{
    use ApiResponse, NotifiesUsers;

    public function index()
    {
        return response()->json(['message' => 'Proposals API endpoint', 'status' => 'active']);
    }

    //
    public function modernNewProposal()
    {
        if (! auth()->user()->haspermission('canmakenewproposal')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to Make a new Proposal!'], 403);
        }
        $themes = ResearchTheme::all();
        $departments = Department::select('depid', 'shortname', 'description')->get();
        $user = auth()->user();
        $currentgrant = GlobalSetting::where('item', 'current_open_grant')->first();
        $grants = collect();
        if ($currentgrant) {
            $grants = Grant::where('grantid', $currentgrant->value1)
                ->whereDoesntHave('proposals', function ($query) use ($user) {
                    $query->where('useridfk', $user->userid);
                })->get();
        }

        return response()->json(['success' => true, 'data' => compact('grants', 'themes', 'departments')]);
    }

    public function postnewproposal(Request $request)
    {
        if (! auth()->user()->haspermission('canmakenewproposal')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to Make a new Proposal!'], 403);
        }
        // Define validation rules
        $rules = [
            'grantnofk' => 'required|integer',
            'departmentfk' => 'required|string',
            'themefk' => 'required|string',
            'researchtitle' => 'required|string',
        ];

        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Check if the grant has already been applied for
        if ($this->isgrantapplied($request->input('grantnofk'))) {
            // Proposal exists for the current user and grant number
            return response()->json([
                'success' => false,
                'message' => 'Proposal exists for grant number',
            ], 409);
        }
        $grant = Grant::findOrFail($request->input('grantnofk'));
        // Generate proposal code
        $currentYear = date('Y');
        $currentMonth = date('Y');
        $lastRecord = Proposal::orderBy('proposalid', 'desc')->first();
        $incrementNumber = $lastRecord ? $lastRecord->proposalid + 1 : 1;
        $generatedCode = 'UOK/ARG/P/'.$currentYear.'/'.$currentMonth.'/'.$incrementNumber;

        // Create a new proposal instance
        $proposal = new Proposal;

        // Assign values from the request
        $proposal->proposalid = $incrementNumber;
        $proposal->proposalcode = $generatedCode;
        $proposal->grantnofk = $request->input('grantnofk');
        $proposal->departmentidfk = $request->input('departmentfk');
        $proposal->useridfk = Auth::user()->userid;
        $proposal->pfnofk = Auth::user()->pfno;
        $proposal->approvalstatus = ApprovalStatus::PENDING;
        $proposal->submittedstatus = SubmittedStatus::PENDING;
        $proposal->receivedstatus = ReceivedStatus::PENDING;
        $proposal->allowediting = true;
        $proposal->themefk = $request->input('themefk');
        $proposal->researchtitle = $request->input('researchtitle');

        // Save the proposal
        $proposal->save();

        // Return JSON response for API
        return response()->json([
            'success' => true,
            'message' => 'Basic Details Saved Successfully! Continue editing your proposal.',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success',
        ], 201);
    }

    private function isgrantapplied($grantno)
    {
        try {
            // Get the current user's ID (assuming you have authenticated users)
            $userid = auth()->user()->userid;

            // Check if a proposal exists with the given $grantno and for the current user
            $proposalExists = Proposal::where('grantnofk', $grantno)
                ->where('useridfk', $userid)
                ->exists();

            return $proposalExists;
        } catch (Exception $e) {
            // Handle any exceptions, such as database errors
            return true; // Return true to indicate an error occurred (adjust as needed)
        }
    }

    public function updatebasicdetails(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if (! auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to edit this Proposal. Only the owner can Edit!',
            ], 403);
        }
        if (! $proposal->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal cannot be edited at this time.',
            ], 403);
        }
        $rules = [
            'grantnofk' => 'required|integer',
            'departmentfk' => 'required|string',
            'themefk' => 'required|string',
        ];

        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Assign values from the request
        $proposal->departmentidfk = $request->input('departmentfk');
        $proposal->grantnofk = $request->input('grantnofk');
        $proposal->themefk = $request->input('themefk');
        // Save the proposal
        $proposal->save();

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Basic Details Saved Successfully!!',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success',
        ], 200);
    }

    public function updateresearchdetails(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if (! auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to edit this Proposal. Only the owner can Edit!',
            ], 403);
        }
        if (! $proposal->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal cannot be edited at this time.',
            ], 403);
        }
        $rules = [
            'researchtitle' => 'required|string', // Example rules, adjust as needed
            'objectives' => 'required|string',
            'hypothesis' => 'required|string',
            'significance' => 'required|string',
            'ethicals' => 'required|string',
            'outputs' => 'required|string',
            'economicimpact' => 'required|string',
            'res_findings' => 'required|string',
            'terminationdate' => 'required|date',
            'commencingdate' => 'required|date',
        ];

        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Assign values from the request
        $proposal->researchtitle = $request->input('researchtitle');
        $proposal->objectives = $request->input('objectives');
        $proposal->hypothesis = $request->input('hypothesis');
        $proposal->significance = $request->input('significance'); // Example qualification
        $proposal->ethicals = $request->input('ethicals');
        $proposal->expoutput = $request->input('outputs');
        $proposal->socio_impact = $request->input('economicimpact');
        $proposal->res_findings = $request->input('res_findings');
        $proposal->commencingdate = $request->input('commencingdate');
        $proposal->terminationdate = $request->input('terminationdate');
        // Save the proposal
        $proposal->save();

        return response()->json([
            'success' => true,
            'message' => 'Research Details Saved Successfully!!',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success',
        ], 200);
    }

    public function submitproposal(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if (! auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Submit this Proposal. Only the owner can Submit!',
            ], 403);
        }
        if ($proposal->submittedstatus == SubmittedStatus::SUBMITTED) {
            return response(['message' => 'Application has already been submitted!', 'type' => 'danger']);
        }
        if ($proposal->receivedstatus == ReceivedStatus::RECEIVED) {
            return response(['message' => 'This Proposal has been received before!!', 'type' => 'danger']);
        }
        $cansubmit = $this->cansubmit($id);
        if (isset($cansubmit)) {
            $proposal->submittedstatus = SubmittedStatus::SUBMITTED;
            $proposal->allowediting = false;
            $proposal->save();

            // Send dual notifications (in-app + email)
            $this->notifyProposalSubmitted($proposal);

            // notifiable users to be informed of new proposal
            $notificationService = new DualNotificationService;
            $url = '/api/v1/proposals/'.$proposal->proposalid;
            $notificationService->notifyUsersOfProposalActivity('proposalsubmitted', 'New Proposal', 'success', ['You have a New Proposal Pending Receival and processing.'], 'View Proposal', $url);

            return response(['message' => 'Application Submitted Successfully!!', 'type' => 'success']);
        } else {
            return response(['message' => 'Application not ready for Submission. Has incomplete Details!', 'type' => 'warning']);
        }

    }

    public function receiveproposal(Request $request, $id)
    {
        if (! auth()->user()->haspermission('canreceiveproposal')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to receive this Proposal!',
            ], 403);
        }

        $proposal = Proposal::findOrFail($id);
        if ($proposal->submittedstatus != SubmittedStatus::SUBMITTED) {
            return response(['message' => 'This proposal has not been submitted!', 'type' => 'warning']);
        }
        if ($proposal->receivedstatus == ReceivedStatus::RECEIVED) {
            return response(['message' => 'This Proposal has been received before!!', 'type' => 'danger']);
        }
        $proposal->receivedstatus = ReceivedStatus::RECEIVED;
        $proposal->allowediting = false;
        $proposal->save();

        // Send dual notifications
        $this->notifyProposalReceived($proposal);

        $notificationService = new DualNotificationService;
        $Url = '/api/v1/proposals/'.$proposal->proposalid;
        $notificationService->notifyUsersOfProposalActivity('proposalreceived', 'Proposal Received!', 'success', ['Your Proposal has been Received Successfully.'], 'View Proposal', $Url);

        return response(['message' => 'Proposal received Successfully!!', 'type' => 'success']);

    }

    public function changeeditstatus(Request $request, $id)
    {
        if (! auth()->user()->haspermission('canenabledisableproposaledit')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Enable or Disable editing of this Proposal!',
            ], 403);
        }

        $proposal = Proposal::findOrFail($id);

        $proposal->allowediting = false;
        $proposal->save();
        $notificationService = new DualNotificationService;
        $notificationService->notifyUserReceivedProposal($proposal);

        return response(['message' => 'Proposal received Successfully!!', 'type' => 'success']);

    }

    public function approverejectproposal(Request $request, $id)
    {
        if ($request->input('status') == ApprovalStatus::APPROVED->value && auth()->user()->haspermission('canapproveproposal')) {
        } elseif ($request->input('status') == ApprovalStatus::REJECTED->value && auth()->user()->haspermission('canrejectproposal')) {
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Approve/Reject this Proposal!',
            ], 403);
        }

        $rules = [
            'comment' => 'required|string',
            'status' => 'required|string',
            'fundingfinyearfk' => [
                'required_if:status,Approved',
                'nullable',
                'string',
            ],
        ];
        // Validate incoming request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Please provide a comment,Funding Year & status!', 'type' => 'warning'], 400);
        }

        $proposal = Proposal::findOrFail($id);
        if ($proposal->submittedstatus != SubmittedStatus::SUBMITTED) {
            return response(['message' => 'This Proposal has not been Submitted by the owner!!', 'type' => 'danger']);
        }
        if ($proposal->receivedstatus != ReceivedStatus::RECEIVED) {
            return response(['message' => 'This Proposal has not been Received by the office!!', 'type' => 'danger']);
        }
        if ($proposal->approvalstatus == ApprovalStatus::REJECTED || ResearchProject::where('proposalidfk', $id)->exists()) {
            return response(['message' => 'This Proposal has been Approved or Rejected before!!', 'type' => 'danger']);
        }
        DB::transaction(function () use ($id, $request) {
            $proposal = Proposal::findOrFail($id);
            $proposal->approvalstatus = $request->input('status');
            $proposal->comment = $request->input('comment');
            $proposal->allowediting = false;
            $proposal->saveOrFail();

            $yearid = GlobalSetting::where('item', 'current_fin_year')->first();
            $currentyear = FinancialYear::findOrFail($yearid->value1);

            if ($request->input('status') == ApprovalStatus::APPROVED->value) {
                $lastRecord = ResearchProject::orderBy('researchid', 'desc')->first();
                $incrementNumber = $lastRecord ? $lastRecord->researchid + 1 : 1;
                $generatedCode = 'UOK/ARG/'.$currentyear->finyear.'/'.$incrementNumber;
                // new project
                $project = new ResearchProject;
                $project->researchnumber = $generatedCode;
                $project->proposalidfk = $proposal->proposalid;
                $project->projectstatus = ResearchProject::STATUS_ACTIVE;
                $project->ispaused = false;
                $project->fundingfinyearfk = $request->input('fundingfinyearfk');
                $project->saveOrFail();
            }

        });
        if ($request->input('status') == ApprovalStatus::APPROVED->value) {
            $project = ResearchProject::where('proposalidfk', $id)->firstOrFail();
            $notificationService = new DualNotificationService;
            $url = '/api/v1/projects/'.$project->researchid;
            $notificationService->notifyUsersOfProposalActivity('proposalapproved', 'Proposal Approved!', 'success', ['This Proposal has been Approved Successfully.', 'The project will kick off on the indicated Start Date.'], 'View Project', $url);

            return response(['message' => 'Proposal Approved Successfully! Project Started!', 'type' => 'success']);
        } elseif ($request->input('status') == ApprovalStatus::REJECTED->value) {
            $notificationService = new DualNotificationService;
            $url = '/api/v1/proposals/'.$id;
            $notificationService->notifyUsersOfProposalActivity('proposalrejected', 'Proposal Rejected', 'success', ['The project didnt qualify for further steps.'], 'View Proposal', $url);

            return response(['message' => 'Proposal Rejected Successfully!!', 'type' => 'danger']);
        } else {
            return response(['message' => 'Unknown Action on Status!!', 'type' => 'danger']);
        }

    }

    public function cansubmit($id)
    {
        $response = $this->querysubmissionstatus($id);
        if ($response['basic'] == 2 && $response['design'] == 2 && $response['expenditure'] == 2 && $response['workplan'] == 2 && $response['researchinfo'] == 2) {
            return true;
        } else {
            return false;
        }

    }

    public function canapprove($id)
    {
        $user = User::findOrFail($id);

        if ($user->haspermission($id)) {
            return true;
        } else {
            return false;
        }

    }

    public function viewallproposals()
    {
        if (! auth()->user()->haspermission('canviewallapplications')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to view all Proposals!'], 403);
        }

        return response()->json(['message' => 'View all proposals endpoint', 'status' => 'active']);
    }

    public function getsingleproposalpage($id)
    {
        try {
            $user = Auth::user();
            $prop = Proposal::with(['applicant', 'department', 'themeitem', 'grantitem'])->findOrFail($id);

            if (! $user->haspermission('canreadproposaldetails') && $user->userid != $prop->useridfk) {
                return response()->json(['success' => false, 'message' => 'You are not Authorized to read the requested Proposal!'], 403);
            }

            $finyears = FinancialYear::all();

            return response()->json(['success' => true, 'data' => compact('prop', 'finyears')]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load proposal details: '.$e->getMessage()], 500);
        }
    }

    public function printpdf($id)
    {
        try {
            $proposal = Proposal::with([
                'applicant:userid,name,email,phonenumber,gender',
                'department:depid,shortname,description',
                'themeitem:themeid,themename',
                'grantitem:grantid,title,status',
                'expenditures:expenditureid,proposalidfk,item,itemtype,quantity,unitprice,total',
                'researchdesigns:designid,proposalidfk,summary,indicators,goal',
                'workplans:workplanid,proposalidfk,activity,time,input,bywhom',
                'collaborators:collaboratorid,proposalidfk,collaboratorname,position,institution',
                'publications:publicationid,proposalidfk,title,publisher,year',
            ])->findOrFail($id);

            $html = $this->generateProposalHtml($proposal);
            $filename = 'Research-Proposal-'.str_replace(['/', ' ', '\\'], ['-', '-', '-'], $proposal->proposalcode).'.pdf';

            $pdf = SnappyPdf::loadHTML($html);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);

        } catch (Exception $e) {
            \Log::error('PDF Generation Error: '.$e->getMessage(), [
                'proposal_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to generate PDF. Please try again later.',
                'message' => config('app.debug') ? $e->getMessage() : 'PDF generation failed',
            ], 500);
        }
    }

    public function testSnappy()
    {
        try {
            $html = '<html><body><h1>Test PDF Generation with Snappy</h1><p>This is a test document to verify that laravel-snappy is working correctly.</p><p>Generated at: '.now().'</p></body></html>';

            $pdf = SnappyPdf::loadHTML($html);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="test-snappy.pdf"',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Snappy test failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function fetchallproposals(Request $request)
    {
        $scope = $request->query('scope', 'all');
        $user = auth()->user();

        if ($scope === 'my') {
            $proposals = Proposal::where('useridfk', $user->userid)
                ->with('department', 'grantitem.financialyear', 'themeitem', 'applicant')
                ->get();
        } else {
            if (! $user->haspermission('canviewallapplications')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
            }
            $proposals = Proposal::with('department', 'grantitem.financialyear', 'themeitem', 'applicant')->get();
        }

        try {
            $data = $proposals->map(function ($proposal) {
                return [
                    'proposalid' => $proposal->proposalid,
                    'researchtitle' => $proposal->researchtitle,
                    'objectives' => $proposal->objectives,
                    'approvalstatus' => $proposal->approvalstatus,
                    'theme_name' => $proposal->themeitem->themename ?? 'N/A',
                    'financial_year' => $proposal->grantitem->financialyear->finyear ?? 'N/A',
                    'created_at' => $proposal->created_at,
                    'applicant_name' => $proposal->applicant->name ?? 'N/A',
                    'department_name' => $proposal->department->departmentname ?? 'N/A',
                    'is_editable' => $proposal->isEditable(),
                ];
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function fetchsingleproposal($id)
    {
        try {
            $proposal = Proposal::with([
                'department', 
                'grantitem', 
                'themeitem', 
                'applicant',
                'expenditures',
                'researchdesigns',
                'workplans',
                'collaborators',
                'publications'
            ])->findOrFail($id);

            return response()->json(['success' => true, 'data' => $proposal]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchsearchproposals(Request $request)
    {
        if (! auth()->user()->haspermission('canviewallapplications')) {
            return response()->json(['success' => false, 'message' => 'You are not Authorized to view all Proposals!'], 403);
        }
        $searchTerm = $request->input('search');
        $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')
            ->where('approvalstatus', 'like', '%'.$searchTerm.'%')
            ->orWhere('highqualification', 'like', '%'.$searchTerm.'%')
            ->orWhereHas('themeitem', function ($query) use ($searchTerm) {
                $query->where('themename', 'like', '%'.$searchTerm.'%');
            })
            ->orWhereHas('applicant', function ($query1) use ($searchTerm) {
                $query1->where('name', 'like', '%'.$searchTerm.'%');
            })
            ->orWhereHas('department', function ($query) use ($searchTerm) {
                $query->where('shortname', 'like', '%'.$searchTerm.'%');
            })
            ->get();

        return response()->json($data); // Return filtered data as JSON
    }

    public function fetchcollaborators($id)
    {
        try {
            $data = Collaborator::where('proposalidfk', $id)->get();

            return $this->successResponse($data, 'Collaborators retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch collaborators', $e->getMessage(), 500);
        }
    }

    public function fetchpublications($id)
    {
        try {
            $data = Publication::where('proposalidfk', $id)->get();

            return $this->successResponse($data, 'Publications retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch publications', $e->getMessage(), 500);
        }
    }

    public function fetchexpenditures($id)
    {
        try {
            $data = Expenditureitem::where('proposalidfk', $id)->get();
            $summary = [];
            $totalOthers = $data->where('itemtype', 'Others')->sum('total');
            $totalTravels = $data->where('itemtype', 'Travels')->sum('total');
            $totalConsumables = $data->where('itemtype', 'Consumables')->sum('total');
            $totalFacilities = $data->where('itemtype', 'Facilities')->sum('total');

            $summary['totalOthers'] = $totalOthers;
            $summary['totalTravels'] = $totalTravels;
            $summary['totalConsumables'] = $totalConsumables;
            $summary['totalFacilities'] = $totalFacilities;
            $summary['totalExpenditures'] = $totalFacilities + $totalConsumables + $totalTravels + $totalOthers;

            $rule_40 = $totalOthers + $totalTravels;
            $rule_60 = $totalFacilities + $totalConsumables;
            $summary['isValidBudget'] = $this->getIsValidBudget($rule_40, $rule_60);

            return $this->successResponse(['items' => $data, 'summary' => $summary], 'Expenditures retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch expenditures', $e->getMessage(), 500);
        }
    }

    private function getIsValidBudget($rule_40, $rule_60)
    {
        $total = $rule_40 + $rule_60;
        if ($rule_40 <= (0.4 * $total)) {
            return true;
        } else {
            return false;
        }
    }

    public function fetchworkplanitems($id)
    {
        try {
            $data = Workplan::where('proposalidfk', $id)->get();

            return $this->successResponse($data, 'Workplan items retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch workplan items', $e->getMessage(), 500);
        }
    }

    public function fetchresearchdesign($id)
    {
        try {
            $data = ResearchDesignItem::where('proposalidfk', $id)->get();

            return $this->successResponse($data, 'Research design items retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch research design items', $e->getMessage(), 500);
        }
    }

    public function querysubmissionstatus($id)
    {
        // 0 -not required
        // 1 -not completed
        // 2 -completed
        $prop = Proposal::findOrFail($id);
        $researchinfo = 1;
        if ($prop->researchtitle && $prop->objectives && $prop->commencingdate && $prop->terminationdate && $prop->hypothesis && $prop->significance && $prop->ethicals && $prop->expoutput && $prop->socio_impact && $prop->res_findings) {
            $researchinfo = 2;
        }
        $basic = ($prop) ? 2 : 1;
        $design = (ResearchDesignItem::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $finanncials = (Expenditureitem::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $workplan = (Workplan::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $collaborators = (Collaborator::where('proposalidfk', $id)->count() > 0) ? 2 : 1;

        $publications = (Publication::where('proposalidfk', $id)->count() > 0) ? 2 : 1;

        $appstatus = ['basic' => $basic, 'researchinfo' => $researchinfo, 'design' => $design, 'workplan' => $workplan, 'collaborators' => $collaborators, 'publications' => $publications, 'expenditure' => $finanncials];

        return $appstatus;
    }

    public function fetchsubmissionstatus($id)
    {
        $data = $this->querysubmissionstatus($id);
        $cansubmit = $this->cansubmit($id);

        return response(['data' => $data, 'cansubmitstatus' => $cansubmit]);
    }

    public function fetchproposalchanges($id)
    {
        $data = ProposalChanges::where('proposalidfk', $id)->with('suggestedby')->get();

        return response()->json($data);
    }

    public function budgetValidation($id)
    {
        $expenditures = Expenditureitem::where('proposalidfk', $id)->get();

        $facilitiesTotal = $expenditures->where('itemtype', 'Facilities/Equipment')->sum('total');
        $consumablesTotal = $expenditures->where('itemtype', 'Consumables')->sum('total');
        $personnelTotal = $expenditures->where('itemtype', 'Personnel/Subsistence')->sum('total');
        $travelTotal = $expenditures->where('itemtype', 'Travel/Other')->sum('total');

        $researchTotal = $facilitiesTotal + $consumablesTotal;
        $totalBudget = $facilitiesTotal + $consumablesTotal + $personnelTotal + $travelTotal;

        $researchPercentage = $totalBudget > 0 ? ($researchTotal / $totalBudget) * 100 : 0;
        $isCompliant = $researchPercentage >= 60;

        return response()->json([
            'total_budget' => $totalBudget,
            'facilities_equipment' => $facilitiesTotal,
            'consumables' => $consumablesTotal,
            'personnel_subsistence' => $personnelTotal,
            'travel_other' => $travelTotal,
            'research_total' => $researchTotal,
            'research_percentage' => round($researchPercentage, 2),
            'is_compliant' => $isCompliant,
            'status' => $isCompliant ? 'Compliant' : 'Non-compliant',
            'message' => $isCompliant ? '60% rule met' : 'Research items < 60%',
        ]);
    }

    public function approveProposal(Request $request, $id)
    {
        try {
            if (! auth()->user()->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string',
                'fundingfinyearfk' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Please select a valid funding year'], 400);
            }

            $fundingYear = $request->input('fundingfinyearfk');
            if (! $fundingYear || $fundingYear === 'undefined' || $fundingYear === '') {
                return response()->json(['success' => false, 'message' => 'Please select a funding year'], 400);
            }

            $proposal = Proposal::findOrFail($id);

            if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
                return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
            }

            DB::transaction(function () use ($proposal, $request) {
                $proposal->approvalstatus = ApprovalStatus::APPROVED;
                $proposal->comment = $request->input('comment', 'Approved');
                $proposal->allowediting = false;
                $proposal->save();

                $yearid = GlobalSetting::where('item', 'current_fin_year')->first();
                if (! $yearid) {
                    throw new Exception('Current financial year not set');
                }

                $currentyear = FinancialYear::findOrFail($yearid->value1);
                $lastRecord = ResearchProject::orderBy('researchid', 'desc')->first();
                $incrementNumber = $lastRecord ? $lastRecord->researchid + 1 : 1;
                $generatedCode = 'UOK/ARG/'.$currentyear->finyear.'/'.$incrementNumber;

                $project = new ResearchProject;
                $project->researchnumber = $generatedCode;
                $project->proposalidfk = $proposal->proposalid;
                $project->projectstatus = ResearchProject::STATUS_ACTIVE;
                $project->ispaused = false;
                $project->fundingfinyearfk = $request->input('fundingfinyearfk');
                $project->save();
            });

            // Send dual notifications
            $this->notifyProposalApproved($proposal);

            return response()->json(['success' => true, 'message' => 'Proposal approved successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function rejectProposal(Request $request, $id)
    {
        if (! auth()->user()->haspermission('canrejectproposal')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Comment is required'], 400);
        }

        $proposal = Proposal::findOrFail($id);

        if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
            return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
        }

        $proposal->approvalstatus = ApprovalStatus::REJECTED;
        $proposal->comment = $request->input('comment');
        $proposal->allowediting = false;
        $proposal->save();

        // Send dual notifications
        $this->notifyProposalRejected($proposal);

        $notificationService = new DualNotificationService;
        $url = '/api/v1/proposals/'.$id;
        $notificationService->notifyUsersOfProposalActivity('proposalrejected', 'Proposal Rejected', 'danger', ['The project didnt qualify for further steps.'], 'View Proposal', $url);

        return response()->json(['success' => true, 'message' => 'Proposal rejected']);
    }

    public function markAsDraft(Request $request, $id)
    {
        try {
            if (! auth()->user()->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $proposal = Proposal::findOrFail($id);

            if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
                return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
            }

            $proposal->approvalstatus = ApprovalStatus::DRAFT;
            $proposal->allowediting = true;
            $proposal->save();

            return response()->json(['success' => true, 'message' => 'Proposal marked as draft']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function requestChanges(Request $request, $id)
    {
        try {
            if (! auth()->user()->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'triggerissue' => 'required|string',
                'suggestedchange' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Both issue and suggested changes are required'], 400);
            }

            $proposal = Proposal::findOrFail($id);

            if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
                return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
            }

            $change = new ProposalChanges;
            $change->proposalidfk = $id;
            $change->suggestedbyfk = auth()->user()->userid;
            $change->triggerissue = $request->input('triggerissue');
            $change->suggestedchange = $request->input('suggestedchange');
            $change->status = 'Pending';
            $change->save();

            $proposal->allowediting = true;
            $proposal->save();

            // Send dual notifications
            $this->notifyChangesRequested($proposal);

            return response()->json(['success' => true, 'message' => 'Change request sent']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    private function generateProposalHtml($proposal)
    {
        $totalBudget = $proposal->expenditures->sum('total');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Research Proposal - '.($proposal->proposalcode ?? 'N/A').'</title>
            <style>
                body { font-family: "Times New Roman", serif; margin: 0; padding: 20px; line-height: 1.6; color: #333; }
                .header { text-align: center; border-bottom: 3px solid #2c5aa0; padding-bottom: 20px; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; color: #2c5aa0; margin-bottom: 10px; }
                .university { font-size: 18px; color: #666; }
                .title { font-size: 20px; font-weight: bold; margin: 30px 0; text-align: center; color: #2c5aa0; }
                .section { margin: 25px 0; page-break-inside: avoid; }
                .section-title { font-size: 16px; font-weight: bold; color: #2c5aa0; border-bottom: 2px solid #e0e0e0; padding-bottom: 5px; margin-bottom: 15px; }
                .field { margin: 10px 0; }
                .label { font-weight: bold; color: #555; display: inline-block; min-width: 150px; }
                .value { color: #333; }
                .content { margin: 15px 0; text-align: justify; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; font-weight: bold; }
                .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #e0e0e0; padding-top: 20px; }
                .status-badge { padding: 5px 10px; border-radius: 4px; color: white; font-weight: bold; }
                .status-pending { background-color: #ffc107; }
                .status-approved { background-color: #28a745; }
                .status-rejected { background-color: #dc3545; }
            </style>
        </head>
        <body>
            <div class="header">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0 0%, #1e3d6f 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                    <div style="color: white; font-weight: bold; font-size: 24px; text-align: center; line-height: 1;">UK</div>
                </div>
                <div class="logo">UNIVERSITY OF KABIANGA</div>
                <div class="university">Annual Research Grants Portal</div>
            </div>
            
            <div class="title">RESEARCH PROPOSAL</div>
            
            <div class="section">
                <div class="section-title">BASIC INFORMATION</div>
                <div class="field"><span class="label">Proposal Code:</span> <span class="value">'.($proposal->proposalcode ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Research Title:</span> <span class="value">'.($proposal->researchtitle ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Principal Investigator:</span> <span class="value">'.($proposal->applicant->name ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Department:</span> <span class="value">'.($proposal->department->shortname ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Research Theme:</span> <span class="value">'.($proposal->themeitem->themename ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Grant:</span> <span class="value">'.($proposal->grantitem->title ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Duration:</span> <span class="value">'.($proposal->commencingdate ?? 'N/A').' to '.($proposal->terminationdate ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Status:</span> <span class="status-badge status-'.strtolower($proposal->approvalstatus->value ?? 'pending').'">'.($proposal->approvalstatus->value ?? 'N/A').'</span></div>
            </div>
            
            <div class="section">
                <div class="section-title">RESEARCH OBJECTIVES</div>
                <div class="content">'.nl2br($proposal->objectives ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">HYPOTHESIS</div>
                <div class="content">'.nl2br($proposal->hypothesis ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">SIGNIFICANCE OF THE STUDY</div>
                <div class="content">'.nl2br($proposal->significance ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">ETHICAL CONSIDERATIONS</div>
                <div class="content">'.nl2br($proposal->ethicals ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">EXPECTED OUTPUTS</div>
                <div class="content">'.nl2br($proposal->expoutput ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">SOCIO-ECONOMIC IMPACT</div>
                <div class="content">'.nl2br($proposal->socio_impact ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">RESEARCH FINDINGS UTILIZATION</div>
                <div class="content">'.nl2br($proposal->res_findings ?? 'Not specified').'</div>
            </div>'.
            
            ($proposal->expenditures->count() > 0 ? '
            <div class="section">
                <div class="section-title">BUDGET BREAKDOWN</div>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit Price (KES)</th>
                            <th>Total (KES)</th>
                        </tr>
                    </thead>
                    <tbody>'.collect($proposal->expenditures)->map(function($exp) {
                        return '<tr><td>'.($exp->item ?? 'N/A').'</td><td>'.($exp->itemtype ?? 'N/A').'</td><td>'.($exp->quantity ?? 'N/A').'</td><td>'.number_format($exp->unitprice ?? 0, 2).'</td><td>'.number_format($exp->total ?? 0, 2).'</td></tr>';
                    })->join('').
                    '</tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background-color: #f0f0f0;">
                            <td colspan="4">TOTAL BUDGET</td>
                            <td>KES '.number_format($totalBudget, 2).'</td>
                        </tr>
                    </tfoot>
                </table>
            </div>' : '').
            
            ($proposal->workplans->count() > 0 ? '
            <div class="section">
                <div class="section-title">WORK PLAN</div>
                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Timeline</th>
                            <th>Input Required</th>
                            <th>Responsible Person</th>
                        </tr>
                    </thead>
                    <tbody>'.collect($proposal->workplans)->map(function($wp) {
                        return '<tr><td>'.($wp->activity ?? 'N/A').'</td><td>'.($wp->time ?? 'N/A').'</td><td>'.($wp->input ?? 'N/A').'</td><td>'.($wp->bywhom ?? 'N/A').'</td></tr>';
                    })->join('').
                    '</tbody>
                </table>
            </div>' : '').
            
            ($proposal->collaborators->count() > 0 ? '
            <div class="section">
                <div class="section-title">COLLABORATORS</div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Institution</th>
                        </tr>
                    </thead>
                    <tbody>'.collect($proposal->collaborators)->map(function($collab) {
                        return '<tr><td>'.($collab->collaboratorname ?? 'N/A').'</td><td>'.($collab->position ?? 'N/A').'</td><td>'.($collab->institution ?? 'N/A').'</td></tr>';
                    })->join('').
                    '</tbody>
                </table>
            </div>' : '').
            
            ($proposal->publications->count() > 0 ? '
            <div class="section">
                <div class="section-title">RELATED PUBLICATIONS</div>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Publisher</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>'.collect($proposal->publications)->map(function($pub) {
                        return '<tr><td>'.($pub->title ?? 'N/A').'</td><td>'.($pub->publisher ?? 'N/A').'</td><td>'.($pub->year ?? 'N/A').'</td></tr>';
                    })->join('').
                    '</tbody>
                </table>
            </div>' : '').
            
            ($proposal->researchdesigns->count() > 0 ? '
            <div class="section">
                <div class="section-title">RESEARCH DESIGN</div>'.collect($proposal->researchdesigns)->map(function($design) {
                    return '<div class="content"><strong>Goal:</strong> '.($design->goal ?? 'N/A').'<br><strong>Summary:</strong> '.nl2br($design->summary ?? 'N/A').'<br><strong>Indicators:</strong> '.nl2br($design->indicators ?? 'N/A').'</div>';
                })->join('').
            '</div>' : '').
            
            '<div class="footer">
                <p>Generated on '.now()->format('F j, Y \a\t g:i A').'</p>
                <p>University of Kabianga - Annual Research Grants Portal</p>
            </div>
        </body>
        </html>';
    }
}
