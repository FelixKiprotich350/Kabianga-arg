<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Research Proposal - {{ $proposal->proposalcode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #2c3e50;
            background: #fff;
            margin: 0;
            padding: 5px;
        }

        .page-container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c5aa0;
        }

        .university-logo {
            width: 70px;
            height: auto;
            margin-bottom: 8px;
        }

        .university-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .document-title {
            font-size: 14px;
            font-weight: bold;
            color: #34495e;
            margin: 8px 0;
            text-transform: uppercase;
        }

        .proposal-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 10px;
            color: #7f8c8d;
        }

        .content {
            margin-top: 20px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 10px;
            padding: 6px 10px;
            background: #f8f9fa;
            border-left: 3px solid #88CDFF;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-left: 0;
        }

        .info-table th {
            background: #88CDFF;
            color: #495057;
            font-weight: bold;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            width: 150px;
        }

        .info-table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            background: #fff;
            vertical-align: top;
            text-align: left;
        }

        .info-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table th {
            background: #88CDFF;
            color: #495057;
            font-weight: bold;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #88CDFF;
        }

        .data-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            background: #fff;
            font-size: 10px;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .text-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2c5aa0;
            margin-bottom: 15px;
            text-align: justify;
            line-height: 1.5;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #2c5aa0;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }

        .footer .disclaimer {
            font-style: italic;
            margin-bottom: 5px;
        }

        .footer .copyright {
            font-weight: bold;
            color: #2c5aa0;
        }

        .amount {
            font-weight: bold;
            color: #27ae60;
        }

        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.approved {
            background: #d4edda;
            color: #155724;
        }

        .status.rejected {
            background: #f8d7da;
            color: #721c24;
        }

        @media print {
            body {
                padding: 10px;
            }

            .page-container {
                margin: 0;
            }
        }
    </style>

</head>

