<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Grant;
use App\Models\Proposal;
use App\Models\ResearchTheme;
use App\Models\ResearchProject;
use App\Models\ResearchFunding;
use App\Models\ResearchProgress;
use App\Models\Publication;
use App\Models\User;
use App\Models\Expenditureitem;
use App\Models\FinancialYear;
use App\Services\PdfGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGenerationService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    //return reports main view
    public function home()
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to View Reports!");
        }
        $allgrants = Grant::all();
        $allthemes = ResearchTheme::all();
        $alldepartments = Department::all();
        $allfinyears = FinancialYear::all();
        return view('pages.reports.index', compact('allgrants', 'allthemes', 'alldepartments', 'allfinyears'));
    }

    public function getallproposals(Request $request)
    {
        if (!auth()->user()->haspermission('canviewallapplications')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }
        $searchTerm = $request->input('search');
        if ($searchTerm != null) {
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
        } else {
            $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')->get();
        }
        return response()->json($data); // Return filtered data as JSON
    }
    public function c(Request $request)
    {
        if (!auth()->user()->haspermission('canviewallapplications')) {
            return redirect()->route('pages.unauthorized')->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }
        $grantfilter = $request->input('filtergrant');
        $themefilter = $request->input('filtertheme');
        $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant');
        //filter grant
        if ($grantfilter != null) {
            $data = $data->orWhereHas('grantitem', function ($query) use ($grantfilter) {
                $query->where('grantid', $grantfilter);
            });
        }
        //filter theme
        if ($themefilter != null) {
            $data = $data->orWhereHas('themeitem', function ($query) use ($themefilter) {
                $query->where('themeid', $themefilter);
            });
        }
        //get all departments
        $departments = Department::all();
        $departmentProposals[] = ['Name', 'Total', 'Male', 'Female'];
        // Loop through each department
        foreach ($departments as $department) {
            // Get the proposals for the current department
            $proposals = $data->where('departmentidfk', $department->depid)->get();
            //proposal status
            $approved = $proposals->where('approvalstatus', 'APPROVED')->count();
            $rejected = $proposals->where('approvalstatus', 'REJECTED')->count();
            $pending = $proposals->where('approvalstatus', 'PENDING')->count();
            // gender
            $malecount = $proposals->filter(function ($proposal) {
                return $proposal->applicant->gender === 'Male';
            })->count();
            $femalecount = $proposals->filter(function ($proposal) {
                return $proposal->applicant->gender === 'Female';
            })->count();

            // Add the department and its proposals to the array
            $departmentProposals[] = [$department->shortname, $proposals->count(), $malecount, $femalecount];
            // 'statuses' => ['APPROVED' => $approved, 'REJECTED' => $rejected, 'PENDING' => $pending]

        }
        return response()->json($departmentProposals); // Return filtered data as JSON
    }
    public function getProposalsBySchool(Request $request)
    {
        if (!auth()->user()->hasPermission('canViewAllApplications')) {
            return redirect()->route('pages.unauthorized')
                ->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }

        $grantFilter = $request->input('filtergrant');
        $themeFilter = $request->input('filtertheme');

        $proposalsQuery = Proposal::with('department', 'grantitem', 'themeitem', 'applicant');

        // Filter by grant
        if ($grantFilter && $grantFilter != 'all') {
            $proposalsQuery->whereHas('grantitem', function ($query) use ($grantFilter) {
                $query->where('grantid', $grantFilter);
            });
        }

        // Filter by theme
        if ($themeFilter && $themeFilter != 'all') {
            $proposalsQuery->whereHas('themeitem', function ($query) use ($themeFilter) {
                $query->where('themeid', $themeFilter);
            });
        }

        $departments = Department::all();
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Total Proposals',
                    'backgroundColor' => 'rgba(17, 126, 73, 1)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Male Applicants',
                    'backgroundColor' => 'rgba(236, 141, 87, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Female Applicants',
                    'backgroundColor' => 'rgba(236, 87, 182, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Approved Proposals',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Rejected Proposals',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Pending Proposals',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ]
            ]
        ];

        foreach ($departments as $department) {
            $filteredProposals = (clone $proposalsQuery)->where('departmentidfk', $department->depid)->get();

            $approvedCount = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $pendingCount = $filteredProposals->where('approvalstatus', 'PENDING')->count();

            $maleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender === 'Male')->count();
            $femaleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender === 'Female')->count();

            $chartData['labels'][] = $department->shortname;

            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $maleCount;
            $chartData['datasets'][2]['data'][] = $femaleCount;
            $chartData['datasets'][3]['data'][] = $approvedCount;
            $chartData['datasets'][4]['data'][] = $rejectedCount;
            $chartData['datasets'][5]['data'][] = $pendingCount;
        }

        return response()->json($chartData);
    }

    public function getProposalsByTheme(Request $request)
    {
        if (!auth()->user()->hasPermission('canViewAllApplications')) {
            return redirect()->route('pages.unauthorized')
                ->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }

        $grantFilter = $request->input('filtergrant');
        $departmentFilter = $request->input('filterdepartment');

        $proposalsQuery = Proposal::with('department', 'grantitem', 'themeitem', 'applicant');

        // Filter by grant
        if ($grantFilter && $grantFilter != 'all') {
            $proposalsQuery->whereHas('grantitem', function ($query) use ($grantFilter) {
                $query->where('grantid', $grantFilter);
            });
        }

        // Filter by department
        if ($departmentFilter && $departmentFilter != 'all') {
            $proposalsQuery->whereHas('department', function ($query) use ($departmentFilter) {
                $query->where('depid', $departmentFilter);
            });
        }

        $themes = ResearchTheme::all();
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Total Proposals',
                    'backgroundColor' => 'rgba(17, 126, 73, 1)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Male Applicants',
                    'backgroundColor' => 'rgba(236, 141, 87, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Female Applicants',
                    'backgroundColor' => 'rgba(236, 87, 182, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Approved Proposals',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Rejected Proposals',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Pending Proposals',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ]
            ]
        ];

        foreach ($themes as $theme) {
            $filteredProposals = (clone $proposalsQuery)->where('themefk', $theme->themeid)->get();

            $approvedCount = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $pendingCount = $filteredProposals->where('approvalstatus', 'PENDING')->count();

            $maleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender == 'Male')->count();
            $femaleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender == 'Female')->count();

            $chartData['labels'][] = $theme->themename;

            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $maleCount;
            $chartData['datasets'][2]['data'][] = $femaleCount;
            $chartData['datasets'][3]['data'][] = $approvedCount;
            $chartData['datasets'][4]['data'][] = $rejectedCount;
            $chartData['datasets'][5]['data'][] = $pendingCount;
        }

        return response()->json($chartData);
    }

    public function getProposalsByGrant(Request $request)
    {
        if (!auth()->user()->hasPermission('canViewAllApplications')) {
            return redirect()->route('pages.unauthorized')
                ->with('unauthorizationmessage', "You are not Authorized to view all Proposals!");
        }

        $themeFilter = $request->input('filtertheme');
        $departmentFilter = $request->input('filterdepartment');

        $proposalsQuery = Proposal::with('department', 'grantitem', 'themeitem', 'applicant');

        // Filter by grant
        if ($themeFilter && $themeFilter != 'all') {
            $proposalsQuery->whereHas('themeitem', function ($query) use ($themeFilter) {
                $query->where('themeid', $themeFilter);
            });
        }

        // Filter by department
        if ($departmentFilter && $departmentFilter != 'all') {
            $proposalsQuery->whereHas('department', function ($query) use ($departmentFilter) {
                $query->where('depid', $departmentFilter);
            });
        }

        $grants = Grant::all();
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Total Proposals',
                    'backgroundColor' => 'rgba(17, 126, 73, 1)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Male Applicants',
                    'backgroundColor' => 'rgba(236, 141, 87, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Female Applicants',
                    'backgroundColor' => 'rgba(236, 87, 182, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Approved Proposals',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Rejected Proposals',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ],
                [
                    'label' => 'Pending Proposals',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => []
                ]
            ]
        ];

        foreach ($grants as $grant) {
            $filteredProposals = (clone $proposalsQuery)->where('grantnofk', $grant->grantid)->get();

            $approvedCount = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $pendingCount = $filteredProposals->where('approvalstatus', 'PENDING')->count();

            $maleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender == 'Male')->count();
            $femaleCount = $filteredProposals->filter(fn($proposal) => $proposal->applicant->gender == 'Female')->count();

            $chartData['labels'][] = $grant->grantid . '(' . $grant->finyear . ')';

            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $maleCount;
            $chartData['datasets'][2]['data'][] = $femaleCount;
            $chartData['datasets'][3]['data'][] = $approvedCount;
            $chartData['datasets'][4]['data'][] = $rejectedCount;
            $chartData['datasets'][5]['data'][] = $pendingCount;
        }

        return response()->json($chartData);
    }

    // Financial Reports
    public function getFinancialSummary(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $grantFilter = $request->input('grant');
            $yearFilter = $request->input('year');

            $query = ResearchFunding::with(['applicant', 'project.proposal.grantitem']);
            
            if ($grantFilter && $grantFilter != 'all') {
                $query->whereHas('project.proposal.grantitem', function($q) use ($grantFilter) {
                    $q->where('grantid', $grantFilter);
                });
            }

            if ($yearFilter && $yearFilter != 'all') {
                $query->whereYear('created_at', $yearFilter);
            }

            $fundings = $query->get();
            $totalFunding = $fundings->sum('amount') ?? 0;
            $avgFunding = $fundings->count() > 0 ? $fundings->avg('amount') : 0;
            $fundingCount = $fundings->count();

            // Budget vs Actual Analysis
            $budgetData = Expenditureitem::select(
                DB::raw('SUM(total) as total_budget'),
                DB::raw('COUNT(*) as proposal_count')
            )->first();

            return response()->json([
                'success' => true,
                'total_funding' => $totalFunding,
                'average_funding' => round($avgFunding, 2),
                'funding_count' => $fundingCount,
                'total_budget' => $budgetData->total_budget ?? 0,
                'budget_utilization' => $budgetData->total_budget > 0 ? round(($totalFunding / $budgetData->total_budget) * 100, 2) : 0,
                'funding_by_month' => $this->getFundingByMonth($yearFilter)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load financial data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getFundingByMonth($year = null)
    {
        try {
            $year = $year ?? date('Y');
            
            $monthlyFunding = ResearchFunding::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $data = array_fill(0, 12, 0);
            
            foreach ($monthlyFunding as $funding) {
                $data[$funding->month - 1] = floatval($funding->total);
            }

            return [
                'labels' => $months,
                'data' => $data
            ];
        } catch (\Exception $e) {
            // Return empty data if there's an error
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => array_fill(0, 12, 0)
            ];
        }
    }

    // Project Reports
    public function getProjectsReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $statusFilter = $request->input('status');
            $grantFilter = $request->input('grant');

            $query = ResearchProject::with(['proposal.applicant', 'proposal.grantitem', 'proposal.themeitem']);
            
            if ($statusFilter && $statusFilter != 'all') {
                $query->where('projectstatus', $statusFilter);
            }

            if ($grantFilter && $grantFilter != 'all') {
                $query->whereHas('proposal.grantitem', function($q) use ($grantFilter) {
                    $q->where('grantid', $grantFilter);
                });
            }

            $projects = $query->get();

            $statusCounts = [
                'ACTIVE' => $projects->where('projectstatus', 'ACTIVE')->count(),
                'PAUSED' => $projects->where('projectstatus', 'PAUSED')->count(),
                'COMPLETED' => $projects->where('projectstatus', 'COMPLETED')->count(),
                'CANCELLED' => $projects->where('projectstatus', 'CANCELLED')->count(),
            ];

            $projectsByTheme = $projects->groupBy('proposal.themeitem.themename')
                ->map(function($group) {
                    return $group->count();
                });

            return response()->json([
                'success' => true,
                'total_projects' => $projects->count(),
                'status_breakdown' => $statusCounts,
                'projects_by_theme' => $projectsByTheme,
                'completion_rate' => $projects->count() > 0 ? round(($statusCounts['COMPLETED'] / $projects->count()) * 100, 2) : 0,
                'projects' => $projects->map(function($project) {
                    return [
                        'id' => $project->researchid,
                        'number' => $project->researchnumber,
                        'title' => $project->proposal->researchtitle ?? 'N/A',
                        'applicant' => $project->proposal->applicant->name ?? 'N/A',
                        'status' => $project->projectstatus,
                        'theme' => $project->proposal->themeitem->themename ?? 'N/A',
                        'grant' => $project->proposal->grantitem->grantid ?? 'N/A',
                        'created_at' => $project->created_at->format('Y-m-d')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load projects report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // User Activity Reports
    public function getUserActivityReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $departmentFilter = $request->input('department');
            $roleFilter = $request->input('role');

            $query = User::with(['department', 'userRoles']);
            
            if ($departmentFilter && $departmentFilter != 'all') {
                $query->whereHas('department', function($q) use ($departmentFilter) {
                    $q->where('depid', $departmentFilter);
                });
            }

            if ($roleFilter && $roleFilter != 'all') {
                $query->whereHas('userRoles', function($q) use ($roleFilter) {
                    $q->where('role_type', $roleFilter);
                });
            }

            $users = $query->get();

            $userStats = $users->map(function($user) {
                $proposalCount = Proposal::where('useridfk', $user->userid)->count();
                $approvedProposals = Proposal::where('useridfk', $user->userid)
                    ->where('approvalstatus', 'APPROVED')->count();
                $activeProjects = ResearchProject::whereHas('proposal', function($q) use ($user) {
                    $q->where('useridfk', $user->userid);
                })->where('projectstatus', 'ACTIVE')->count();

                // Get user role - check if admin first, then user roles
                $role = 'User';
                if ($user->isadmin) {
                    $role = 'Admin';
                } elseif ($user->userRoles->isNotEmpty()) {
                    $role = $user->userRoles->first()->role_type ?? 'User';
                }

                return [
                    'id' => $user->userid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role,
                    'department' => $user->department->shortname ?? 'N/A',
                    'proposal_count' => $proposalCount,
                    'approved_proposals' => $approvedProposals,
                    'active_projects' => $activeProjects,
                    'success_rate' => $proposalCount > 0 ? round(($approvedProposals / $proposalCount) * 100, 2) : 0,
                    'last_login' => $user->updated_at->format('Y-m-d H:i:s')
                ];
            });

            // Calculate role distribution
            $roleDistribution = [];
            foreach ($users as $user) {
                if ($user->isadmin) {
                    $roleDistribution['Admin'] = ($roleDistribution['Admin'] ?? 0) + 1;
                } elseif ($user->userRoles->isNotEmpty()) {
                    foreach ($user->userRoles as $userRole) {
                        $roleType = $userRole->role_type;
                        $roleDistribution[$roleType] = ($roleDistribution[$roleType] ?? 0) + 1;
                    }
                } else {
                    $roleDistribution['User'] = ($roleDistribution['User'] ?? 0) + 1;
                }
            }

            return response()->json([
                'success' => true,
                'total_users' => $users->count(),
                'active_users' => $users->where('isactive', true)->count(),
                'role_distribution' => $roleDistribution,
                'users' => $userStats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load user activity report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Publications Report
    public function getPublicationsReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $yearFilter = $request->input('year');
            $themeFilter = $request->input('theme');

            $query = Publication::with(['proposal.themeitem', 'proposal.applicant']);
            
            if ($yearFilter && $yearFilter != 'all') {
                $query->where('year', $yearFilter);
            }

            if ($themeFilter && $themeFilter != 'all') {
                $query->whereHas('proposal.themeitem', function($q) use ($themeFilter) {
                    $q->where('themeid', $themeFilter);
                });
            }

            $publications = $query->get();

            $publicationsByYear = $publications->groupBy('year')
                ->map(function($group) {
                    return $group->count();
                })->sortKeys();

            $publicationsByTheme = $publications->groupBy('proposal.themeitem.themename')
                ->map(function($group) {
                    return $group->count();
                });

            return response()->json([
                'success' => true,
                'total_publications' => $publications->count(),
                'publications_by_year' => $publicationsByYear,
                'publications_by_theme' => $publicationsByTheme,
                'recent_publications' => $publications->sortByDesc('year')->take(10)->map(function($pub) {
                    return [
                        'title' => $pub->title,
                        'authors' => $pub->authors,
                        'year' => $pub->year,
                        'publisher' => $pub->publisher,
                        'theme' => $pub->proposal->themeitem->themename ?? 'N/A',
                        'applicant' => $pub->proposal->applicant->name ?? 'N/A'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load publications report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Export Reports to PDF
    public function exportReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reportType = $request->input('type');
        $filters = $request->except(['type']);

        $data = [];
        $title = '';

        switch ($reportType) {
            case 'proposals':
                $data = $this->getallproposals($request)->getData();
                $title = 'Proposals Report';
                break;
            case 'projects':
                $data = $this->getProjectsReport($request)->getData();
                $title = 'Projects Report';
                break;
            case 'financial':
                $data = $this->getFinancialSummary($request)->getData();
                $title = 'Financial Report';
                break;
            case 'publications':
                $data = $this->getPublicationsReport($request)->getData();
                $title = 'Publications Report';
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        $html = view('pages.reports.pdf-template', compact('data', 'title', 'filters'))->render();
        
        try {
            $pdf = $this->pdfService->generatePdf($html, [
                'page-size' => 'A4',
                'orientation' => 'Portrait',
                'margin-top' => '0.75in',
                'margin-right' => '0.75in',
                'margin-bottom' => '0.75in',
                'margin-left' => '0.75in'
            ]);

            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . strtolower(str_replace(' ', '_', $title)) . '_' . date('Y-m-d') . '.pdf"'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
        }
    }

    // Progress Tracking Report
    public function getProgressReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $statusFilter = $request->input('status');
            $query = ResearchProject::with(['proposal.applicant']);
            
            if ($statusFilter && $statusFilter != 'all') {
                $query->where('projectstatus', $statusFilter);
            }

            $projects = $query->get();
            $progressData = $projects->map(function($project) {
                $progressCount = ResearchProgress::where('researchidfk', $project->researchid)->count();
                $lastProgress = ResearchProgress::where('researchidfk', $project->researchid)
                    ->latest()->first();
                
                return [
                    'project_id' => $project->researchid,
                    'title' => $project->proposal->researchtitle ?? 'N/A',
                    'applicant' => $project->proposal->applicant->name ?? 'N/A',
                    'status' => $project->projectstatus,
                    'progress_reports' => $progressCount,
                    'last_report_date' => $lastProgress ? $lastProgress->created_at->format('Y-m-d') : 'Never',
                    'days_since_report' => $lastProgress ? $lastProgress->created_at->diffInDays(now()) : 'N/A'
                ];
            });

            return response()->json([
                'success' => true,
                'projects' => $progressData,
                'overdue_projects' => $progressData->filter(function($p) {
                    return is_numeric($p['days_since_report']) && $p['days_since_report'] > 90;
                })->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load progress report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Compliance Report
    public function getComplianceReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $proposals = Proposal::with(['applicant'])->get();
            $projects = ResearchProject::with(['proposal.applicant'])->get();
            
            $complianceData = [
                'proposals_missing_docs' => $proposals->filter(function($p) {
                    return empty($p->researchtitle) || empty($p->objectives);
                })->count(),
                'projects_no_progress' => $projects->filter(function($p) {
                    return ResearchProgress::where('researchidfk', $p->researchid)->count() == 0;
                })->count(),
                'overdue_reports' => $projects->filter(function($p) {
                    $lastReport = ResearchProgress::where('researchidfk', $p->researchid)->latest()->first();
                    return !$lastReport || $lastReport->created_at->diffInDays(now()) > 90;
                })->count(),
                'inactive_users' => User::where('isactive', false)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $complianceData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load compliance report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Performance Analytics Report
    public function getPerformanceReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $yearFilter = $request->input('year', date('Y'));
            
            $proposals = Proposal::with(['applicant', 'grantitem'])
                ->whereYear('created_at', $yearFilter)->get();
            $projects = ResearchProject::with(['proposal.applicant'])
                ->whereYear('created_at', $yearFilter)->get();
            
            $performance = [
                'approval_rate' => $proposals->count() > 0 ? 
                    round(($proposals->where('approvalstatus', 'APPROVED')->count() / $proposals->count()) * 100, 2) : 0,
                'completion_rate' => $projects->count() > 0 ? 
                    round(($projects->where('projectstatus', 'COMPLETED')->count() / $projects->count()) * 100, 2) : 0,
                'avg_processing_time' => $this->calculateAvgProcessingTime($proposals),
                'top_performers' => $this->getTopPerformers($yearFilter),
                'grant_efficiency' => $this->getGrantEfficiency($yearFilter)
            ];

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load performance report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Budget vs Actual Report
    public function getBudgetActualReport(Request $request)
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $grantFilter = $request->input('grant');
            $query = Proposal::with(['expenditures', 'grantitem']);
            
            if ($grantFilter && $grantFilter != 'all') {
                $query->where('grantnofk', $grantFilter);
            }

            $proposals = $query->where('approvalstatus', 'APPROVED')->get();
            
            $budgetData = $proposals->map(function($proposal) {
                $budgetAmount = $proposal->expenditures->sum('total') ?? 0;
                $actualFunding = ResearchFunding::whereHas('project.proposal', function($q) use ($proposal) {
                    $q->where('proposalid', $proposal->proposalid);
                })->sum('amount') ?? 0;
                
                return [
                    'proposal_id' => $proposal->proposalid,
                    'title' => $proposal->researchtitle ?? 'N/A',
                    'grant' => $proposal->grantitem->grantid ?? 'N/A',
                    'budget_amount' => $budgetAmount,
                    'actual_funding' => $actualFunding,
                    'variance' => $actualFunding - $budgetAmount,
                    'variance_percentage' => $budgetAmount > 0 ? 
                        round((($actualFunding - $budgetAmount) / $budgetAmount) * 100, 2) : 0
                ];
            });

            return response()->json([
                'success' => true,
                'budget_analysis' => $budgetData,
                'total_budget' => $budgetData->sum('budget_amount'),
                'total_actual' => $budgetData->sum('actual_funding'),
                'overall_variance' => $budgetData->sum('variance')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load budget analysis',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateAvgProcessingTime($proposals)
    {
        $approvedProposals = $proposals->where('approvalstatus', 'APPROVED');
        if ($approvedProposals->count() == 0) return 0;
        
        $totalDays = $approvedProposals->sum(function($p) {
            return $p->created_at->diffInDays($p->updated_at);
        });
        
        return round($totalDays / $approvedProposals->count(), 1);
    }

    private function getTopPerformers($year)
    {
        return User::withCount([
            'proposals as approved_count' => function($q) use ($year) {
                $q->where('approvalstatus', 'APPROVED')->whereYear('created_at', $year);
            }
        ])->orderBy('approved_count', 'desc')->take(5)->get()
        ->map(function($user) {
            return [
                'name' => $user->name,
                'approved_proposals' => $user->approved_count
            ];
        });
    }

    private function getGrantEfficiency($year)
    {
        return Grant::withCount([
            'proposals as total_proposals' => function($q) use ($year) {
                $q->whereYear('created_at', $year);
            },
            'proposals as approved_proposals' => function($q) use ($year) {
                $q->where('approvalstatus', 'APPROVED')->whereYear('created_at', $year);
            }
        ])->get()->map(function($grant) {
            return [
                'grant_id' => $grant->grantid,
                'total_proposals' => $grant->total_proposals,
                'approved_proposals' => $grant->approved_proposals,
                'efficiency_rate' => $grant->total_proposals > 0 ? 
                    round(($grant->approved_proposals / $grant->total_proposals) * 100, 2) : 0
            ];
        });
    }

    // Dashboard Summary for Reports
    public function getReportsSummary()
    {
        if (!auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $totalProposals = Proposal::count();
            $totalProjects = ResearchProject::count();
            $totalFunding = ResearchFunding::sum('amount') ?? 0;
            $totalPublications = Publication::count();
            $activeUsers = User::where('isactive', true)->count();

            $recentActivity = [
                'proposals_this_month' => Proposal::whereMonth('created_at', date('m'))->count(),
                'projects_this_month' => ResearchProject::whereMonth('created_at', date('m'))->count(),
                'funding_this_month' => ResearchFunding::whereMonth('created_at', date('m'))->sum('amount') ?? 0,
                'publications_this_year' => Publication::where('year', date('Y'))->count()
            ];

            return response()->json([
                'success' => true,
                'totals' => [
                    'proposals' => $totalProposals,
                    'projects' => $totalProjects,
                    'funding' => $totalFunding,
                    'publications' => $totalPublications,
                    'active_users' => $activeUsers
                ],
                'recent_activity' => $recentActivity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load reports summary',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
