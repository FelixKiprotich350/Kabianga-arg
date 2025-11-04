<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeaFarmersInformationProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Beatrice Cheptoo Korir',
                'email' => 'bkorir939@gmail.com',
                'password' => Hash::make('password123'),
                'phonenumber' => '0720107939',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Beatrice Cheptoo Korir (Email: bkorir939@gmail.com, Password: password123)\n";
            
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
                'highqualification' => 'Degree',
                'officephone' => 'N/A',
                'cellphone' => '0720107939',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Access and Utilization of Agricultural Information for Enhancement of Productivity among Tea Farmers in Kericho County, Kenya',
                'commencingdate' => '2025-08-01',
                'terminationdate' => '2026-06-30',
                'objectives' => "General Objective:\nThe main goal of this study is to determine the access and utilization of agricultural information for enhancement of productivity among tea farmers in Kericho County.\n\nSpecific Objectives:\n1. To assess the importance of agricultural information to tea farmers in Kericho County\n2. To determine the impact of agricultural information by tea farmers of Kericho County\n3. To identify challenges faced by tea farmers in accessing and utilizing agricultural information\n4. To recommend strategies for improving the access and utilization of agricultural information to enhance tea farming productivity",
                'hypothesis' => "Research Questions:\n1. Are tea farmers of Kericho accessing and using agricultural information?\n2. Does the agricultural information accessed and utilized impact tea farming activities in Kericho County?\n3. Do the farmers face any challenges when accessing and using information in Kericho County?",
                'significance' => "The study will help the farmers improve on agricultural practices, because they will access and use relevant and up-to-date information thus greatly enhancing tea farming. Information will make them informed about the latest farming techniques, advancements in tea cultivation, pest and disease management strategies, soil health maintenance and effective use of fertilizers and pesticides.\n\nThe tea associations will be able to pinpoint areas in which they want to improve to make the lives of farmers better. Tea associations are closer to the farmers and therefore can take advantage of the weaknesses that are hindering farmers from accessing and utilizing information to better their practice.\n\nTea farmers will become aware of the trends in the market, demand-supply, and price changes to allow them to make better decisions on when and where they can market their produce. Access and usage of information empower the farmers to bargain for reasonable prices, find potential buyers or markets and map the consumers' preference.\n\nThe county government of Kericho will benefit from the study by acquiring an awareness of the strengths and weaknesses in the access and usage of information of the Kericho county tea farmers, which might be the same to other farmers and come up with policies which will motivate tea farmers to utilize and acquire information.",
                'ethicals' => "The researcher seeks to uphold high standards of ethical conduct in research, as informed by the University's values and norms. A letter of clearance from the institution, acting as a mandate to venture into the field, would be obtained before collecting any data. It would be relied on in making research clearance with respect to the National Commission on Science, Technology, and Innovation (NACOSTI), a state body responsible for regulating research in the country.\n\nUpon clearance, NACOSTI clearance will permit the researcher to continue with data collection from respondents as well as making contact with respondents in a legal way. In collecting data, informed consent from all respondents will be requested. That is, respondents will be fully informed regarding the purpose for conducting the study, nature of participation, and right to withdraw from participation at will without any form of retribution.\n\nFurthermore, all information collected would be kept confidential. Personal identifiers would be removed or anonymized, with data being stored safely not for any unauthorized individuals. Best effort would be made on the part of the researcher to uphold confidentiality as well as dignity of the participants while using information collected for academic purposes only. These are all done with a view towards ensuring the rights as well as well-being of the participants, adhering to research ethics, as well as ensuring credibility as well as integrity in research.",
                'expoutput' => "The findings of the study are anticipated to demonstrate that access to timely, accurate, and trusted agricultural information significantly influences the adoption of good agricultural practices, particularly in pest management, fertilizer application, and market-informed decision-making.\n\nExpected outputs include:\n1. Research report on access and utilization of agricultural information among tea farmers\n2. Published manuscript in peer-reviewed journal\n3. Conference presentation\n4. Policy brief for stakeholders\n5. Workshop with tea farmers and extension officers",
                'socio_impact' => "We anticipate the study enable farmers to:\n1. Increase crop yields through improved agricultural practices\n2. Increase income levels through better market information\n3. Manage pests and know how to control diseases effectively\n4. Adopt new farming technologies\n5. Make informed decisions on fertilizer application\n6. Access market information for better pricing\n7. Improve overall tea farming productivity in Kericho County",
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
                'collaboratorname' => 'Dr. Ronald Bituka',
                'position' => 'Lecturer',
                'institution' => 'Kenyatta University',
                'researcharea' => 'Information Science',
                'experience' => 'More than 5 years',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Publications
            $publications = [
                ['Njagi, P., Gitau, N. & Bituka, R.', 2024, 'Exploring Collaborative Partnership in Research Data Management: A study of Selected University Libraries in Kenya', 'Eastern Africa Journal of Contemporary Research, 4(1), 13–21'],
                ['Bituka, R., & Chemulwo, M. J.', 2021, 'Assessing Effects of Librarians\' Emotional Intelligence on Quality Service at the MOI University and United States International University Libraries', 'International Journal of Research in Library Science, 7(4), 244'],
                ['Njagi, P.R., Njoroge, G. & Bituka, R.', 2024, 'Understanding the legal framework in Research Data Management: a study of selected academic libraries in Kenya', 'Regional Journal of Information and Knowledge Management, 9(1),93-102']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Information Science',
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
                'summary' => 'Mixed-methods research approach combining qualitative and quantitative methods',
                'indicators' => 'Sources of information among tea farmers, Digital literacy among tea farmers, Gadgets used by farmers to access agricultural information',
                'verification' => 'Availability of information materials in tea buying centers, Information disseminated by agricultural extension officers',
                'assumptions' => 'The willingness of tea farmers to adopt new technologies',
                'goal' => 'Determine the access and utilization of agricultural information for enhancement of productivity among tea farmers in Kericho County',
                'purpose' => 'Enable farmers to improve agricultural practices through access to timely and accurate information',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Finalize study design and protocol', 'August 2025', 'Protocol document', 'None', 'PI', 'Protocol document'],
                ['Obtain ethical approval and research permit', 'End of August 2025', 'Approval and permit fees', 'IREC and NACOSTI', 'PI', 'Approval and authorization documents'],
                ['Recruit and train research assistants', 'September 2025', 'Travel and lunch fees', 'Tea Buying Centers', 'Research team', 'Qualified research assistants'],
                ['Pretest questionnaires', 'September 2025', 'Travel and lunch fees', 'Tea Buying Centers', 'Research team', 'Valid and reliable questionnaires'],
                ['Conduct pilot study', 'October 2025', 'Travel and lunch fees', 'Tea Buying Centers', 'Research assistants and team', 'Valid and reliable questionnaires'],
                ['Data collection', 'October-November 2025', 'Data collection fees', 'Tea Buying Centers', 'Research assistants and team', 'Data collected'],
                ['Data analysis and report writing', 'December 2025-January 2026', 'Analysis fees', 'None', 'Research team', 'Manuscript draft'],
                ['Disseminate findings through extension officers', 'February 2026', 'Workshop fees', 'None', 'Research team', 'Workshop conducted'],
                ['Disseminate findings through conference', 'March 2026', 'Conference fees', 'None', 'PI', 'Conference presentation'],
                ['Publish article', 'April-July 2026', 'Publication fees', 'None', 'Research team', 'Published manuscript']
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
                ['IREC fees', 'Equipment and facilities', 1, 5000],
                ['NACOSTI fees', 'Equipment and facilities', 1, 2000],
                ['Lunch per diem during training', 'Training and pilot', 24, 500],
                ['Lunch per diem for research assistants', 'Training and pilot', 20, 500],
                ['Travel costs during training', 'Training and pilot', 48, 137.5],
                ['Transcription fees', 'Data collection', 60, 500],
                ['Data analysis fees', 'Data collection', 1, 70000],
                ['Lunch per diem for research team', 'Data collection', 40, 500],
                ['Travel costs during data collection', 'Data collection', 240, 100],
                ['Questionnaire and consent form printing', 'Data collection', 4050, 10],
                ['Community Health Unit workshop fees', 'Dissemination', 1, 9000],
                ['Conference fees', 'Dissemination', 1, 33400],
                ['Manuscript publication fees', 'Dissemination', 1, 20000]
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
            
            echo "Successfully created Tea Farmers Information Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Beatrice Cheptoo Korir\n";
            echo "Theme: Food Security\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: August 2025 - June 2026\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
