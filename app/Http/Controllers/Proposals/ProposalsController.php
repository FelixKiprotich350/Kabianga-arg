<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailingController;
use App\Models\Collaborator;
use App\Models\Department;
use App\Models\Expenditureitem;
use App\Models\FinancialYear;
use App\Models\GlobalSetting;
use App\Models\Grant;
use App\Models\Permission;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\ResearchDesignItem;
use App\Models\ResearchProject;
use App\Models\ResearchTheme;
use App\Models\Workplan;
use App\Models\User;
use App\Models\ProposalChanges;
use App\Notifications\ProposalSubmitted;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteUri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Notification;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\{SubmittedStatus, ReceivedStatus, ApprovalStatus};



class ProposalsController extends Controller
{
    public function index()
    {
        return view('pages.proposals.index');
    }

    //
    public function modernNewProposal()
    {
        if (!auth()->user()->haspermission('canmakenewproposal')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Make a new Proposal!");
        }
        $themes = ResearchTheme::all();
        $departments = Department::all();
        $user = auth()->user();
        $currentgrant = GlobalSetting::where('item', 'current_open_grant')->first();
        $grants = collect();
        if ($currentgrant) {
            $grants = Grant::where('grantid', $currentgrant->value1)
                ->whereDoesntHave('proposals', function ($query) use ($user) {
                    $query->where('useridfk', $user->userid);
                })->get();
        }

        return view('pages.proposals.create', compact('grants', 'themes', 'departments'));
    }

