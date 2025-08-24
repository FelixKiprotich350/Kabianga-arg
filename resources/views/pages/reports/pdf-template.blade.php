<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin-top: 0;
            color: #495057;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-card {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 22%;
        }
        .summary-card h4 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        .status-paused { background-color: #fff3cd; color: #856404; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>University of Kabianga - Annual Research Grants Portal</p>
        <p>Generated on: {{ date('F j, Y \a\t g:i A') }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Applied Filters</h3>
        @foreach($filters as $key => $value)
            @if($value && $value != 'all')
                <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</p>
            @endif
        @endforeach
    </div>
    @endif

    @if(isset($data->totals))
    <div class="section">
        <h3>Summary Statistics</h3>
        <div class="summary-cards">
            <div class="summary-card">
                <h4>Total Proposals</h4>
                <div class="value">{{ $data->totals->proposals ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <h4>Active Projects</h4>
                <div class="value">{{ $data->totals->projects ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <h4>Total Funding</h4>
                <div class="value">KES {{ number_format($data->totals->funding ?? 0) }}</div>
            </div>
            <div class="summary-card">
                <h4>Publications</h4>
                <div class="value">{{ $data->totals->publications ?? 0 }}</div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data->total_funding))
    <div class="section">
        <h3>Financial Summary</h3>
        <div class="summary-cards">
            <div class="summary-card">
                <h4>Total Funding</h4>
                <div class="value">KES {{ number_format($data->total_funding) }}</div>
            </div>
            <div class="summary-card">
                <h4>Average Funding</h4>
                <div class="value">KES {{ number_format($data->average_funding) }}</div>
            </div>
            <div class="summary-card">
                <h4>Funding Count</h4>
                <div class="value">{{ $data->funding_count }}</div>
            </div>
            <div class="summary-card">
                <h4>Budget Utilization</h4>
                <div class="value">{{ $data->budget_utilization }}%</div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data->projects))
    <div class="section">
        <h3>Projects Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Project Number</th>
                    <th>Title</th>
                    <th>Applicant</th>
                    <th>Status</th>
                    <th>Theme</th>
                    <th>Grant</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->projects as $project)
                <tr>
                    <td>{{ $project->number ?? 'N/A' }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->applicant }}</td>
                    <td><span class="status-badge status-{{ strtolower($project->status) }}">{{ $project->status }}</span></td>
                    <td>{{ $project->theme }}</td>
                    <td>{{ $project->grant }}</td>
                    <td>{{ $project->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data->users))
    <div class="section">
        <h3>User Activity Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Proposals</th>
                    <th>Approved</th>
                    <th>Success Rate</th>
                    <th>Active Projects</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->department }}</td>
                    <td>{{ $user->proposal_count }}</td>
                    <td>{{ $user->approved_proposals }}</td>
                    <td>{{ $user->success_rate }}%</td>
                    <td>{{ $user->active_projects }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data->recent_publications))
    <div class="section">
        <h3>Recent Publications</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Authors</th>
                    <th>Year</th>
                    <th>Publisher</th>
                    <th>Theme</th>
                    <th>Applicant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->recent_publications as $pub)
                <tr>
                    <td>{{ $pub->title }}</td>
                    <td>{{ $pub->authors }}</td>
                    <td>{{ $pub->year }}</td>
                    <td>{{ $pub->publisher }}</td>
                    <td>{{ $pub->theme }}</td>
                    <td>{{ $pub->applicant }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(is_array($data) && count($data) > 0)
    <div class="section">
        <h3>Proposals Data</h3>
        <table>
            <thead>
                <tr>
                    <th>Proposal ID</th>
                    <th>Applicant</th>
                    <th>Department</th>
                    <th>Theme</th>
                    <th>Grant</th>
                    <th>Status</th>
                    <th>Qualification</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $proposal)
                <tr>
                    <td>{{ $proposal->proposalid ?? 'N/A' }}</td>
                    <td>{{ $proposal->applicant->name ?? 'N/A' }}</td>
                    <td>{{ $proposal->department->shortname ?? 'N/A' }}</td>
                    <td>{{ $proposal->themeitem->themename ?? 'N/A' }}</td>
                    <td>{{ $proposal->grantitem->grantid ?? 'N/A' }}</td>
                    <td><span class="status-badge status-{{ strtolower($proposal->approvalstatus) }}">{{ $proposal->approvalstatus }}</span></td>
                    <td>{{ $proposal->highqualification ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the UoK ARG Portal System</p>
        <p>For questions or support, please contact the system administrator</p>
    </div>
</body>
</html>