<body>
    <div class="page-container">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
                alt="University Logo" class="university-logo" />
            <div class="university-name">University of Kabianga</div>
            <div class="document-title">Annual Research Grant Proposal</div>
            <div class="proposal-meta">
                <span><strong>Proposal Code:</strong> {{ $proposal->proposalcode }}</span>
                <span><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</span>
                <span class="status {{ $proposal->approvalstatus }}">{{ $proposal->approvalstatus }}</span>
            </div>
        </div>
        <div class="content">
            <div class="section">
                <div class="section-title">A: Principal Investigator Details</div>
                <table class="info-table">
                    <tr>
                        <th>Full Name</th>
                        <td>{{ $proposal->applicant->name }}</td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td>{{ $proposal->applicant->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $proposal->applicant->phonenumber ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $proposal->applicant->gender ?? 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $proposal->department->description ?? ($proposal->department->shortname ?? 'Not specified') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">B: Research Project Overview</div>
                <table class="info-table">
                    <tr>
                        <th>Research Title</th>
                        <td><strong>{{ $proposal->researchtitle }}</strong></td>
                    </tr>
                    <tr>
                        <th>Research Theme</th>
                        <td>{{ $proposal->themeitem->themename ?? 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <th>Grant Type</th>
                        <td>{{ $proposal->grantitem->title ?? 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <th>Requested Amount</th>
                        <td class="amount">{{ $proposal->grantitem->status ?? 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <th>Project Duration</th>
                        <td>{{ \Carbon\Carbon::parse($proposal->commencingdate)->format('M j, Y') }} -
                            {{ \Carbon\Carbon::parse($proposal->terminationdate)->format('M j, Y') }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">C: Research Objectives</div>
                <div class="text-content">
                    {{ $proposal->objectives ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">D: Research Hypothesis</div>
                <div class="text-content">
                    {{ $proposal->hypothesis ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">E: Research Significance</div>
                <div class="text-content">
                    {{ $proposal->significance ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">F: Ethical Considerations</div>
                <div class="text-content">
                    {{ $proposal->ethicals ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">G: Expected Outputs</div>
                <div class="text-content">
                    {{ $proposal->expoutput ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">H: Social Impact</div>
                <div class="text-content">
                    {{ $proposal->socio_impact ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">I: Expected Research Findings</div>
                <div class="text-content">
                    {{ $proposal->res_findings ?? 'Not provided' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">J: Budget & Expenditures</div>
                @if ($proposal->expenditures && $proposal->expenditures->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Item Description</th>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 20%;">Unit Cost</th>
                                <th style="width: 15%;">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBudget = 0; @endphp
                            @foreach ($proposal->expenditures as $expenditure)
                                @php $totalBudget += $expenditure->total ?? 0; @endphp
                                <tr>
                                    <td>{{ $expenditure->item ?? 'Not specified' }}</td>
                                    <td>{{ $expenditure->itemtype ?? 'General' }}</td>
                                    <td class="amount">KES {{ number_format($expenditure->unitprice ?? 0, 2) }}</td>
                                    <td class="amount">KES {{ number_format($expenditure->total ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr style="background: #e9ecef; font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total Budget:</td>
                                <td class="amount">KES {{ number_format($totalBudget, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <div class="text-content">No expenditure details provided.</div>
                @endif
            </div>

            <div class="section">
                <div class="section-title">K: Research Methodology & Design</div>
                @if ($proposal->researchdesigns && $proposal->researchdesigns->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Methodology Summary</th>
                                <th style="width: 30%;">Key Indicators</th>
                                <th style="width: 30%;">Expected Goals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposal->researchdesigns as $design)
                                <tr>
                                    <td>{{ $design->summary ?? 'Not provided' }}</td>
                                    <td>{{ $design->indicators ?? 'Not provided' }}</td>
                                    <td>{{ $design->goal ?? 'Not provided' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-content">No research design details provided.</div>
                @endif
            </div>

            <div class="section">
                <div class="section-title">L: Project Work Plan</div>
                @if ($proposal->workplans && $proposal->workplans->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Activity</th>
                                <th style="width: 20%;">Timeline</th>
                                <th style="width: 25%;">Required Input</th>
                                <th style="width: 20%;">Responsible Person</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposal->workplans as $workplan)
                                <tr>
                                    <td>{{ $workplan->activity ?? 'Not specified' }}</td>
                                    <td>{{ $workplan->time ?? 'Not specified' }}</td>
                                    <td>{{ $workplan->input ?? 'Not specified' }}</td>
                                    <td>{{ $workplan->bywhom ?? 'Not specified' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-content">No work plan details provided.</div>
                @endif
            </div>


            <div class="section">
                <div class="section-title">M: Research Collaborators</div>
                @if ($proposal->collaborators && $proposal->collaborators->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Collaborator Name</th>
                                <th style="width: 25%;">Role/Position</th>
                                <th style="width: 40%;">Institution/Organization</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposal->collaborators as $collaborator)
                                <tr>
                                    <td>{{ $collaborator->collaboratorname ?? 'Not provided' }}</td>
                                    <td>{{ $collaborator->position ?? 'Not specified' }}</td>
                                    <td>{{ $collaborator->institution ?? 'Not specified' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-content">No collaborators listed for this research project.</div>
                @endif
            </div>

            <div class="section">
                <div class="section-title">N: Related Publications</div>
                @if ($proposal->publications && $proposal->publications->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Publication Title</th>
                                <th style="width: 35%;">Publisher/Journal</th>
                                <th style="width: 15%;">Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposal->publications as $publication)
                                <tr>
                                    <td>{{ $publication->title ?? 'Not provided' }}</td>
                                    <td>{{ $publication->publisher ?? 'Not specified' }}</td>
                                    <td>{{ $publication->year ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-content">No related publications listed.</div>
                @endif
            </div>

            @if ($proposal->comment)
                <div class="section">
                    <div class="section-title">O: Additional Comments & Notes</div>
                    <div class="text-content">
                        {{ $proposal->comment }}
                    </div>
                </div>
            @endif
        </div>

        <div class="footer">
            <div class="disclaimer">
                <strong>Disclaimer:</strong> This is a computer-generated document and does not require a physical
                signature.
            </div>
            <div class="copyright">
                &copy; {{ \Carbon\Carbon::now()->format('Y') }} University of Kabianga - Annual Research Grants Program
            </div>
        </div>
    </div>
</body>

</html>
