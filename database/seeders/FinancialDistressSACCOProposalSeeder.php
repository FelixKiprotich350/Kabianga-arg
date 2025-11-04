<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FinancialDistressSACCOProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Geoffrey Ngetich Iletaach',
                'email' => 'geilenge36@gmail.com',
                'password' => Hash::make('password123'),
                'phonenumber' => '0724687972',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Geoffrey Ngetich Iletaach (Email: geilenge36@gmail.com, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Entrepreneurship%')->first();
            
            if (!$theme) {
                $theme = DB::table('researchthemes')->first();
            }

            if (!$grants || !$departments || !$theme) {
                echo "Missing required data. Please ensure grants, departments, and themes exist.\n";
                return;
            }

            $proposalCode = 'PROP' . str_pad(DB::table('proposals')->count() + 1, 4, '0', STR_PAD_LEFT);

            $proposalId = DB::table('proposals')->insertGetId([
                'proposalcode' => $proposalCode,
                'grantnofk' => $grants->grantid,
                'departmentidfk' => $departments->depid,
                'useridfk' => $user->userid,
                'pfnofk' => $user->pfno,
                'themefk' => $theme->themeid,
                'submittedstatus' => 'PENDING',
                'receivedstatus' => 'PENDING',
                'approvalstatus' => 'DRAFT',
                'allowediting' => true,
                'highqualification' => 'Masters Degree',
                'officephone' => '0724687972',
                'cellphone' => '0724687972',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Financial Distress Factors, Firm Size and Financial Performance of Deposit Taking SACCOs in Kenya',
                'commencingdate' => '2024-06-01',
                'terminationdate' => '2025-05-31',
                'objectives' => "General Objective:\nTo investigate the relationship between financial distress factors, firm size and financial performance of Deposit Taking SACCOs in Kenya.\n\nSpecific Objectives:\n1. To investigate the relationship between financial distress factors, firm size and financial performance of Deposit Taking SACCOs in Kenya\n2. To determine the relationship between liquidity and financial performance of Deposit Taking SACCOs in Kenya\n3. To evaluate the relationship between leverage and financial performance of Deposit Taking SACCOs in Kenya\n4. To establish the relationship between operational efficiency and financial performance of Deposit Taking SACCOs in Kenya\n5. To evaluate the relationship between asset quality and financial performance of Deposit Taking SACCOs in Kenya\n6. To determine the relationship between capital sufficiency and financial performance of Deposit Taking SACCOs in Kenya\n7. To examine the moderating effect of firm size on the relationship between financial distress factors and financial performance of Deposit Taking SACCOs in Kenya",
                'hypothesis' => "Research Hypotheses:\nHo1: Liquidity has no statistically significant relationship with financial performance of Deposit taking SACCOs in Kenya\nHo2: Leverage has no statistically significant relationship with financial performance of Deposit taking SACCOs in Kenya\nHo3: Operational efficiency has no statistically significant relationship with financial performance of Deposit taking SACCOs in Kenya\nHo4: Asset quality has no statistically significant relationship with the financial performance of Deposit taking SACCOs in Kenya\nHo5: Capital sufficiency has no statistically significant relationship with the financial performance of Deposit taking SACCOs in Kenya\nHo6: Firm size has no statistically significant moderating effect on the relationship between financial distress factors and financial performance of Deposit Taking SACCOs in Kenya",
                'significance' => "SACCOs in Kenya play a crucial role in economic growth by managing significant money circulation, contributing to GDP, and supporting development goals. However, despite SASRA's supervision, 51% of SACCOs face financial distress, mainly due to cash flow issues, unmet obligations, declining profits, low dividends, membership withdrawals, and loan defaults. Identifying distress factors such as liquidity, leverage, operational efficiency, asset quality, and capital sufficiency is essential to reversing this trend.\n\nThis study is significant for regulators like SASRA, helping refine policies to prevent SACCO failures. It will assist SACCOs in monitoring financial distress indicators, enabling better decision-making for investors, policymakers, and scholars. Investors will gain insights into SACCO risks, while policymakers can strengthen regulations. Additionally, SACCO management will learn strategies to mitigate distress, ensuring sustainability. Scholars will benefit from valuable literature on financial distress factors, firm size, and financial performance in SACCOs.",
                'ethicals' => "This involves making sure that the necessary research permissions are in place before the study can begin. This entails receiving a letter of permission from the University of Kabianga Board of Graduate Studies. This will provide the researcher the opportunity to request approval from the relevant universities as well as get another research permit from the National Commission for Science, Technology, and Innovation (NACOSTI).\n\nIn addition, the researcher will speak with the respondents to explain the study's goals and get their voluntary agreement to participate. No form of coercion or undue influence will be used during the exercise. The respondents will also be allowed to withdraw from the exercise at any stage if they opt to.\n\nTo keep the identity of the respondents anonymous, a clause will be included in the data collection instrument asking the respondent not to indicate their names on the instrument. Finally, the researcher will protect the information provided during the study and ensure it is only used for the intended purpose. A copy of the study findings will be availed to NACOSTI and any interested party upon request.",
                'expoutput' => "Expected Outputs:\n1. Research Publications in academic journals\n2. Point of academic Reference\n3. Evidence-based insights into SACCO financial distress factors\n4. Policy recommendations for SASRA and financial regulators\n5. Guidelines for SACCO management on financial distress mitigation\n6. Academic contribution to literature on financial performance in cooperative societies",
                'socio_impact' => "Socio-Economic Impact:\n1. Identifying financial distress factors helps businesses improve sustainability, reducing failures and enhancing job security\n2. Stronger firms drive economic growth by generating revenue, paying taxes, and expanding operations\n3. Financial market stability improves as firms make informed decisions, reducing market shocks\n4. Investors benefit from better insights into company financial health, leading to improved investment choices\n5. Policymakers can use the findings to design regulations that promote business stability\n6. Enhanced credit access allows banks to assess firm risks more accurately, supporting SMEs in achieving long-term growth\n7. Understanding financial distress reduces bankruptcy rates and boosts consumer confidence in stable businesses\n8. The research encourages entrepreneurship by helping new business owners avoid financial pitfalls\n9. Improved corporate governance fosters transparency and accountability\n10. Business stability contributes to societal welfare through employment and community support, ultimately reducing poverty and enhancing economic well-being",
                'res_findings' => null,
                'comment' => null,
                'approvedrejectedbywhofk' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Collaborators
            DB::table('collaborators')->insert([
                [
                    'collaboratorid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'collaboratorname' => 'Dr. Penina Langat',
                    'position' => 'Lecturer',
                    'institution' => 'University of Kabianga, Department of Accounting and Finance',
                    'researcharea' => 'Finance',
                    'experience' => '10 years',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'collaboratorid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'collaboratorname' => 'Dr. Gichuki Kingori',
                    'position' => 'Head of Department',
                    'institution' => 'University of Kabianga, Department of Accounting and Finance',
                    'researcharea' => 'Accounting and Finance',
                    'experience' => 'Over 10 years',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);

            // Publications
            $publications = [
                ['Iletaach, G. N., Langat, P., & Kingori, G.', 2024, 'Assessing the impact of asset quality on financial performance: A study of Kenyan deposit taking SACCOs', 'International Journal of Research and Innovation in Social Science, 8(8)'],
                ['Iletaach, G. N., Kingori, G., & Langat, P.', 2024, 'Capital sufficiency and financial performance of deposit taking SACCOs in Kenya', 'International Journal of Latest Research in Humanities and Social Science, 7(8), 41–50'],
                ['Iletaach, G. N.', 2024, 'Moderating effect of firm size on financial distress factors and financial performance of deposit taking SACCOs in Kenya', 'International Journal of Latest Research in Humanities and Social Science, 7(9), 19–28'],
                ['Iletaach, G. N.', 2024, 'Operation efficiency and financial performance of deposit taking SACCOs in Kenya', 'International Journal of Business Marketing and Management, 9(5), 1–10'],
                ['Kingori, G.', 2025, 'Human Capital Disclosure and Firm Value; Does Audit Committee Size Matters. Evidence from Listed Firms in Nairobi Securities Exchange, Kenya', 'Journal of Finance and Accounting, 9(1), 40-58']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Finance and Accounting',
                    'publisher' => $pub[3],
                    'volume' => '1',
                    'pages' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Research Design
            DB::table('researchdesigns')->insert([
                'designid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'summary' => 'Positivist research philosophy with correlational and longitudinal approach using secondary data from 176 Kenyan deposit-taking SACCOs (2012-2022)',
                'indicators' => 'Liquidity ratios, Debt-to-equity ratio, Cost-to-income ratio, NPL ratio, Capital sufficiency ratio, ROA, ROE, Total assets',
                'verification' => 'Financial statements, SASRA reports, Audited SACCO financials, Regulatory reports, Credit reports',
                'assumptions' => 'SACCOs provide accurate financial data, No major external shocks, Leverage accurately reported, Operational expenses consistently recorded, Loan classifications accurate, Capital adequacy measured consistently, Firm size data available',
                'goal' => 'Investigate the relationship between financial distress factors, firm size and financial performance of Deposit Taking SACCOs in Kenya',
                'purpose' => 'Provide evidence-based insights to improve SACCO financial performance and sustainability through understanding financial distress factors',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Research permit Application', 'Month 1', 'Application processing', 'NACOSTI', 'Researcher', 'Research Permit'],
                ['Introduction letter request', 'Month 1', 'Processing Application', 'Department', 'Researcher', 'Introduction letter'],
                ['Designing research questionnaires', 'Month 1', 'Extensive research', 'Relevant information', 'Researcher', 'Research Questionnaire'],
                ['Training of research assistants', 'Month 1', 'Seeking consent', 'Research questionnaire', 'Researcher', 'Skilled research assistants'],
                ['Pilot study', 'Month 1', 'Conducting pilot', 'Questionnaires', 'Researcher', 'Pilot data'],
                ['Analysis of pilot study data', 'Month 1', 'Analytical skills', 'STATA software', 'Researcher and data analyst', 'Pilot test report'],
                ['Review and adjusting questionnaire', 'Month 1', 'Extensive research', 'Relevant information', 'Researcher', 'Adjusted questionnaire'],
                ['Research permit to institutions', 'Months 2-3', 'Processing application', 'Requirements', 'Researcher', 'Authorization letters'],
                ['Ethical approval and consent form', 'Months 2-3', 'Processing application', 'Requirements', 'Researcher', 'Ethical approval certificate'],
                ['Data collection', 'Months 4-6', 'Administering questionnaires', 'Data extraction form', 'Researcher and assistants', 'Raw Data'],
                ['Data Analysis', 'Months 7-10', 'Coding and Analysis', 'STATA software', 'Researcher and Data analyst', 'Report findings']
            ];

            foreach ($workplan as $activity) {
                DB::table('workplans')->insert([
                    'workplanid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'activity' => $activity[0],
                    'time' => $activity[1],
                    'input' => $activity[2],
                    'facilities' => $activity[3],
                    'bywhom' => $activity[4],
                    'outcome' => $activity[5],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Budget
            $expenditures = [
                ['Research Permit - NACOSTI', 'Equipment and facilities', 1, 2000],
                ['UoK ISERC', 'Equipment and facilities', 1, 3000],
                ['Printing questionnaires - Pilot', 'Equipment and facilities', 40, 10],
                ['Printing questionnaires - Main study', 'Equipment and facilities', 350, 10],
                ['Note books', 'Equipment and facilities', 3, 400],
                ['Pens - biro', 'Equipment and facilities', 25, 20],
                ['Laptop', 'Equipment and facilities', 1, 58000],
                ['Flash disk (2Gb)', 'Equipment and facilities', 2, 800],
                ['Air time & Bundles', 'Equipment and facilities', 9, 12000],
                ['Printing for defense', 'Equipment and facilities', 6, 1200],
                ['Final Thesis printing', 'Equipment and facilities', 9, 1500],
                ['Pilot study allowance', 'Travel and subsistence', 1, 10000],
                ['Main study allowance', 'Travel and subsistence', 3, 10000],
                ['Research Analyst - Pilot', 'Personnel and other cost', 1, 6500],
                ['Research Analyst - Main study', 'Personnel and other cost', 1, 60000],
                ['Research paper publication', 'Personnel and other cost', 3, 20000],
                ['Printing proposals - Dept', 'Consumables', 4, 700],
                ['Printing proposals - School', 'Consumables', 4, 700]
            ];

            foreach ($expenditures as $item) {
                DB::table('expenditures')->insert([
                    'expenditureid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'item' => $item[0],
                    'itemtype' => $item[1],
                    'quantity' => $item[2],
                    'unitprice' => $item[3],
                    'total' => $item[2] * $item[3],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $totalBudget = DB::table('expenditures')
                ->where('proposalidfk', $proposalId)
                ->sum('total');

            DB::commit();
            
            echo "Successfully created Financial Distress SACCO Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Geoffrey Ngetich Iletaach\n";
            echo "Theme: Entrepreneurship\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: June 2024 - May 2025 (12 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