    public function postnewproposal(Request $request)
    {
        if (!auth()->user()->haspermission('canmakenewproposal')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Make a new Proposal!");
        }
        // Define validation rules
        $rules = [
            'grantnofk' => 'required|integer',
            'departmentfk' => 'required|string',
            'themefk' => 'required|string',
            'highestqualification' => 'required|string',
            'officephone' => 'required|string',
            'cellphone' => 'required|string',
            'faxnumber' => 'required|string',
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
                'message' => 'Proposal exists for grant number'
            ], 409);
        }
        $grant = Grant::findOrFail($request->input('grantnofk'));
        // Generate proposal code 
        $currentYear = date('Y');
        $currentMonth = date('Y');
        $lastRecord = Proposal::orderBy('proposalid', 'desc')->first();
        $incrementNumber = $lastRecord ? $lastRecord->proposalid + 1 : 1;
        $generatedCode = 'UOK/ARG/P/' . $currentYear . '/' . $currentMonth . '/' . $incrementNumber;

        // Create a new proposal instance
        $proposal = new Proposal();

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
        $proposal->highqualification = $request->input('highestqualification');
        $proposal->officephone = $request->input('officephone');
        $proposal->cellphone = $request->input('cellphone');
        $proposal->faxnumber = $request->input('faxnumber');
        $proposal->themefk = $request->input('themefk');

        // Save the proposal
        $proposal->save();
        // Return JSON response for API
        return response()->json([
            'success' => true,
            'message' => 'Basic Details Saved Successfully! Continue editing your proposal.',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success'
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
        if (!auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to edit this Proposal. Only the owner can Edit!'
            ], 403);
        }
        if (!$proposal->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal cannot be edited at this time.'
            ], 403);
        }
        $rules = [
            'grantnofk' => 'required|integer', // Example rules, adjust as needed
            'departmentfk' => 'required|string',
            'themefk' => 'required|string',
            'highestqualification' => 'required|string',
            'officephone' => 'required|string',
            'cellphone' => 'required|string',
            'faxnumber' => 'required|string',
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
        $proposal->highqualification = $request->input('highestqualification'); // Example qualification
        $proposal->officephone = $request->input('officephone');
        $proposal->cellphone = $request->input('cellphone');
        $proposal->faxnumber = $request->input('faxnumber');
        // Save the proposal
        $proposal->save();

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Basic Details Saved Successfully!!',
            'proposal_id' => $proposal->proposalid,
            'type' => 'success'
        ], 200);
    }

    public function updateresearchdetails(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if (!auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to edit this Proposal. Only the owner can Edit!'
            ], 403);
        }
        if (!$proposal->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal cannot be edited at this time.'
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
            'type' => 'success'
        ], 200);
    }

    public function submitproposal(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if (!auth()->user()->userid == $proposal->useridfk) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Submit this Proposal. Only the owner can Submit!'
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
            //notifiable users to be informed of new proposal
            $mailingController = new MailingController();
            $url = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
            $mailingController->notifyUsersOfProposalActivity('proposalsubmitted', 'New Proposal', 'success', ['You have a New Proposal Pending Receival and processing.'], 'View Proposal', $url);

            return response(['message' => 'Application Submitted Successfully!!', 'type' => 'success']);
        } else {
            return response(['message' => 'Application not ready for Submission. Has incomplete Details!', 'type' => 'warning']);
        }

    }
    public function receiveproposal(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canreceiveproposal')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to receive this Proposal!'
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
        $mailingController = new MailingController();
        $Url = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
        $mailingController->notifyUsersOfProposalActivity('proposalreceived', 'Proposal Received!', 'success', ['Your Proposal has been Received Successfully.'], 'View Proposal', $Url);
        return response(['message' => 'Proposal received Successfully!!', 'type' => 'success']);


    }
    public function changeeditstatus(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canenabledisableproposaledit')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Enable or Disable editing of this Proposal!'
            ], 403);
        }

        $proposal = Proposal::findOrFail($id);

        $proposal->allowediting = false;
        $proposal->save();
        $mailingController = new MailingController();
        $mailingController->notifyUserReceivedProposal($proposal);
        return response(['message' => 'Proposal received Successfully!!', 'type' => 'success']);


    }
    public function approverejectproposal(Request $request, $id)
    {
        if ($request->input('status') == ApprovalStatus::APPROVED->value && auth()->user()->haspermission('canapproveproposal')) {
        } else if ($request->input('status') == ApprovalStatus::REJECTED->value && auth()->user()->haspermission('canrejectproposal')) {
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not Authorized to Approve/Reject this Proposal!'
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
            return response()->json(['message' => "Please provide a comment,Funding Year & status!", 'type' => "warning"], 400);
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
                $generatedCode = 'UOK/ARG/' . $currentyear->finyear . '/' . $incrementNumber;
                // new project
                $project = new ResearchProject();
                $project->researchnumber = $generatedCode;
                $project->proposalidfk = $proposal->proposalid;
                $project->projectstatus = 'Active';
                $project->ispaused = false;
                $project->fundingfinyearfk = $request->input('fundingfinyearfk');
                $project->saveOrFail();
            }

        });
        if ($request->input('status') == ApprovalStatus::APPROVED->value) {
            $project = ResearchProject::where('proposalidfk', $id)->firstOrFail();
            $mailingController = new MailingController();
            $url = route('pages.projects.viewanyproject', ['id' => $project->researchid]);
            $mailingController->notifyUsersOfProposalActivity('proposalapproved', 'Proposal Approved!', 'success', ['This Proposal has been Approved Successfully.', 'The project will kick off on the indicated Start Date.'], 'View Project', $url);
            return response(['message' => 'Proposal Approved Successfully! Project Started!', 'type' => 'success']);
        } else if ($request->input('status') == ApprovalStatus::REJECTED->value) {
            $mailingController = new MailingController();
            $url = route('pages.proposals.viewproposal', ['id' => $id]);
            $mailingController->notifyUsersOfProposalActivity('proposalrejected', 'Proposal Rejected', 'success', ['The project didnt qualify for further steps.'], 'View Proposal', $url);
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
        if (!auth()->user()->haspermission('canviewallapplications')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }
        return view('pages.proposals.index');
    }

    public function getsingleproposalpage($id)
    {
        try {
            $user = Auth::user();
            // Find the proposal by ID or fail with a 404 error
            $prop = Proposal::with(['applicant', 'department', 'themeitem', 'grantitem'])->findOrFail($id);

            if (!$user->haspermission('canreadproposaldetails') && $user->userid != $prop->useridfk) {
                return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to read the requested Proposal!");
            }
            
            $finyears = FinancialYear::all();
            return view('pages.proposals.show', compact('prop', 'finyears'));
        } catch (\Exception $e) {
            \Log::error('Error loading proposal details: ' . $e->getMessage(), ['proposal_id' => $id]);
            return redirect()->route('pages.proposals.index')->with('error', 'Failed to load proposal details: ' . $e->getMessage());
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
                'publications:publicationid,proposalidfk,title,publisher,year'
            ])->findOrFail($id);
            
            $html = view('pages.proposals.printproposal', compact('proposal'))->render();
            $filename = 'Research-Proposal-' . str_replace(['/', ' ', '\\'], ['-', '-', '-'], $proposal->proposalcode) . '.pdf';
            
            $pdf = SnappyPdf::loadHTML($html);
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'proposal_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to generate PDF. Please try again later.',
                'message' => config('app.debug') ? $e->getMessage() : 'PDF generation failed'
            ], 500);
        }
    }
    
    public function testSnappy()
    {
        try {
            $html = '<html><body><h1>Test PDF Generation with Snappy</h1><p>This is a test document to verify that laravel-snappy is working correctly.</p><p>Generated at: ' . now() . '</p></body></html>';
            
            $pdf = SnappyPdf::loadHTML($html);
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="test-snappy.pdf"'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Snappy test failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function geteditsingleproposalpage(Request $req, $id)
    {
        $prop = Proposal::findOrFail($id);
        if (!auth()->user()->userid == $prop->useridfk) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to Edit the requested Proposal!");
        }
        if (!$prop->isEditable()) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "The Proposal cannot be edited. It may have been approved, rejected, or is not in an editable state.");
        }
        $grants = Grant::all();
        $departments = Department::all();
        $themes = ResearchTheme::all();
        $hasmessage = ($req->input('has_message', 0) == 1) ? true : false;
        // Return the view with the proposal data
        return view('pages.proposals.proposalform', compact('prop', 'departments', 'grants', 'themes', 'hasmessage'));

    }

    public function fetchmyapplications()
    {
        // if (!auth()->user()->haspermission('canviewmyapplications')) {
        //     return response()->json(['data' => []]);
        // }
        $user = auth()->user();
        $myapplications = Proposal::where('useridfk', $user->userid)->with('department', 'grantitem', 'themeitem', 'applicant')->get();
        $proposals = $myapplications->map(function ($proposal) {
            return [
                'proposalid' => $proposal->proposalid,
                'title' => $proposal->researchtitle,
                'abstract' => $proposal->objectives,
                'approvalstatus' => $proposal->approvalstatus,
                'theme_name' => $proposal->themeitem->themename ?? 'N/A',
                'grant_name' => $proposal->grantitem->grantname ?? 'N/A',
                'requested_amount' => $proposal->grantitem->amount ?? 0,
                'created_at' => $proposal->created_at,
                'themefk' => $proposal->themefk
            ];
        });
        return response()->json(data: ['data' => $proposals, 'success' => true]);
    }

    public function fetchallproposals()
    {
        if (!auth()->user()->haspermission('canviewallapplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized', 'data' => []], 403);
        }

        try {
            $proposals = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')->get();
            $data = $proposals->map(function ($proposal) {
                return [
                    'proposalid' => $proposal->proposalid,
                    'researchtitle' => $proposal->researchtitle,
                    'objectives' => $proposal->objectives,
                    'approvalstatus' => $proposal->approvalstatus,
                    'theme_name' => $proposal->themeitem->themename ?? 'N/A',
                    'grant_name' => $proposal->grantitem->grantname ?? 'N/A',
                    'requested_amount' => $proposal->grantitem->amount ?? 0,
                    'created_at' => $proposal->created_at,
                    'applicant_name' => $proposal->applicant->name ?? 'N/A',
                    'department_name' => $proposal->department->departmentname ?? 'N/A'
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
            $proposal = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')->findOrFail($id);
            $data = [
                'proposalid' => $proposal->proposalid,
                'researchtitle' => $proposal->researchtitle,
                'objectives' => $proposal->objectives,
                'approvalstatus' => $proposal->approvalstatus,
                'submittedstatus' => $proposal->submittedstatus,
                'receivedstatus' => $proposal->receivedstatus,
                'allowediting' => $proposal->allowediting,
                'theme_name' => $proposal->themeitem->themename ?? 'N/A',
                'grant_name' => $proposal->grantitem->grantname ?? 'N/A',
                'created_at' => $proposal->created_at,
                'applicant_name' => $proposal->applicant->name ?? 'N/A',
                'department_name' => $proposal->department->departmentname ?? 'N/A'
            ];
            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchsearchproposals(Request $request)
    {
        if (!auth()->user()->haspermission('canviewallapplications')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }
        $searchTerm = $request->input('search');
        $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')
            ->where('approvalstatus', 'like', '%' . $searchTerm . '%')
            ->orWhere('highqualification', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('themeitem', function ($query) use ($searchTerm) {
                $query->where('themename', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('applicant', function ($query1) use ($searchTerm) {
                $query1->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('department', function ($query) use ($searchTerm) {
                $query->where('shortname', 'like', '%' . $searchTerm . '%');
            })
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }

    public function fetchcollaborators($id)
    {
        $data = Collaborator::where('proposalidfk', $id)
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }

    public function fetchpublications($id)
    {
        $data = Publication::where('proposalidfk', $id)
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }

    public function fetchexpenditures($id)
    {
        $data = Expenditureitem::where('proposalidfk', $id)
            ->get();
        $summary = [];
        $totalOthers = $data->where('itemtype', 'Others')
            ->sum('total');

        $totalTravels = $data->where('itemtype', 'Travels')
            ->sum('total');

        $totalConsumables = $data->where('itemtype', 'Consumables')
            ->sum('total');

        $totalFacilities = $data->where('itemtype', 'Facilities')
            ->sum('total');
        $summary['totalOthers'] = $totalOthers;
        $summary['totalTravels'] = $totalTravels;
        $summary['totalConsumables'] = $totalConsumables;
        $summary['totalFacilities'] = $totalFacilities;
        $summary['totalExpenditures'] = $totalFacilities + $totalConsumables + $totalTravels + $totalOthers;
        $rule_40 = $totalOthers + $totalTravels;
        $rule_60 = $totalFacilities + $totalConsumables;
        $summary['isValidBudget'] = $this->getIsValidBudget($rule_40, $rule_60);

        return response(compact(['data', 'summary'])); // Return filtered data as JSON
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
        $data = Workplan::where('proposalidfk', $id)
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }
    public function fetchresearchdesign($id)
    {
        $data = ResearchDesignItem::where('proposalidfk', $id)
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }
    public function querysubmissionstatus($id)
    {
        //0 -not required
        //1 -not completed
        //2 -completed
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
        ;
        $publications = (Publication::where('proposalidfk', $id)->count() > 0) ? 2 : 1;
        ;
        $appstatus = array('basic' => $basic, 'researchinfo' => $researchinfo, 'design' => $design, 'workplan' => $workplan, 'collaborators' => $collaborators, 'publications' => $publications, 'expenditure' => $finanncials);
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
            'message' => $isCompliant ? '60% rule met' : 'Research items < 60%'
        ]);
    }

    public function approveProposal(Request $request, $id)
    {
        try {
            if (!auth()->user()->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string',
                'fundingfinyearfk' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Please select a valid funding year'], 400);
            }

            $fundingYear = $request->input('fundingfinyearfk');
            if (!$fundingYear || $fundingYear === 'undefined' || $fundingYear === '') {
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
                if (!$yearid) {
                    throw new Exception('Current financial year not set');
                }
                
                $currentyear = FinancialYear::findOrFail($yearid->value1);
                $lastRecord = ResearchProject::orderBy('researchid', 'desc')->first();
                $incrementNumber = $lastRecord ? $lastRecord->researchid + 1 : 1;
                $generatedCode = 'UOK/ARG/' . $currentyear->finyear . '/' . $incrementNumber;

                $project = new ResearchProject();
                $project->researchnumber = $generatedCode;
                $project->proposalidfk = $proposal->proposalid;
                $project->projectstatus = 'Active';
                $project->ispaused = false;
                $project->fundingfinyearfk = $request->input('fundingfinyearfk');
                $project->save();
            });

            return response()->json(['success' => true, 'message' => 'Proposal approved successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function rejectProposal(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canrejectproposal')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string'
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

        $mailingController = new MailingController();
        $url = route('pages.proposals.viewproposal', ['id' => $id]);
        $mailingController->notifyUsersOfProposalActivity('proposalrejected', 'Proposal Rejected', 'danger', ['The project didnt qualify for further steps.'], 'View Proposal', $url);

        return response()->json(['success' => true, 'message' => 'Proposal rejected']);
    }

    public function markAsDraft(Request $request, $id)
    {
        try {
            if (!auth()->user()->haspermission('canapproveproposal')) {
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
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function requestChanges(Request $request, $id)
    {
        try {
            if (!auth()->user()->haspermission('canapproveproposal')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'triggerissue' => 'required|string',
                'suggestedchange' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Both issue and suggested changes are required'], 400);
            }

            $proposal = Proposal::findOrFail($id);
            
            if ($proposal->approvalstatus !== ApprovalStatus::PENDING) {
                return response()->json(['success' => false, 'message' => 'Proposal already processed'], 400);
            }

            $change = new ProposalChanges();
            $change->proposalidfk = $id;
            $change->suggestedbyfk = auth()->user()->userid;
            $change->triggerissue = $request->input('triggerissue');
            $change->suggestedchange = $request->input('suggestedchange');
            $change->status = 'Pending';
            $change->save();

            $proposal->allowediting = true;
            $proposal->save();

            return response()->json(['success' => true, 'message' => 'Change request sent']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}

