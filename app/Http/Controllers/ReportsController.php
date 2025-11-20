<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Expenditureitem;
use App\Models\Grant;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\ResearchFunding;
use App\Models\ResearchProgress;
use App\Models\ResearchProject;
use App\Models\ResearchTheme;
use App\Models\School;
use App\Models\User;
use App\Services\PdfGenerationService;
use App\Traits\ApiResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    use ApiResponse;

    protected $pdfService;

    public function __construct(PdfGenerationService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function getallproposals(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $searchTerm = $request->input('search');
        $output = $request->input('output', 'json');

        if ($searchTerm != null) {
            $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')
                ->select(['proposalid', 'researchtitle', 'useridfk', 'departmentidfk', 'grantnofk', 'themefk', 'approvalstatus', 'created_at', 'updated_at'])
                ->where('approvalstatus', 'like', '%'.$searchTerm.'%')
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
        } else {
            $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')
                ->select(['proposalid', 'researchtitle', 'useridfk', 'departmentidfk', 'grantnofk', 'themefk', 'approvalstatus', 'created_at', 'updated_at'])
                ->get();
        }

        if ($output === 'csv') {
            return $this->exportProposalsCSV($data);
        } elseif ($output === 'pdf') {
            return $this->exportProposalsPDF($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Proposals retrieved successfully',
            'data' => $data,
        ]);
    }

    public function getProposalsBySchool(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to view all Proposals!";
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
                    'data' => [],
                ],
                [
                    'label' => 'Male Applicants',
                    'backgroundColor' => 'rgba(236, 141, 87, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Female Applicants',
                    'backgroundColor' => 'rgba(236, 87, 182, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Approved Proposals',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Rejected Proposals',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Pending Proposals',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
            ],
        ];

        foreach ($departments as $department) {
            $filteredProposals = (clone $proposalsQuery)->where('departmentidfk', $department->depid)->get();

            $approvedCount = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $pendingCount = $filteredProposals->where('approvalstatus', 'PENDING')->count();

            $maleCount = $filteredProposals->filter(fn ($proposal) => $proposal->applicant->gender === 'Male')->count();
            $femaleCount = $filteredProposals->filter(fn ($proposal) => $proposal->applicant->gender === 'Female')->count();

            $chartData['labels'][] = $department->shortname;

            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $maleCount;
            $chartData['datasets'][2]['data'][] = $femaleCount;
            $chartData['datasets'][3]['data'][] = $approvedCount;
            $chartData['datasets'][4]['data'][] = $rejectedCount;
            $chartData['datasets'][5]['data'][] = $pendingCount;
        }

        return response()->json([
            'success' => true,
            'message' => 'Proposals by school retrieved successfully',
            'data' => $chartData,
        ]);
    }

    public function getProposalsByTheme(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $grantFilter = $request->input('filtergrant');
        $departmentFilter = $request->input('filterdepartment');

        $proposalsQuery = Proposal::with('department', 'grantitem', 'themeitem', 'applicant');

        if ($grantFilter && $grantFilter != 'all') {
            $proposalsQuery->whereHas('grantitem', function ($query) use ($grantFilter) {
                $query->where('grantid', $grantFilter);
            });
        }

        if ($departmentFilter && $departmentFilter != 'all') {
            $proposalsQuery->where('departmentidfk', $departmentFilter);
        }

        $themes = ResearchTheme::all();
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Total Proposals',
                    'backgroundColor' => 'rgba(17, 126, 73, 1)',
                    'data' => [],
                ],
                [
                    'label' => 'Approved',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'data' => [],
                ],
                [
                    'label' => 'Rejected',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'data' => [],
                ],
                [
                    'label' => 'Pending',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'data' => [],
                ],
            ],
        ];

        foreach ($themes as $theme) {
            $filteredProposals = (clone $proposalsQuery)->whereHas('themeitem', function ($query) use ($theme) {
                $query->where('themeid', $theme->themeid);
            })->get();

            $chartData['labels'][] = $theme->themename;
            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $chartData['datasets'][2]['data'][] = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $chartData['datasets'][3]['data'][] = $filteredProposals->where('approvalstatus', 'PENDING')->count();
        }

        return response()->json([
            'success' => true,
            'message' => 'Proposals by theme retrieved successfully',
            'data' => $chartData,
        ]);
    }

    public function getProjectsReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $output = $request->input('output', 'json');
        $projects = ResearchProject::with('proposal.applicant', 'proposal.department', 'proposal.grantitem')->get();

        if ($output === 'csv') {
            return $this->exportProjectsCSV($projects);
        } elseif ($output === 'pdf') {
            return $this->exportProjectsPDF($projects);
        }

        return response()->json([
            'success' => true,
            'message' => 'Projects report retrieved successfully',
            'data' => $projects,
        ]);
    }

    public function getUsersReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $users = User::with('department')->get();
        $departments = Department::all();

        $departmentData = [];
        foreach ($departments as $department) {
            $deptUsers = $users->where('departmentidfk', $department->depid);
            $departmentData[] = [
                'department' => $department->shortname,
                'total' => $deptUsers->count(),
                'male' => $deptUsers->where('gender', 'Male')->count(),
                'female' => $deptUsers->where('gender', 'Female')->count(),
                'verified' => $deptUsers->where('email_verified_at', '!=', null)->count(),
            ];
        }

        $genderChart = [
            'labels' => ['Male', 'Female'],
            'datasets' => [[
                'label' => 'Users by Gender',
                'backgroundColor' => ['rgba(87, 148, 236, 1)', 'rgba(236, 87, 182, 1)'],
                'data' => [
                    $users->where('gender', 'Male')->count(),
                    $users->where('gender', 'Female')->count(),
                ],
            ]],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Users report retrieved successfully',
            'data' => [
                'genderChart' => $genderChart,
                'departmentData' => $departmentData,
                'totalUsers' => $users->count(),
                'verifiedUsers' => $users->where('email_verified_at', '!=', null)->count(),
            ],
        ]);
    }

    public function getThemesReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $themes = ResearchTheme::withCount('proposals')->get();

        $chartData = [
            'labels' => $themes->pluck('themename')->toArray(),
            'datasets' => [[
                'label' => 'Proposals per Theme',
                'backgroundColor' => 'rgba(17, 126, 73, 1)',
                'data' => $themes->pluck('proposals_count')->toArray(),
            ]],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Themes report retrieved successfully',
            'data' => [
                'chartData' => $chartData,
                'themes' => $themes,
                'totalThemes' => $themes->count(),
            ],
        ]);
    }

    public function getGrantsReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $grants = Grant::withCount('proposals')->get();

        $chartData = [
            'labels' => $grants->pluck('grantname')->toArray(),
            'datasets' => [[
                'label' => 'Proposals per Grant',
                'backgroundColor' => 'rgba(87, 148, 236, 1)',
                'data' => $grants->pluck('proposals_count')->toArray(),
            ]],
        ];

        $totalFunding = $grants->sum('maxfunding');
        $activeGrants = $grants->where('status', 'ACTIVE')->count();

        return response()->json([
            'success' => true,
            'message' => 'Grants report retrieved successfully',
            'data' => [
                'chartData' => $chartData,
                'grants' => $grants,
                'totalGrants' => $grants->count(),
                'activeGrants' => $activeGrants,
                'totalFunding' => $totalFunding,
            ],
        ]);
    }

    public function getSchoolsReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $schools = School::with('departments.proposals')->get();

        $schoolData = [];
        $labels = [];
        $proposalCounts = [];
        $departmentCounts = [];

        foreach ($schools as $school) {
            $totalProposals = $school->departments->sum(function ($dept) {
                return $dept->proposals->count();
            });

            $schoolData[] = [
                'school' => $school->schoolname,
                'departments' => $school->departments->count(),
                'proposals' => $totalProposals,
                'approved' => $school->departments->sum(function ($dept) {
                    return $dept->proposals->where('approvalstatus', 'APPROVED')->count();
                }),
            ];

            $labels[] = $school->schoolname;
            $proposalCounts[] = $totalProposals;
            $departmentCounts[] = $school->departments->count();
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Proposals',
                    'backgroundColor' => 'rgba(17, 126, 73, 1)',
                    'data' => $proposalCounts,
                ],
                [
                    'label' => 'Departments',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'data' => $departmentCounts,
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Schools report retrieved successfully',
            'data' => [
                'chartData' => $chartData,
                'schoolData' => $schoolData,
                'totalSchools' => $schools->count(),
            ],
        ]);
    }

    public function getDepartmentsReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $departments = Department::with('school', 'proposals.applicant', 'users')->get();

        $departmentData = [];
        foreach ($departments as $department) {
            $departmentData[] = [
                'department' => $department->shortname,
                'school' => $department->school->schoolname ?? 'N/A',
                'users' => $department->users->count(),
                'proposals' => $department->proposals->count(),
                'approved' => $department->proposals->where('approvalstatus', 'APPROVED')->count(),
                'rejected' => $department->proposals->where('approvalstatus', 'REJECTED')->count(),
                'pending' => $department->proposals->where('approvalstatus', 'PENDING')->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Departments report retrieved successfully',
            'data' => [
                'departmentData' => $departmentData,
                'totalDepartments' => $departments->count(),
            ],
        ]);
    }

    public function getSummaryReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $output = $request->input('output', 'json');

        $summary = [
            'proposals' => [
                'total' => Proposal::count(),
                'approved' => Proposal::where('approvalstatus', 'APPROVED')->count(),
                'rejected' => Proposal::where('approvalstatus', 'REJECTED')->count(),
                'pending' => Proposal::where('approvalstatus', 'PENDING')->count(),
            ],
            'projects' => [
                'total' => ResearchProject::count(),
                'active' => ResearchProject::where('projectstatus', 'ACTIVE')->count(),
                'completed' => ResearchProject::where('projectstatus', 'COMPLETED')->count(),
                'totalFunding' => ResearchProject::sum('totalfunding'),
            ],
            'users' => [
                'total' => User::count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
                'male' => User::where('gender', 'Male')->count(),
                'female' => User::where('gender', 'Female')->count(),
            ],
            'system' => [
                'themes' => ResearchTheme::count(),
                'grants' => Grant::count(),
                'schools' => School::count(),
                'departments' => Department::count(),
            ],
        ];

        if ($output === 'csv') {
            return $this->exportSummaryCSV($summary);
        } elseif ($output === 'pdf') {
            return $this->exportSummaryPDF($summary);
        }

        return response()->json([
            'success' => true,
            'message' => 'Summary report retrieved successfully',
            'data' => $summary,
        ]);
    }

    public function exportReport(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $type = $request->input('type', 'summary');
        $format = $request->input('format', 'json');

        try {
            switch ($type) {
                case 'proposals':
                    $data = Proposal::with('department', 'grantitem', 'themeitem', 'applicant')->get();
                    break;
                case 'projects':
                    $data = ResearchProject::with('proposal.applicant', 'proposal.department')->get();
                    break;
                case 'users':
                    $data = User::with('department')->get();
                    break;
                default:
                    $data = $this->getSummaryReport($request)->getData();
            }

            return response()->json([
                'success' => true,
                'report_type' => $type,
                'format' => $format,
                'generated_at' => now()->toISOString(),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate report: '.$e->getMessage()], 500);
        }
    }

    public function getProposalsByGrant(Request $request)
    {
        if (! auth()->user()->hasPermission('canViewAllApplications')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); // message: "You are not Authorized to view all Proposals!";
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
                    'data' => [],
                ],
                [
                    'label' => 'Male Applicants',
                    'backgroundColor' => 'rgba(236, 141, 87, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Female Applicants',
                    'backgroundColor' => 'rgba(236, 87, 182, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Approved Proposals',
                    'backgroundColor' => 'rgba(87, 148, 236, 1)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Rejected Proposals',
                    'backgroundColor' => 'rgba(207, 210, 101, 1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
                [
                    'label' => 'Pending Proposals',
                    'backgroundColor' => 'rgba(101, 173, 45, 0.47)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ],
            ],
        ];

        foreach ($grants as $grant) {
            $filteredProposals = (clone $proposalsQuery)->where('grantnofk', $grant->grantid)->get();

            $approvedCount = $filteredProposals->where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = $filteredProposals->where('approvalstatus', 'REJECTED')->count();
            $pendingCount = $filteredProposals->where('approvalstatus', 'PENDING')->count();

            $maleCount = $filteredProposals->filter(fn ($proposal) => $proposal->applicant->gender == 'Male')->count();
            $femaleCount = $filteredProposals->filter(fn ($proposal) => $proposal->applicant->gender == 'Female')->count();

            $chartData['labels'][] = $grant->grantid.'('.$grant->finyear.')';

            $chartData['datasets'][0]['data'][] = $filteredProposals->count();
            $chartData['datasets'][1]['data'][] = $maleCount;
            $chartData['datasets'][2]['data'][] = $femaleCount;
            $chartData['datasets'][3]['data'][] = $approvedCount;
            $chartData['datasets'][4]['data'][] = $rejectedCount;
            $chartData['datasets'][5]['data'][] = $pendingCount;
        }

        return response()->json([
            'success' => true,
            'message' => 'Proposals by grant retrieved successfully',
            'data' => $chartData,
        ]);
    }

    // Financial Reports
    public function getFinancialSummary(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            $grantFilter = $request->input('grant');
            $yearFilter = $request->input('year');

            $query = ResearchFunding::with(['applicant', 'project.proposal.grantitem']);

            if ($grantFilter && $grantFilter != 'all') {
                $query->whereHas('project.proposal.grantitem', function ($q) use ($grantFilter) {
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

            return $this->successResponse([
                'total_funding' => $totalFunding,
                'average_funding' => round($avgFunding, 2),
                'funding_count' => $fundingCount,
                'total_budget' => $budgetData->total_budget ?? 0,
                'budget_utilization' => $budgetData->total_budget > 0 ? round(($totalFunding / $budgetData->total_budget) * 100, 2) : 0,
                'funding_by_month' => $this->getFundingByMonth($yearFilter),
            ], 'Financial summary retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load financial data', $e->getMessage(), 500);
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
                'data' => $data,
            ];
        } catch (\Exception $e) {
            // Return empty data if there's an error
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => array_fill(0, 12, 0),
            ];
        }
    }

    // Publications Report
    public function getPublicationsReport(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
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
                $query->whereHas('proposal.themeitem', function ($q) use ($themeFilter) {
                    $q->where('themeid', $themeFilter);
                });
            }

            $publications = $query->get();

            $publicationsByYear = $publications->groupBy('year')
                ->map(function ($group) {
                    return $group->count();
                })->sortKeys();

            $publicationsByTheme = $publications->groupBy('proposal.themeitem.themename')
                ->map(function ($group) {
                    return $group->count();
                });

            return response()->json([
                'success' => true,
                'total_publications' => $publications->count(),
                'publications_by_year' => $publicationsByYear,
                'publications_by_theme' => $publicationsByTheme,
                'recent_publications' => $publications->sortByDesc('year')->take(10)->map(function ($pub) {
                    return [
                        'title' => $pub->title,
                        'authors' => $pub->authors,
                        'year' => $pub->year,
                        'publisher' => $pub->publisher,
                        'theme' => $pub->proposal->themeitem->themename ?? 'N/A',
                        'applicant' => $pub->proposal->applicant->name ?? 'N/A',
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load publications report',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Progress Tracking Report
    public function getProgressReport(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $statusFilter = $request->input('status');
            $query = ResearchProject::with(['proposal.applicant']);

            if ($statusFilter && $statusFilter != 'all') {
                $query->where('projectstatus', $statusFilter);
            }

            $projects = $query->get();
            $progressData = $projects->map(function ($project) {
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
                    'days_since_report' => $lastProgress ? $lastProgress->created_at->diffInDays(now()) : 'N/A',
                ];
            });

            return response()->json([
                'success' => true,
                'projects' => $progressData,
                'overdue_projects' => $progressData->filter(function ($p) {
                    return is_numeric($p['days_since_report']) && $p['days_since_report'] > 90;
                })->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load progress report',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Compliance Report
    public function getComplianceReport(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $proposals = Proposal::with(['applicant'])->get();
            $projects = ResearchProject::with(['proposal.applicant'])->get();

            $complianceData = [
                'proposals_missing_docs' => $proposals->filter(function ($p) {
                    return empty($p->researchtitle) || empty($p->objectives);
                })->count(),
                'projects_no_progress' => $projects->filter(function ($p) {
                    return ResearchProgress::where('researchidfk', $p->researchid)->count() == 0;
                })->count(),
                'overdue_reports' => $projects->filter(function ($p) {
                    $lastReport = ResearchProgress::where('researchidfk', $p->researchid)->latest()->first();

                    return ! $lastReport || $lastReport->created_at->diffInDays(now()) > 90;
                })->count(),
                'inactive_users' => User::where('isactive', false)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $complianceData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load compliance report',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Performance Analytics Report
    public function getPerformanceReport(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
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
                'grant_efficiency' => $this->getGrantEfficiency($yearFilter),
            ];

            return response()->json([
                'success' => true,
                'data' => $performance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load performance report',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Budget vs Actual Report
    public function getBudgetActualReport(Request $request)
    {
        if (! auth()->user()->haspermission('canviewreports')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $grantFilter = $request->input('grant');
            $query = Proposal::with(['expenditures', 'grantitem']);

            if ($grantFilter && $grantFilter != 'all') {
                $query->where('grantnofk', $grantFilter);
            }

            $proposals = $query->where('approvalstatus', 'APPROVED')->get();

            $budgetData = $proposals->map(function ($proposal) {
                $budgetAmount = $proposal->expenditures->sum('total') ?? 0;
                $actualFunding = ResearchFunding::whereHas('project.proposal', function ($q) use ($proposal) {
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
                        round((($actualFunding - $budgetAmount) / $budgetAmount) * 100, 2) : 0,
                ];
            });

            return response()->json([
                'success' => true,
                'budget_analysis' => $budgetData,
                'total_budget' => $budgetData->sum('budget_amount'),
                'total_actual' => $budgetData->sum('actual_funding'),
                'overall_variance' => $budgetData->sum('variance'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load budget analysis',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function calculateAvgProcessingTime($proposals)
    {
        $approvedProposals = $proposals->where('approvalstatus', 'APPROVED');
        if ($approvedProposals->count() == 0) {
            return 0;
        }

        $totalDays = $approvedProposals->sum(function ($p) {
            return $p->created_at->diffInDays($p->updated_at);
        });

        return round($totalDays / $approvedProposals->count(), 1);
    }

    private function getTopPerformers($year)
    {
        return User::withCount([
            'proposals as approved_count' => function ($q) use ($year) {
                $q->where('approvalstatus', 'APPROVED')->whereYear('created_at', $year);
            },
        ])->orderBy('approved_count', 'desc')->take(5)->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'approved_proposals' => $user->approved_count,
                ];
            });
    }

    private function getGrantEfficiency($year)
    {
        return Grant::withCount([
            'proposals as total_proposals' => function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            },
            'proposals as approved_proposals' => function ($q) use ($year) {
                $q->where('approvalstatus', 'APPROVED')->whereYear('created_at', $year);
            },
        ])->get()->map(function ($grant) {
            return [
                'grant_id' => $grant->grantid,
                'total_proposals' => $grant->total_proposals,
                'approved_proposals' => $grant->approved_proposals,
                'efficiency_rate' => $grant->total_proposals > 0 ?
                    round(($grant->approved_proposals / $grant->total_proposals) * 100, 2) : 0,
            ];
        });
    }

    // Dashboard Summary for Reports
    public function getReportsSummary()
    {
        if (! auth()->user()->haspermission('canviewreports')) {
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
                'publications_this_year' => Publication::where('year', date('Y'))->count(),
            ];

            return response()->json([
                'success' => true,
                'totals' => [
                    'proposals' => $totalProposals,
                    'projects' => $totalProjects,
                    'funding' => $totalFunding,
                    'publications' => $totalPublications,
                    'active_users' => $activeUsers,
                ],
                'recent_activity' => $recentActivity,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load reports summary',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function exportProposalsCSV($data)
    {
        $filename = 'proposals_report_'.date('Y-m-d_H-i-s').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Applicant', 'Department', 'Grant', 'Theme', 'Status', 'Created At']);

            foreach ($data as $proposal) {
                fputcsv($file, [
                    $proposal->proposalid,
                    $proposal->researchtitle,
                    $proposal->applicant->name ?? 'N/A',
                    $proposal->department->shortname ?? 'N/A',
                    $proposal->grantitem->grantid ?? 'N/A',
                    $proposal->themeitem->themename ?? 'N/A',
                    $proposal->approvalstatus->value ?? 'N/A',
                    $proposal->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportProposalsPDF($data)
    {
        $html = '<html><head><title>Proposals Report</title><style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { color: #333; text-align: center; margin-bottom: 5px; }
            h2 { color: #666; text-align: center; margin-top: 0; margin-bottom: 20px; }
            .header-info { text-align: center; margin-bottom: 20px; }
        </style></head><body>';

        $html .= '<h1>University of Kabianga</h1>';
        $html .= '<h2>Research and Innovation Management System</h2>';
        $html .= '<div class="header-info">';
        $html .= '<h3>Proposals Report</h3>';
        $html .= '<p>Generated on: '.date('Y-m-d H:i:s').'</p>';
        $html .= '<p>Generated by: '.auth()->user()->name.'</p>';
        $html .= '</div>';
        $html .= '<table><thead><tr>';
        $html .= '<th>ID</th><th>Title</th><th>Applicant</th><th>Department</th><th>Grant</th><th>Theme</th><th>Status</th><th>Created At</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($data as $proposal) {
            $html .= '<tr>';
            $html .= '<td>'.$proposal->proposalid.'</td>';
            $html .= '<td>'.htmlspecialchars($proposal->researchtitle).'</td>';
            $html .= '<td>'.htmlspecialchars($proposal->applicant->name ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($proposal->department->shortname ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($proposal->grantitem->grantid ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($proposal->themeitem->themename ?? 'N/A').'</td>';
            $html .= '<td>'.($proposal->approvalstatus->value ?? 'N/A').'</td>';
            $html .= '<td>'.$proposal->created_at->format('Y-m-d H:i:s').'</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        $filename = 'proposals_report_'.date('Y-m-d_H-i-s').'.pdf';

        try {
            $pdf = Pdf::loadHTML($html);
            $pdfContent = $pdf->output();

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        } catch (\Exception $e) {
            // Fallback to HTML if PDF generation fails
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="proposals_report_'.date('Y-m-d_H-i-s').'.html"',
            ]);
        }
    }

    private function exportProjectsCSV($data)
    {
        $filename = 'projects_report_'.date('Y-m-d_H-i-s').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Applicant', 'Department', 'Grant', 'Status', 'Total Funding', 'Created At']);

            foreach ($data as $project) {
                fputcsv($file, [
                    $project->researchid,
                    $project->proposal->researchtitle ?? 'N/A',
                    $project->proposal->applicant->name ?? 'N/A',
                    $project->proposal->department->shortname ?? 'N/A',
                    $project->proposal->grantitem->grantid ?? 'N/A',
                    $project->projectstatus,
                    $project->totalfunding ?? 0,
                    $project->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportProjectsPDF($data)
    {
        $html = '<html><head><title>Projects Report</title><style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { color: #333; text-align: center; margin-bottom: 5px; }
            h2 { color: #666; text-align: center; margin-top: 0; margin-bottom: 20px; }
            .header-info { text-align: center; margin-bottom: 20px; }
        </style></head><body>';

        $html .= '<h1>University of Kabianga</h1>';
        $html .= '<h2>Research and Innovation Management System</h2>';
        $html .= '<div class="header-info">';
        $html .= '<h3>Projects Report</h3>';
        $html .= '<p>Generated on: '.date('Y-m-d H:i:s').'</p>';
        $html .= '<p>Generated by: '.auth()->user()->name.'</p>';
        $html .= '</div>';
        $html .= '<table><thead><tr>';
        $html .= '<th>ID</th><th>Title</th><th>Applicant</th><th>Department</th><th>Grant</th><th>Status</th><th>Total Funding</th><th>Created At</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($data as $project) {
            $html .= '<tr>';
            $html .= '<td>'.$project->researchid.'</td>';
            $html .= '<td>'.htmlspecialchars($project->proposal->researchtitle ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($project->proposal->applicant->name ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($project->proposal->department->shortname ?? 'N/A').'</td>';
            $html .= '<td>'.htmlspecialchars($project->proposal->grantitem->grantid ?? 'N/A').'</td>';
            $html .= '<td>'.$project->projectstatus.'</td>';
            $html .= '<td>'.number_format($project->totalfunding ?? 0, 2).'</td>';
            $html .= '<td>'.$project->created_at->format('Y-m-d H:i:s').'</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        $filename = 'projects_report_'.date('Y-m-d_H-i-s').'.pdf';

        try {
            $pdf = Pdf::loadHTML($html);
            $pdfContent = $pdf->output();

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        } catch (\Exception $e) {
            // Fallback to HTML if PDF generation fails
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="projects_report_'.date('Y-m-d_H-i-s').'.html"',
            ]);
        }
    }

    private function exportSummaryCSV($data)
    {
        $filename = 'summary_report_'.date('Y-m-d_H-i-s').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Category', 'Metric', 'Count']);

            foreach ($data as $category => $metrics) {
                foreach ($metrics as $metric => $count) {
                    fputcsv($file, [ucfirst($category), ucfirst(str_replace('_', ' ', $metric)), $count]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportSummaryPDF($data)
    {
        $html = '<html><head><title>Summary Report</title><style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { color: #333; text-align: center; margin-bottom: 5px; }
            h2 { color: #666; text-align: center; margin-top: 0; margin-bottom: 20px; }
            .header-info { text-align: center; margin-bottom: 20px; }
        </style></head><body>';

        $html .= '<h1>University of Kabianga</h1>';
        $html .= '<h2>Research and Innovation Management System</h2>';
        $html .= '<div class="header-info">';
        $html .= '<h3>Summary Report</h3>';
        $html .= '<p>Generated on: '.date('Y-m-d H:i:s').'</p>';
        $html .= '<p>Generated by: '.auth()->user()->name.'</p>';
        $html .= '</div>';
        $html .= '<table><thead><tr><th>Category</th><th>Metric</th><th>Count</th></tr></thead><tbody>';

        foreach ($data as $category => $metrics) {
            foreach ($metrics as $metric => $count) {
                $html .= '<tr>';
                $html .= '<td>'.ucfirst($category).'</td>';
                $html .= '<td>'.ucfirst(str_replace('_', ' ', $metric)).'</td>';
                $html .= '<td>'.$count.'</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table></body></html>';

        $filename = 'summary_report_'.date('Y-m-d_H-i-s').'.pdf';

        try {
            $pdf = Pdf::loadHTML($html);
            $pdfContent = $pdf->output();

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        } catch (\Exception $e) {
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="summary_report_'.date('Y-m-d_H-i-s').'.html"',
            ]);
        }
    }
}
