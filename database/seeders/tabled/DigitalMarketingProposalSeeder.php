<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DigitalMarketingProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Dr. Lydia Langat',
                'email' => 'lchepkoech@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '0726989877',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Dr. Lydia Langat (Email: lchepkoech@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Education%')->orWhere('themename', 'LIKE', '%Entrepreneurship%')->first();
            
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
                'highqualification' => 'PhD (Marketing)',
                'officephone' => 'N/A',
                'cellphone' => '0726989877',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Digital Marketing Strategies for Kenyan Universities: Comparative Study of Public and Private Universities',
                'commencingdate' => '2025-01-01',
                'terminationdate' => '2025-10-31',
                'objectives' => "General Objective:\nTo compare the digital marketing strategies employed by public and private universities in Kenya and evaluate their impact on student enrollment and engagement.\n\nSpecific Objectives:\n1. To compare the digital marketing strategies employed by public and private universities in Kenya\n2. To evaluate the impact of digital marketing on student enrollment and engagement in Kenyan universities\n3. To identify the challenges and opportunities associated with digital marketing in Kenyan public and private universities",
                'hypothesis' => "Research Hypotheses:\nHo1: There is no statistically significant difference in the effectiveness of digital marketing strategies employed by private universities compared to public universities in Kenya\nHo2: There is no statistically significant positive impact of effective digital marketing strategies on student enrollment and engagement\nHo3: There is no statistically significant difference in the challenges faced in digital marketing between public and private universities",
                'significance' => "This study is significant as it addresses the critical gap in the adoption and effectiveness of digital marketing strategies in Kenyan universities. In an era where digital platforms dominate communication and engagement, universities must leverage these tools to remain competitive and accessible.\n\nThe research will provide valuable insights into how public and private universities can optimize digital marketing to enhance student enrollment, engagement, and institutional reputation. Kenya's higher education landscape is marked by increasing competition and a growing demand for innovative solutions to attract and retain students.\n\nThis study will examine disparities between public and private universities, highlighting challenges such as resource allocation, technological infrastructure, and organizational culture, and offer actionable recommendations for policy-making, capacity-building, and fostering a culture of innovation in higher education.\n\nUltimately, this research aligns with Kenya's broader development agenda by promoting educational accessibility, improving institutional efficiency, and preparing universities to compete in a globalized education market.",
                'ethicals' => "The researcher will adhere to the following ethical considerations:\n1. Prior permission will be sought from NACOSTI\n2. Information obtained will be kept confidential\n3. Identity of the respondents will not be revealed\n4. There will be no plagiarism\n5. Privacy of respondents will be respected\n6. No respondent will be coerced to answer questions by any means",
                'expoutput' => "Expected Outputs:\n1. Publications on the study area and expansion of available knowledge on the study field\n2. Practical recommendations for enhancing digital marketing strategies, including tailored approaches for public and private universities to increase visibility, attract students, and engage with stakeholders effectively\n3. Comparative analysis report on digital marketing effectiveness between public and private universities\n4. Policy recommendations for improving digital marketing in higher education",
                'socio_impact' => "Socio-Economic Impact:\n1. Enhanced access to higher education by improving digital marketing strategies that attract a wider and more diverse student population\n2. Increased student enrollment generating additional revenue for universities\n3. Job creation in digital marketing, content creation, and technology services sectors\n4. Enhanced visibility and competitiveness of Kenyan universities on the global stage\n5. Attraction of international students leading to increased foreign investment in education\n6. Bridging the digital divide between public and private universities\n7. Contributing to national economic growth and social mobility by creating opportunities for individuals from diverse socio-economic backgrounds",
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
                'collaboratorname' => 'Fred Odhiambo',
                'position' => 'Lecturer',
                'institution' => 'University of Kabianga',
                'researcharea' => 'Management Information Systems',
                'experience' => 'Over 3 years',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Publications
            $publications = [
                ['Odhiambo, F., Bett, A., & Langat, L.', 2024, 'E-learning and performance of public universities in the Rift Valley region, Kenya', 'EPRA International Journal of Economics, Business and Management Studies (EBMS), 11(8), 133-138'],
                ['Chepkwony, D., Langat, L., Chepkwony, P., & Langat, R.', 2023, 'Objective quality and price variation in tea marketing', 'International Journal of Latest Research in Humanities and Social Science, 6(10), 324-336'],
                ['Bii, M. K., Bett, A., Langat, L., & Kimeto, J.', 2024, 'Messenger marketing platforms and performance of tour operating companies in Kenya', 'East African Journal of Business and Economics, 7(1)'],
                ['Kipkirui Leonard, K., Naibei, I., & Langat, L.', 2022, 'Effect of internal control systems on financial performance of selected commercial banks in selected counties in Kenya', 'International Journal of Scientific and Research Publications, 12(3), 152']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Marketing and Business',
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
                'summary' => 'Quantitative research design to investigate effectiveness of digital marketing strategies in Kenyan universities targeting 30 universities (15 public and 15 private)',
                'indicators' => 'Publications, Number of questionnaires distributed, Reports generated, Returned questionnaires, Student enrollment rates, Engagement metrics',
                'verification' => 'Data collected and analyzed for pilot and final study, Progress reports submitted periodically, Achievements on set timelines, Publications',
                'assumptions' => 'Sufficient funds will be available, Study will be completed on time, Tools and equipment needed will be available, All stakeholders will cooperate',
                'goal' => 'Compare digital marketing strategies between public and private universities and evaluate their impact on student enrollment and engagement',
                'purpose' => 'Provide comparative analysis and practical recommendations for universities to enhance digital marketing efforts, improving visibility, stakeholder engagement, and competitiveness',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Gap identification/design', 'January 2025', 'Accessing relevant articles', 'Library', 'Principal researchers', 'Gap Identified'],
                ['Questionnaire design', 'April 2025', 'Extensive Research', 'Relevant information', 'Principal researchers', 'Questionnaire'],
                ['Pilot test', 'May 2025', 'Labour', 'Questionnaires', 'Principal researchers and 1 assistant', 'Raw data'],
                ['Data Collection', 'July 2025', 'Administering questionnaires', 'Questionnaires', 'Principal researchers and 3 assistants', 'Raw data'],
                ['Data analysis', 'August 2025', 'Entering, coding and analyzing', 'SPSS software', 'Principal researchers', 'Findings, Tables, Graphs'],
                ['Report writing', 'September 2025', 'Writing of report', 'Laptop, Internet, computers', 'Principal researchers and 3 assistants', 'Report'],
                ['Publications and presentations', 'October 2025', 'Publications', 'Publication fee', 'Principal researchers', 'Published journals, Final report']
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
                ['NACOSTI permit', 'Equipment and facilities', 1, 2500],
                ['Subsistence during pilot study', 'Equipment and facilities', 3, 10000],
                ['Questionnaires for pilot study', 'Equipment and facilities', 400, 5],
                ['Printing questionnaires', 'Consumables', 400, 60],
                ['Data analysis', 'Consumables', 1, 32700],
                ['Printing and binding final document', 'Consumables', 8, 2400],
                ['Travel expenses - Nairobi', 'Travel and subsistence', 4, 3750],
                ['Travel expenses - Mombasa', 'Travel and subsistence', 2, 3000],
                ['Travel expenses - Kilifi', 'Travel and subsistence', 1, 8000],
                ['Travel expenses - Taita Taveta', 'Travel and subsistence', 1, 6000],
                ['Travel expenses - Garrissa', 'Travel and subsistence', 1, 7000],
                ['Travel expenses - Meru', 'Travel and subsistence', 1, 5000],
                ['Travel expenses - Tharaka Nithi', 'Travel and subsistence', 1, 5000],
                ['Travel expenses - Embu', 'Travel and subsistence', 1, 4000],
                ['Travel expenses - Kitui', 'Travel and subsistence', 1, 5000],
                ['Travel expenses - Machakos', 'Travel and subsistence', 1, 4000],
                ['Travel expenses - Nyeri', 'Travel and subsistence', 1, 6000],
                ['Travel expenses - Kirinyaga', 'Travel and subsistence', 1, 3000],
                ['Travel expenses - Muranga', 'Travel and subsistence', 1, 4000],
                ['Travel expenses - Kiambu', 'Travel and subsistence', 1, 3000],
                ['Travel expenses - Uasingishu', 'Travel and subsistence', 2, 1500],
                ['Travel expenses - Laikipia', 'Travel and subsistence', 1, 2000],
                ['Travel expenses - Nakuru', 'Travel and subsistence', 1, 800],
                ['Travel expenses - Narok', 'Travel and subsistence', 1, 800],
                ['Travel expenses - Kakamega', 'Travel and subsistence', 1, 2000],
                ['Travel expenses - Kisii', 'Travel and subsistence', 1, 2000],
                ['Travel expenses - Bungoma', 'Travel and subsistence', 1, 4000],
                ['Travel expenses - Kisumu', 'Travel and subsistence', 2, 1500],
                ['Travel expenses - Migori', 'Travel and subsistence', 1, 2000],
                ['Research personnel allowance', 'Personnel and other cost', 3, 24000],
                ['Publications', 'Personnel and other cost', 2, 14000]
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
            
            echo "Successfully created Digital Marketing Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Dr. Lydia Langat\n";
            echo "Theme: Education and Marketing\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: January 2025 - October 2025 (10 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
