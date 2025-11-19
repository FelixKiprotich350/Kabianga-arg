<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ResearchFunding;
use App\Models\ResearchProject;
use App\Models\ResearchTheme;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function chartdata(Request $request)
    {
        if (!auth()->user()->haspermission('canviewdashboard')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            $themes = ResearchTheme::all();

            // Bar Chart Data 1 - Proposals per Theme
            $barChartData = [
                'labels' => [],
                'datasets' => [[
                    'label' => 'Proposals per Theme',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ]],
            ];

            foreach ($themes as $theme) {
                $count = Proposal::where('themefk', $theme->themeid)->count();
                $barChartData['labels'][] = $theme->themename;
                $barChartData['datasets'][0]['data'][] = $count;
            }

            // Pie Chart Data - Proposal Status Counts
            $approvedCount = Proposal::where('approvalstatus', 'APPROVED')->count();
            $rejectedCount = Proposal::where('approvalstatus', 'REJECTED')->count();
            $pendingCount = Proposal::where('approvalstatus', 'PENDING')->count();
            $draftCount = Proposal::where('approvalstatus', 'DRAFT')->count();

            $pieChartData = [
                'labels' => ['Approved', 'Rejected', 'Pending', 'Draft'],
                'datasets' => [[
                    'data' => [$approvedCount, $rejectedCount, $pendingCount, $draftCount],
                    'backgroundColor' => [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(108, 117, 125, 1)',
                    ],
                    'borderWidth' => 1,
                ]],
            ];

            // Bar Chart Data 2 - Projects per Theme
            $barChart2Data = [
                'labels' => [],
                'datasets' => [[
                    'label' => 'Projects per Theme',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.8)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [],
                ]],
            ];

            foreach ($themes as $theme) {
                $count = ResearchProject::whereHas('proposal', function ($query) use ($theme) {
                    $query->where('themefk', $theme->themeid);
                })->count();
                $barChart2Data['labels'][] = $theme->themename;
                $barChart2Data['datasets'][0]['data'][] = $count;
            }

            $chartData = [
                'barChart_data1' => $barChartData,
                'barchart_data2' => $barChart2Data,
                'pieChart' => $pieChartData,
            ];

            return $this->successResponse($chartData, 'Chart data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch chart data', null, 500);
        }
    }

    public function getStats()
    {
        if (!auth()->user()->haspermission('canviewdashboard')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            $stats = [
                'proposals' => [
                    'total' => Proposal::count(),
                    'approved' => Proposal::where('approvalstatus', 'APPROVED')->count(),
                    'pending' => Proposal::where('approvalstatus', 'PENDING')->count(),
                    'rejected' => Proposal::where('approvalstatus', 'REJECTED')->count(),
                ],
                'projects' => [
                    'total' => ResearchProject::count(),
                    'active' => ResearchProject::where('projectstatus', ResearchProject::STATUS_ACTIVE)->count(),
                    'completed' => ResearchProject::where('projectstatus', ResearchProject::STATUS_COMPLETED)->count(),
                    'cancelled' => ResearchProject::where('projectstatus', ResearchProject::STATUS_CANCELLED)->count(),
                ],
                'funding' => [
                    'total' => ResearchFunding::sum('amount') ?? 0,
                    'count' => ResearchFunding::count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('isactive', true)->count(),
                    'inactive' => User::where('isactive', false)->count(),
                ],
            ];

            return $this->successResponse($stats, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch stats', $e, 500);
        }
    }

}
