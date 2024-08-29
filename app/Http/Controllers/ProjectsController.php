<?php

namespace App\Http\Controllers;

use App\Models\ResearchFunding;
use App\Models\ResearchProgress;
use App\Models\ResearchProject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    //
    public function myprojects()
    {
        if (!auth()->user()->hasPermission('canviewmyprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view your Projects!");
        }

        // Return the view with the necessary data
        return view('pages.projects.myprojects');
    }
    public function fetchmyactiveprojects()
    {
        if (!auth()->user()->hasPermission('canviewmyprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view your Projects!");
        }

        $userid = auth()->user()->userid;

        // Fetch projects where the related proposals' useridfk matches the current user
        $myprojects = ResearchProject::with('proposal')
            ->whereHas('proposal', function ($query) use ($userid) {
                $query->where('useridfk', $userid);
            })
            ->where('projectstatus', 'Active')
            ->with('proposal')
            ->with('applicant')
            ->get();
        // Return the view with the necessary data
        return response()->json($myprojects);
    }
    public function fetchmyallprojects()
    {
        if (!auth()->user()->hasPermission('canviewmyprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view your Projects!");
        }

        $userid = auth()->user()->userid;

        // Fetch projects where the related proposals' useridfk matches the current user
        $myprojects = ResearchProject::with('proposal')
            ->whereHas('proposal', function ($query) use ($userid) {
                $query->where('useridfk', $userid);
            })
            ->with('proposal')
            ->with('applicant')
            ->get();
        // Return the view with the necessary data
        return response()->json($myprojects);
    }
    public function viewmyproject($id)
    {
        if (!auth()->user()->hasPermission('canreadmyproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view this Project!");
        }

        // Fetch projects where the related proposals' useridfk matches the current user
        $project = ResearchProject::with(['proposal.applicant'])->findOrFail($id);
        //  ;
        // Return the view with the necessary data
        return view('pages.projects.viewproject', compact('project'));
    }

    public function allprojects()
    {
        if (!auth()->user()->hasPermission('canviewallprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Projects!");
        }
        // Return the view with the necessary data
        return view('pages.projects.allprojects');
    }

    public function fetchallactiveprojects()
    {
        if (!auth()->user()->hasPermission('canviewallprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Projects!");
        }

        $allprojects = ResearchProject::where('projectstatus', 'Active')
            ->with('proposal')
            ->with('applicant')
            ->get();
        return response()->json($allprojects);
    }

    public function fetchallprojects()
    {
        if (!auth()->user()->hasPermission('canviewallprojects')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Projects!");
        }

        // Fetch projects where the related proposals' useridfk matches the current user
        $allprojects = ResearchProject::with('proposal')
            ->with('applicant')
            ->get();
        return response()->json($allprojects);
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

        // Fetch projects where the related proposals' useridfk matches the current user
        $project = ResearchProject::with(['proposal.applicant'])->findOrFail($id);
        //  ;
        // Return the view with the necessary data
        return view('pages.projects.viewproject', compact('project'));
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

        $item = new ResearchProgress();
        $item->researchidfk = $request->input('researchidfk');
        $item->reportedbyfk = $request->input('reportedbyfk');
        $item->report = $request->input('report');
        $item->save();

        // Optionally, return a response or redirect
        return response(['message' => 'Report Submitted Successfully!!', 'type' => 'success']);


    }
    public function pauseproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canpauseresearchproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to pause this Project!");
        }
        $item = ResearchProject::findOrFail($id);
        $item->ispaused = true;
        $item->save();

        // Optionally, return a response or redirect
        return redirect(route('pages.projects.viewanyproject',['id'=>$id]));


    }
    public function resumeproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('canresumeresearchproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to pause this Project!");
        }
        $item = ResearchProject::findOrFail($id);
        $item->ispaused = false;
        $item->save();

        // Optionally, return a response or redirect
        return redirect(route('pages.projects.viewanyproject',['id'=>$id]));


    }
    public function cancelproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('cancancelresearchproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to pause this Project!");
        }
        $item = ResearchProject::findOrFail($id);
        $item->projectstatus = 'Cancelled';
        $item->save();

        // Optionally, return a response or redirect
        return redirect(route('pages.projects.viewanyproject',['id'=>$id]));
    }
    public function completeproject(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('cancompleteresearchproject')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not authorized to pause this Project!");
        }
        $item = ResearchProject::findOrFail($id);
        $item->projectstatus = 'Completed';
        $item->save();

        // Optionally, return a response or redirect
        return redirect(route('pages.projects.viewanyproject',['id'=>$id]));
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
        $item = new ResearchFunding();
        $item->researchidfk = $id;
        $item->createdby = Auth::user()->userid;
        $item->amount = $request->input('amount');
        $item->save();

        // Optionally, return a response or redirect
        return response(['message' => 'Funding Submitted Successfully!!', 'type' => 'success']);


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
