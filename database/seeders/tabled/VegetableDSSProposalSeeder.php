<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VegetableDSSProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Dr. Carolyne Cherotich',
                'email' => 'crono@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '+254722617815',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Dr. Carolyne Cherotich (Email: crono@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Food Security%')->first();
            
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
                'highqualification' => 'Doctor of Philosophy: Extension Education',
                'officephone' => 'N/A',
                'cellphone' => '+254722617815',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Enhancing Vegetable Production through a Decision Support System (DSS)',
                'commencingdate' => '2024-09-01',
                'terminationdate' => '2025-09-01',
                'objectives' => "General Objective:\nTo enhance decision-making of vegetable growers by providing precise, timely, and actionable information through integration of real-time data, predictive analytics, and facilitation of knowledge sharing for overall improvement in productivity, profitability, and sustainability of vegetable farming.\n\nSpecific Objectives:\n1. Develop a comprehensive DSS: Design and build a user-friendly, scalable, and robust DSS tailored for vegetable production\n2. Data Integration: Collect and integrate data from various sources, including weather forecasts, soil sensors, pest and disease reports, and market trends\n3. Predictive Analytics: Implement predictive models to forecast crop yields, pest outbreaks, and resource requirements\n4. Best Practices and Recommendations: Provide actionable insights and recommendations based on integrated data and predictive analytics\n5. Pilot Implementation: Test and refine the DSS through pilot projects with selected vegetable producers\n6. Training and Capacity Building: Develop training programs to ensure effective adoption and utilization of the DSS by vegetable producers",
                'hypothesis' => 'N/A',
                'significance' => "Vegetable production faces numerous challenges, including pest management, climate variability, soil health, and optimized use of resources. A Decision Support System (DSS) tailored for vegetable production can empower farmers with actionable insights and data-driven recommendations to enhance productivity, sustainability, and profitability. This project aims to develop and implement a DSS that integrates real-time data, predictive analysis, and Good Agricultural Practices (GAPs) to support vegetable decision-making processes.\n\nThe DSS will enable farmers to make informed decisions on crop monitoring, pest management, watering scheduling, and yield prediction. By leveraging technology and data, the system will support efficient farming decisions that enhance productivity, sustainability, and profitability in vegetable production.",
                'ethicals' => "Informed Consent: All stakeholders who will take part in the survey and focus group discussions will be taken through study objectives to help them understand what the study entails.\n\nConfidentiality and anonymity: Questionnaires to be administered will not demand provision of identity. Additionally, all sensitive information provided by respondents will be held with utmost confidentiality. In case of focus group discussion where the research will be in direct contact with the participants, no identity of discussant will be revealed.\n\nVoluntary participation: Respondents will not be coerced to take part in the study, but rather by their own volition.",
                'expoutput' => "The key output will be a comprehensive report, out of which at least three publications in refereed journals is expected.\n\nExpected outputs include:\n1. Functional Decision Support System for vegetable production\n2. User manuals and technical documentation\n3. Training materials for farmers and extension officers\n4. At least three publications in refereed journals\n5. Conference presentations\n6. Policy guidelines and briefs",
                'socio_impact' => "1. Enhanced Decision-Making: Producers will have access to real-time data and predictive insights, leading to more informed and timely decisions\n2. Increased Productivity: Optimized resource use and proactive management will lead to higher crop yields and quality\n3. Sustainability: Improved practices will reduce environmental impact and promote sustainable horticulture\n4. Scalability: The DSS will be designed for scalability, allowing for broader adoption across different regions and horticultural systems\n5. Economic benefits through improved market access and better pricing decisions\n6. Reduced environmental impact through optimized resource use",
                'res_findings' => null,
                'comment' => null,
                'approvedrejectedbywhofk' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Collaborators
            DB::table('collaborators')->insert([
                'collaboratorid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'collaboratorname' => 'Dr Patrick Bii',
                'position' => 'Senior Lecturer',
                'institution' => 'University of Kabianga',
                'researcharea' => 'Education Technology',
                'experience' => 'More than 5 years',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Publications
            $publications = [
                ['Cherotich, C and Kaur M', 2021, 'Determinants of Awareness of Good Agricultural Practices (GAP) among Vegetable Growers in Punjab, India', 'Int.J.Curr.Microbiol.App.Sci'],
                ['Cherotich, C and Kaur M', 2021, 'Determinants of Awareness of Good Agricultural Practices (GAP) among Vegetable Growers in Nakuru, Kenya', 'Journal of Community Development and Sustainable Development'],
                ['Cherotich C and Kaur M', 2021, 'Understanding the intention to use Good Agricultural Practices (GAP) on Vegetable farms – A comparative study of farmers in Punjab, India and Nakuru, Kenya', 'African Journal of Agriculture and Food Science. Volume 4, Issue 4']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Agricultural Extension',
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
                'summary' => 'Development of computer-based Decision Support System for vegetable production through needs assessment, design, development, deployment, and evaluation',
                'indicators' => 'Vegetable yields data, Socio-economic factors, Pest and disease incidence, Soil and climate conditions, Farmer knowledge and practices, Market demand and prices, Input availability and costs, Technological adoption, Environmental Impact, Government Policies and Support',
                'verification' => 'System testing reports, User feedback, Performance metrics, Yield improvement data',
                'assumptions' => 'Users have basic understanding of vegetable production and DSS technology. Reliable data on soil, weather, pests is available. Users have access to necessary technology infrastructure. DSS can be integrated into existing farming practices',
                'goal' => 'Enhance decision-making of vegetable growers by providing precise, timely, and actionable information',
                'purpose' => 'Enhance productivity, sustainability, and profitability by leveraging technology and data to support informed farming decisions',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Requirement gathering and initial planning', 'Month 1', 'Application fee, Data', 'N/A', 'PI and Co-PI', 'Research Permit, Project charter, Stakeholder analysis, Project plan'],
                ['Detailed analysis and feasibility study', 'Month 2', 'Detailed project plan', 'Laptops, wireless routers', 'PI, Co-PI, Research assistants', 'Requirements specification document'],
                ['System Design', 'Month 3', 'System architecture diagram', 'Laptops, wireless routers', 'PI, Co-PI, Research assistants', 'System architecture document, Database schema, UI mockups'],
                ['Development - Phase 1 (Core System)', 'Month 4-5', 'Database schema, API specs', 'Laptops, wireless routers', 'PI, Co-PI, Research assistants', 'Development environment, Coding standards, Version control'],
                ['Development - Phase 2 (User Interface)', 'Month 6', 'UI/UX prototypes', 'Laptops, Airtime, Transport', 'PI, Co-PI, Research assistants', 'Functional modules, Unit test cases'],
                ['Development - Phase 3 (Additional Features)', 'Month 7', 'Functional backend', 'Computer', 'PI, Co-PI, Research assistants', 'Functional modules, Code review reports'],
                ['Initial Testing', 'Month 8', 'Functional system', 'Testing environment', 'PI, Co-PI, Research assistants', 'Integrated system, Test reports'],
                ['System testing and feedback', 'Month 9', 'Real data', 'Testing environment', 'PI, Co-PI, Research assistants', 'User manual, Technical documentation'],
                ['User Training and Documentation', 'Month 10', 'Training materials', 'Training venue', 'PI, Co-PI', 'Deployment plan, System deployed'],
                ['Final Adjustments and Quality Assurance', 'Month 11', 'Test reports', 'QA environment', 'PI, Co-PI', 'Support logs, Maintenance schedule'],
                ['Deployment and Post-Deployment Support', 'Month 12', 'Performance metrics', 'Production environment', 'PI, Co-PI', 'Project review report, Lessons learned']
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
                ['Research permit application fee', 'Personnel and other costs', 1, 3000]
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
            
            echo "Successfully created Vegetable DSS Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Dr. Carolyne Cherotich\n";
            echo "Theme: Food Security\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: September 2024 - September 2025\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
