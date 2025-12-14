<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalRequest;
use App\Models\ApprovalStatus;
use App\Models\Collaborator;
use App\Models\Expenditureitem;
use App\Models\FinancialYear;
use App\Models\GlobalSetting;
use App\Models\Grant;
use App\Models\InnovationTeam;
use App\Models\Proposal;
use App\Models\ProposalInnovationMeta;
use App\Models\ProposalResearchMeta;
use App\Models\ProposalReview;
use App\Models\ProposalType;
use App\Models\Publication;
use App\Models\ReceivedStatus;
use App\Models\ResearchDesignItem;
use App\Models\ResearchProject;
use App\Models\SubmittedStatus;
use App\Models\User;
use App\Models\Workplan;
use App\Services\DualNotificationService;
use App\Traits\ApiResponse;
use App\Traits\NotifiesUsers;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function postnewproposal(StoreProposalRequest $request)
    {

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
        $proposal->departmentidfk = $request->input('departmentidfk');
        $proposal->useridfk = Auth::user()->userid;
        $proposal->approvalstatus = ApprovalStatus::PENDING;
        $proposal->submittedstatus = SubmittedStatus::PENDING;
        $proposal->receivedstatus = ReceivedStatus::PENDING;
        $proposal->allowediting = true;
        $proposal->themefk = $request->input('themefk');
        $proposal->proposaltitle = $request->input('proposaltitle');
        $proposal->proposaltype = ProposalType::from($request->input('proposaltype'));

        // Save the proposal
        $proposal->save();

        // Create meta data based on proposal type
        if ($request->input('proposaltype') === 'innovation') {
            if ($request->filled(['gap', 'solution', 'targetcustomers', 'valueproposition', 'competitors', 'attraction'])) {
                ProposalInnovationMeta::create([
                    'proposal_id' => $proposal->proposalid,
                    'gap' => $request->input('gap'),
                    'solution' => $request->input('solution'),
                    'targetcustomers' => $request->input('targetcustomers'),
                    'valueproposition' => $request->input('valueproposition'),
                    'competitors' => $request->input('competitors'),
                    'attraction' => $request->input('attraction'),
                ]);
            }

            if ($request->has('innovation_teams')) {
                foreach ($request->innovation_teams as $teamMember) {
                    InnovationTeam::create([
                        'proposal_id' => $proposal->proposalid,
                        'name' => $teamMember['name'],
                        'contacts' => $teamMember['contacts'],
                        'role' => $teamMember['role'],
                    ]);
                }
            }
        } else {
            if ($request->filled(['objectives', 'hypothesis', 'significance', 'ethicals', 'expoutput', 'socio_impact', 'res_findings'])) {
                ProposalResearchMeta::create([
                    'proposal_id' => $proposal->proposalid,
                    'objectives' => $request->input('objectives'),
                    'hypothesis' => $request->input('hypothesis'),
                    'significance' => $request->input('significance'),
                    'ethicals' => $request->input('ethicals'),
                    'expoutput' => $request->input('expoutput'),
                    'socio_impact' => $request->input('socio_impact'),
                    'res_findings' => $request->input('res_findings'),
                ]);
            }
        }

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
            'proposaltype' => 'sometimes|in:research,innovation',
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
        if ($request->has('proposaltype')) {
            $proposal->proposaltype = ProposalType::from($request->input('proposaltype'));
        }
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

        if ($proposal->proposaltype->value !== 'research') {
            return response()->json(['success' => false, 'message' => 'This endpoint is only for research proposals'], 400);
        }

        $validator = Validator::make($request->all(), [
            'proposaltitle' => 'required|string',
            'terminationdate' => 'required|date',
            'commencingdate' => 'required|date',
            'objectives' => 'required|string',
            'hypothesis' => 'required|string',
            'significance' => 'required|string',
            'ethicals' => 'required|string',
            'expoutput' => 'required|string',
            'socio_impact' => 'required|string',
            'res_findings' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update basic proposal details
        $proposal->proposaltitle = $request->input('proposaltitle');
        $proposal->commencingdate = $request->input('commencingdate');
        $proposal->terminationdate = $request->input('terminationdate');
        $proposal->save();

        // Update research meta
        $proposal->researchMeta()->updateOrCreate(
            ['proposal_id' => $proposal->proposalid],
            [
                'objectives' => $request->input('objectives'),
                'hypothesis' => $request->input('hypothesis'),
                'significance' => $request->input('significance'),
                'ethicals' => $request->input('ethicals'),
                'expoutput' => $request->input('expoutput'),
                'socio_impact' => $request->input('socio_impact'),
                'res_findings' => $request->input('res_findings'),
            ]
        );

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
        if (auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot receive your own proposal!',
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
        if (auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change edit status of your own proposal!',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:enable,disable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Action must be enable or disable'], 400);
        }

        $proposal->allowediting = $request->input('action') === 'enable';
        $proposal->save();
        
        $action = $request->input('action') === 'enable' ? 'enabled' : 'disabled';
        return response()->json(['success' => true, 'message' => "Proposal editing {$action} successfully"]);
    }

    public function approveProposal(Request $request, $id)
    {
        if (! auth()->user()->haspermission('canapproveproposal')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $proposal = Proposal::findOrFail($id);
        if (auth()->user()->userid == $proposal->useridfk) {
            return response()->json(['success' => false, 'message' => 'You cannot approve your own proposal'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string',
            'fundingfinyearfk' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Please select a valid funding year'], 400);
        }

        $proposal = Proposal::findOrFail($id);
        if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
            return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
        }

        try {
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

        $proposal = Proposal::findOrFail($id);
        if (auth()->user()->userid == $proposal->useridfk) {
            return response()->json(['success' => false, 'message' => 'You cannot reject your own proposal'], 403);
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

        $this->notifyProposalRejected($proposal);

        return response()->json(['success' => true, 'message' => 'Proposal rejected']);
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

    public function printpdf($id)
    {
        try {
            $proposal = Proposal::with([
                'applicant:userid,name,email,phonenumber,gender',
                'department:depid,shortname,description',
                'themeitem:themeid,themename',
                'grantitem:grantid,title,status',
                'expenditures:expenditureid,proposalidfk,item,itemtypeid,quantity,unitprice,total',
                'researchdesigns:designid,proposalidfk,summary,indicators,goal',
                'workplans:workplanid,proposalidfk,activity,time,input,bywhom',
                'collaborators:collaboratorid,proposalidfk,collaboratorname,position,institution',
                'publications:publicationid,proposalidfk,title,publisher,year',
                'researchMeta'
            ])->findOrFail($id);
            
            if ($proposal->proposaltype->value !== 'research') {
                return response()->json(['success' => false, 'message' => 'PDF download is only available for research proposals'], 403);
            }

            $html = $this->generateProposalHtml($proposal);
            $filename = 'Research-Proposal-'.str_replace(['/', ' ', '\\'], ['-', '-', '-'], $proposal->proposalcode).'.pdf';

            $pdf = Pdf::loadHTML($html);

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

            $pdf = Pdf::loadHTML($html);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="test-dompdf.pdf"',
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
        $scope = $request->query('scope', 'my');
        $search = $request->input('q');
        $user = auth()->user();

        $query = Proposal::with('department', 'grantitem.financialyear', 'themeitem', 'applicant');

        if ($scope === 'my') {
            $query->where('useridfk', $user->userid);
        } else {
            if (! $user->haspermission('canviewallproposals')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('approvalstatus', 'like', '%'.$search.'%')
                    ->orWhere('proposaltitle', 'like', '%'.$search.'%')
                    ->orWhereHas('themeitem', function ($query) use ($search) {
                        $query->where('themename', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('applicant', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        try {
            $proposals = $query->get();
            $data = $proposals->map(function ($proposal) {
                return [
                    'proposalid' => $proposal->proposalid,
                    'proposaltitle' => $proposal->proposaltitle,
                    'objectives' => $proposal->objectives,
                    'approvalstatus' => $proposal->approvalstatus,
                    'proposaltype' => $proposal->proposaltype,
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
                'expenditures.expenditureType',
                'researchdesigns',
                'workplans',
                'collaborators',
                'publications',
                'innovationTeams',
                'researchMeta',
                'innovationMeta',
                'reviewers'
            ])->findOrFail($id);

            $proposalData = $proposal->toArray();
            $proposalData['reviewer_ids'] = $proposal->reviewers->pluck('reviewer_id')->toArray();

            return response()->json(['success' => true, 'data' => $proposalData]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
            $data = Expenditureitem::with('expenditureType')->where('proposalidfk', $id)->get();
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

    public function fetchinnovationteams($id)
    {
        try {
            $data = InnovationTeam::where('proposal_id', $id)->get();

            return $this->successResponse($data, 'Innovation teams retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch innovation teams', $e->getMessage(), 500);
        }
    }

    public function querysubmissionstatus($id)
    {
        // 0 -not required
        // 1 -not completed
        // 2 -completed
        $prop = Proposal::with(['researchMeta', 'innovationMeta'])->findOrFail($id);

        // Check research/innovation info based on proposal type
        $researchinfo = 1;
        if ($prop->proposaltype->value === 'innovation') {
            $meta = $prop->innovationMeta;
            if ($prop->proposaltitle && $prop->commencingdate && $prop->terminationdate &&
                $meta && $meta->gap && $meta->solution && $meta->targetcustomers &&
                $meta->valueproposition && $meta->competitors && $meta->attraction) {
                $researchinfo = 2;
            }
        } else {
            $meta = $prop->researchMeta;
            if ($prop->proposaltitle && $prop->commencingdate && $prop->terminationdate &&
                $meta && $meta->objectives && $meta->hypothesis && $meta->significance &&
                $meta->ethicals && $meta->expoutput && $meta->socio_impact && $meta->res_findings) {
                $researchinfo = 2;
            }
        }

        $basic = ($prop) ? 2 : 1;
        $design = (ResearchDesignItem::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $finanncials = (Expenditureitem::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $workplan = (Workplan::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $collaborators = (Collaborator::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        $publications = (Publication::where('proposalidfk', $id)->count() > 0) ? 2 : 1;

        $appstatus = [
            'basic' => $basic,
            'researchinfo' => $researchinfo,
            'design' => $design,
            'workplan' => $workplan,
            'collaborators' => $collaborators,
            'publications' => $publications,
            'expenditure' => $finanncials,
        ];

        return $appstatus;
    }

    public function fetchsubmissionstatus($id)
    {
        $data = $this->querysubmissionstatus($id);
        $cansubmit = $this->cansubmit($id);

        return response(['data' => $data, 'cansubmitstatus' => $cansubmit]);
    }

    public function fetchproposalreviews($id)
    {
        $data = ProposalReview::where('proposalid', $id)->with('reviewer')->get();

        return response()->json($data);
    }

    public function markReviewAddressed($reviewId)
    {
        $review = ProposalReview::findOrFail($reviewId);
        $proposal = $review->proposal;

        if (auth()->user()->userid != $proposal->useridfk) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $review->status = 'addressed';
        $review->addresstime = now();
        $review->save();

        return response()->json(['success' => true, 'message' => 'Review marked as addressed']);
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

    public function updateinnovationdetails(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);

        if (auth()->user()->userid != $proposal->useridfk) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (! $proposal->isEditable()) {
            return response()->json(['success' => false, 'message' => 'Proposal cannot be edited'], 403);
        }

        if ($proposal->proposaltype->value !== 'innovation') {
            return response()->json(['success' => false, 'message' => 'This endpoint is only for innovation proposals'], 400);
        }

        $validator = Validator::make($request->all(), [
            'proposaltitle' => 'required|string',
            'terminationdate' => 'required|date',
            'commencingdate' => 'required|date',
            'gap' => 'required|string',
            'solution' => 'required|string',
            'targetcustomers' => 'required|string',
            'valueproposition' => 'required|string',
            'competitors' => 'required|string',
            'attraction' => 'required|string',
            'innovation_teams' => 'sometimes|array',
            'innovation_teams.*.name' => 'required_with:innovation_teams|string',
            'innovation_teams.*.contacts' => 'required_with:innovation_teams|string',
            'innovation_teams.*.role' => 'required_with:innovation_teams|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        // Update basic proposal details
        $proposal->proposaltitle = $request->input('proposaltitle');
        $proposal->commencingdate = $request->input('commencingdate');
        $proposal->terminationdate = $request->input('terminationdate');
        $proposal->save();

        // Update innovation meta
        $proposal->innovationMeta()->updateOrCreate(
            ['proposal_id' => $proposal->proposalid],
            $request->only(['gap', 'solution', 'targetcustomers', 'valueproposition', 'competitors', 'attraction'])
        );

        // Update innovation teams
        if ($request->has('innovation_teams')) {
            InnovationTeam::where('proposal_id', $proposal->proposalid)->delete();
            foreach ($request->innovation_teams as $teamMember) {
                InnovationTeam::create([
                    'proposal_id' => $proposal->proposalid,
                    'name' => $teamMember['name'],
                    'contacts' => $teamMember['contacts'],
                    'role' => $teamMember['role'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Innovation Details Saved Successfully!!',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success',
        ], 200);
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

    public function submitReview(Request $request, $id)
    {
        try {
            $proposal = Proposal::findOrFail($id);
            $user = auth()->user();

            if (! $proposal->canRequestChanges($user->userid) && ! $user->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized. Only assigned reviewers can submit reviews.'], 403);
            }

            $validator = Validator::make($request->all(), [
                'subject' => 'required|string',
                'reviewcomment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Subject and review comment are required'], 400);
            }

            if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
                return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
            }

            $review = new ProposalReview;
            $review->proposalid = $id;
            $review->reviewerid = auth()->user()->userid;
            $review->subject = $request->input('subject');
            $review->reviewcomment = $request->input('reviewcomment');
            $review->status = 'pending';
            $review->save();

            // Enable editing for the proposal owner to address review
            if (! $proposal->allowediting) {
                $proposal->allowediting = true;
                $proposal->save();
            }

            // Send dual notifications
            $this->notifyChangesRequested($proposal);

            return response()->json(['success' => true, 'message' => 'Review submitted successfully']);
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
                <div class="field"><span class="label">Proposal Title:</span> <span class="value">'.($proposal->proposaltitle ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Proposal Type:</span> <span class="value">'.ucfirst($proposal->proposaltype->value ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Principal Investigator:</span> <span class="value">'.($proposal->applicant->name ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Department:</span> <span class="value">'.($proposal->department->shortname ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Research Theme:</span> <span class="value">'.($proposal->themeitem->themename ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Grant:</span> <span class="value">'.($proposal->grantitem->title ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Duration:</span> <span class="value">'.($proposal->commencingdate ?? 'N/A').' to '.($proposal->terminationdate ?? 'N/A').'</span></div>
                <div class="field"><span class="label">Status:</span> <span class="status-badge status-'.strtolower($proposal->approvalstatus->value ?? 'pending').'">'.($proposal->approvalstatus->value ?? 'N/A').'</span></div>
            </div>
            
            <div class="section">
                <div class="section-title">RESEARCH OBJECTIVES</div>
                <div class="content">'.nl2br($proposal->researchMeta->objectives ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">HYPOTHESIS</div>
                <div class="content">'.nl2br($proposal->researchMeta->hypothesis ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">SIGNIFICANCE OF THE STUDY</div>
                <div class="content">'.nl2br($proposal->researchMeta->significance ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">ETHICAL CONSIDERATIONS</div>
                <div class="content">'.nl2br($proposal->researchMeta->ethicals ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">EXPECTED OUTPUTS</div>
                <div class="content">'.nl2br($proposal->researchMeta->expoutput ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">SOCIO-ECONOMIC IMPACT</div>
                <div class="content">'.nl2br($proposal->researchMeta->socio_impact ?? 'Not specified').'</div>
            </div>
            
            <div class="section">
                <div class="section-title">RESEARCH FINDINGS UTILIZATION</div>
                <div class="content">'.nl2br($proposal->researchMeta->res_findings ?? 'Not specified').'</div>
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
                    <tbody>'.collect($proposal->expenditures)->map(function ($exp) {
                return '<tr><td>'.($exp->item ?? 'N/A').'</td><td>'.($exp->expenditureType->typename ?? 'N/A').'</td><td>'.($exp->quantity ?? 'N/A').'</td><td>'.number_format($exp->unitprice ?? 0, 2).'</td><td>'.number_format($exp->total ?? 0, 2).'</td></tr>';
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
                    <tbody>'.collect($proposal->workplans)->map(function ($wp) {
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
                    <tbody>'.collect($proposal->collaborators)->map(function ($collab) {
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
                    <tbody>'.collect($proposal->publications)->map(function ($pub) {
                return '<tr><td>'.($pub->title ?? 'N/A').'</td><td>'.($pub->publisher ?? 'N/A').'</td><td>'.($pub->year ?? 'N/A').'</td></tr>';
            })->join('').
            '</tbody>
                </table>
            </div>' : '').

            ($proposal->researchdesigns->count() > 0 ? '
            <div class="section">
                <div class="section-title">RESEARCH DESIGN</div>'.collect($proposal->researchdesigns)->map(function ($design) {
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
