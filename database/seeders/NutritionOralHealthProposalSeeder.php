<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NutritionOralHealthProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Dr. Florence Wandia Munyao',
                'email' => 'fmunyao@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '0729947506',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Dr. Florence Wandia Munyao (Email: fmunyao@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Health%')->first();
            
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
                'highqualification' => 'PhD (Human Nutrition and Dietetics)',
                'officephone' => '0729947506',
                'cellphone' => '0729947506',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Nutrition and Oral Hygiene Education Intervention on Oral Health among Primary School Children aged 6-11 Years in Langas and Ngomongo Slums in Eldoret Town, Uasin Gishu County',
                'commencingdate' => '2025-09-01',
                'terminationdate' => '2026-07-31',
                'objectives' => "General Objective:\nTo promote acquisition of appropriate Nutritional and Oral Health Knowledge, Attitudes and Dietary Practice among school pupils which is crucial to prevention of Dental diseases.\n\nSpecific Objectives:\n1. To determine the dietary patterns of primary school children aged 6-11 Years in Langas and Ngomongo slums in Uasin Gishu County\n2. To determine the level of nutritional knowledge and attitudes on oral health among primary school children aged 6-11 Years in Langas and Ngomongo slums\n3. To determine the attitudes on oral health among primary school children aged 6-11 Years in Langas and Ngomongo slums\n4. To determine Oral health practices among primary school children aged 6-11 Years in Langas and Ngomongo slums\n5. To design and implement a nutrition and oral health education program on knowledge, attitudes and dietary patterns in relation to oral health\n6. To establish the effect of nutrition and oral health education on the nutrition knowledge, attitudes and dietary patterns among primary school children\n7. To establish the effect of nutrition and oral health education on Oral Health Practices among primary school children aged 6-11 Years",
                'hypothesis' => "Research Questions:\n1. What are the dietary patterns of primary school children aged 6-11 years in Uasin Gishu County?\n2. What is the level of nutritional knowledge and the attitudes towards oral health among primary school children aged 6-11 years in Uasin Gishu County?\n3. What are the oral health practices among primary school children aged 6-11 years in Uasin Gishu County?\n4. How can a nutrition and oral health education program be designed and implemented to improve knowledge, attitudes, and dietary patterns related to oral health among primary school children aged 6-11 years?\n5. What is the effect of nutrition and oral hygiene education on the nutrition knowledge, attitudes, and dietary patterns among primary school children aged 6-11 years?",
                'significance' => "The results of this study could help Ministries of Health and Education, as well as other interested parties, create nutrition and Oral health initiatives that would enhance children's overall health. The study should add to the ongoing body of knowledge regarding the benefits of oral health education and nutrition on lowering childhood dental caries and oral lesions as well as the related NCDs.\n\nThe study significantly supports various sustainable development goals (SDGs), such as target 2.1 of SDG 2 on Zero Hunger, targets 3.4 and 3.9 of SDG 3 on Good Health and Well-being, target 4.7 of SDG 4 on Quality Education, targets 6.1 and 6.2 of SDG 6 on Clean Water and Sanitation, and target 10.2 of SDG 10 on reduced Inequalities.\n\nInappropriate dietary habits among primary school pupils including excessive consumption of calorific meals and sweet drinks while consuming less fruits and vegetables is a major concern. Irregular provision of balanced meals in schools and frequent snacking predisposes pupils to poor nutrition. Preventive measures are required as prevention offers a less expensive approach as opposed to treatment.",
                'ethicals' => "Ethical clearance to conduct the study was sought and obtained from University of Eastern Africa, Baraton Ethical Review committee, while a research permit was obtained from the National Commission for Science, Technology and Innovation (NACOSTI).\n\nThe permission to carry out research in primary schools will be obtained from the County Director of Education, Uasin Gishu County and head teachers of the sampled schools.\n\nWritten consent will be sought from the parents of the participants. Voluntary informed assent of the subjects will be sought from the participants. The participants will be assured of confidentiality and anonymity of information.",
                'expoutput' => "Expected Outputs:\n1. Recruited participants will acquire knowledge on nutrition in relation to Oral Health\n2. Participating schools will receive a report of the research findings\n3. Increased awareness of health risks of poor oral health practices\n4. Increased knowledge of importance of healthy eating behaviour\n5. Enhanced practice of healthy eating and oral health patterns\n6. Increased awareness of using schools as crucial settings for implementation of nutrition and oral health education programs to prevent dental diseases among primary school pupils\n7. Publications in peer-reviewed journals\n8. Conference presentations",
                'socio_impact' => "Socio-Economic Impact:\n1. Improved oral health outcomes among primary school children in slum areas\n2. Reduced healthcare costs through prevention of dental diseases\n3. Enhanced school attendance and academic performance due to better health\n4. Empowerment of communities through health education\n5. Contribution to achieving multiple SDGs including Zero Hunger, Good Health, Quality Education, and Reduced Inequalities\n6. Long-term reduction in NCDs related to poor nutrition and oral health\n7. Creation of sustainable health promotion models for resource-limited settings",
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
                'collaboratorname' => 'William Kitagwa',
                'position' => 'HOD and Lecturer',
                'institution' => 'University of Kabianga, Dept of Public Health',
                'researcharea' => 'Public Health',
                'experience' => 'Over 20 Years',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Publications
            $publications = [
                ['Florence Birir, Joseph Choge', 2021, 'Clinical Perspectives of Malnutrition. Chapter 1. Challenges Associated With Malnutrition: A Clinical Perspective', 'Guidelines For Health Workers and Students. ISBN 978-620-3-86983-5'],
                ['Florence Birir, Joseph Choge', 2021, 'Clinical Perspectives of Malnutrition. Chapter 2. Clinical Presentations of Malnutrition', 'Guidelines For Health Workers and Students. ISBN 978-620-3-86983-5'],
                ['Florence Birir, Joseph Choge', 2021, 'Clinical Perspectives of Malnutrition. Chapter 3. Classification Of Malnutrition and Its Importance', 'Guidelines For Health Workers and Students. ISBN 978-620-3-86983-5'],
                ['Wandia, F.B; Sophie, O; Ogada, I.', 2020, 'Physical and Nutrition Education Intervention Improves Body Weight Status of Adolescents in Uasin Gishu County, Kenya: A Cluster-Randomized Controlled Trial', 'Current Research in Nutrition and Food Science; 8(1)'],
                ['Wandia, F.B; Sophie, O; Ogada, I.', 2020, 'Effect of Nutrition and Physical Education on Adolescents Physical Activity Levels, Nutrition Knowledge, Attitudes and Dietary Practices', 'J Food Sci Nutr Res; 3 (2): 061-082']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Nutrition and Public Health',
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
                'summary' => 'School-based intervention study to assess and improve nutrition knowledge, attitudes and oral health practices among primary school children aged 6-11 years',
                'indicators' => 'Individual Dietary Diversity Score, Knowledge and attitude levels, Changed dietary practices, Oral health practices improvement',
                'verification' => 'Baseline survey using KAP questionnaires, Follow-up surveys, Endline/Exit surveys',
                'assumptions' => 'Parents will give consent, Study participants will give assent, Schools will allow time and space for survey, Study subjects will not be lost to follow-up, Subjects are willing to embrace change in attitude and practices',
                'goal' => 'Promote acquisition of appropriate Nutritional and Oral Health Knowledge, Attitudes and Dietary Practice among school pupils for prevention of Dental diseases',
                'purpose' => 'Establish the effect of a school focused intervention on Oral Health and Dietary practices among primary school pupils in Uasin Gishu County',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Recruitment of study participants and Baseline Survey', 'Months 1-4', 'Informed consent forms, Assent forms, Questionnaires', 'Schools', 'Principal researcher and Research assistants', 'Recruited participants, Baseline data collected'],
                ['Nutrition Education Intervention', 'Months 5-6', 'LCD projector, Exercise books, Teaching materials', 'Classrooms', 'Principal researcher and Research assistants', 'Increased awareness and knowledge'],
                ['Follow up survey', 'Months 7-8', 'KAP questionnaires', 'Schools', 'Principal researcher and Research assistants', 'Follow-up data collected'],
                ['End point survey on oral health and dietary practices', 'Month 9', 'KAP questionnaires', 'Schools', 'Principal researcher and Research assistants', 'Endline data collected'],
                ['Data analysis, interpretation and presentation', 'Month 10', 'Statistical software', 'Computer', 'Principal researcher and Biostatician', 'Analyzed data and findings'],
                ['Data dissemination (Reports, conferences and publications)', 'Months 10-11', 'Publication fees', 'Conference venues', 'Principal researcher', 'Published papers and reports']
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
                ['Food guide Pyramid charts and posters', 'Equipment and facilities', 6, 200],
                ['Classroom dusters', 'Equipment and facilities', 3, 100],
                ['Attendance register', 'Consumables', 3, 150],
                ['Piloting', 'Consumables', 1, 2000],
                ['General stationery', 'Consumables', 1, 7000],
                ['Manila papers', 'Consumables', 20, 20],
                ['Masking tape 2"', 'Consumables', 1, 150],
                ['Dustless chalk', 'Consumables', 3, 50],
                ['Training Manuals & Handouts', 'Consumables', 1, 4000],
                ['Toothbrushes & tooth pastes', 'Consumables', 1140, 100],
                ['Conferences', 'Consumables', 2, 15000],
                ['Publications', 'Consumables', 2, 20000],
                ['Pilot Testing facilitation', 'Consumables', 1, 2000],
                ['Printing support & communication', 'Consumables', 1, 2000],
                ['Miscellaneous', 'Consumables', 1, 3350],
                ['Principal Investigator', 'Personnel and other cost', 1, 15000],
                ['Research Assistants', 'Personnel and other cost', 2, 15000],
                ['Data Analyst', 'Personnel and other cost', 1, 30000],
                ['Teacher Stipend', 'Personnel and other cost', 6, 3000]
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
            
            echo "Successfully created Nutrition and Oral Health Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Dr. Florence Wandia Munyao\n";
            echo "Theme: Health & Nutrition\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: September 2025 - July 2026 (10 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
