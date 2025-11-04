<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RadonSurveyProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Dr. Fred Wekesa Masinde',
                'email' => 'fmasinde@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '+254720832950',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Dr. Fred Wekesa Masinde (Email: fmasinde@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Natural Sciences%')->first();
            
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
                'highqualification' => 'PhD',
                'officephone' => '+254720832950',
                'cellphone' => '+254735212131',
                'faxnumber' => 'NIL',
                'researchtitle' => 'A Radiological Survey of Radon-222 in Underground Water Sources and Indoor Gas in Belgut Sub-County of Kericho County, Kenya',
                'commencingdate' => '2025-01-01',
                'terminationdate' => '2025-12-31',
                'objectives' => "General Objective:\nTo carry out a radiological survey of radon-222 in underground water sources and indoor gas of Belgut Sub-County of Kericho County, Kenya.\n\nSpecific Objectives:\n1. To determine the activity concentration levels of radon-222 in underground water sources using radium-226, thorium 232 and potassium-40 of Belgut sub-county\n2. To determine radiological parameters associated with radon-222\n3. To compare concentrations of radon-222 in boreholes, springs and hand dug well in Belgut\n4. Measure radon-222 concentration in indoor air of Belgut Sub-County",
                'hypothesis' => "Hypothesis:\nThe samples of underground water and indoor gas collected from Belgut Sub-County do not possess elevated levels of radon-222",
                'significance' => "Radon-222 gas is accountable for 42% source of radiation exposure to bio matter. Living organisms interact with radon in gaseous form or dissolved in drinking underground water. Radon-222 emanates from soils or rocks depending on their geological make up or mineral content.\n\nRadon-222 is a radioactive gas that just like other radionuclides when they interact with living cells they cause cell mutation which can lead to tumors or cancer cells in living organisms. Human beings interacting with radon-222 can ingest radon through drinking water with dissolved radon gas or by inhaling the gas.\n\nDue to this, there is need to assess the radiological exposure of the inhabitants of Belgut Sub-County of Kericho County to radon-222. The project aims to measure and calculate the concentration levels and their associated radiological parameters and hence ascertain that water consumed and air inhaled in the study area is safe from radon-222.\n\nThe findings will inform policy makers about the source of cancer cases since cancer is one of the main causes of deaths in Kenya today.",
                'ethicals' => "Permission will be sought from relevant authorities (University of Kabianga and other government authorities) before the project begins.",
                'expoutput' => "Expected Outputs:\n1. Results from the project will inform policy makers about the levels of radon-222 in underground water sources and indoor air which in turn will help conclude if the inhabitants of study area are in any danger or not due to their exposure to radon-222 through ingestion or inhalation of the gas\n2. The outcome will inform us if the increasing cancer cases in Kenya are as a result of radiological exposure of human beings to radon-222 or not. If yes, mitigation measures can be brought in and if not the rising cancer cases can be attributed to other factors\n3. Two publications will be published from this project in refereed journals and similarly presented in scientific conferences",
                'socio_impact' => "Socio-Economic Impact:\nThe results from the project will inform us about how the population of the study area is exposed to radon-222. If the levels are high then mitigation measures will be put in to protect the population from these high levels since other studies show that elevated levels of exposure to radon-222 leads to high risk of cancer.\n\nIf any population can be able to detect and prevent factors that can trigger cancer then the better because cancer is expensive to treat and equally difficult to cure if not detected early. This will reduce healthcare costs and improve quality of life for the community.",
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
                    'collaboratorname' => 'Dr. Charles Rotich',
                    'position' => 'Lecturer of Physics',
                    'institution' => 'University of Kabianga, Department of Mathematics, Actuarial & Physical Science',
                    'researcharea' => 'Nuclear and Radiation Physics',
                    'experience' => '8 years',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'collaboratorid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'collaboratorname' => 'Ms Joyce Chepkoech',
                    'position' => 'Post graduate student in Physics',
                    'institution' => 'University of Kabianga, Department of Mathematics, Actuarial & Physical Science',
                    'researcharea' => 'Nuclear and Radiation Physics',
                    'experience' => '1 year',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);

            // Publications
            $publications = [
                ['George Wangila Butiki, John Wanjala Makokha, Fred Wekesa Masinde and Conrad Khisa Wanyama', 2021, 'Annual Effective Dose From Radon-222 Concentration Levels in Underground Water in Bungoma South Sub-County, Kenya', 'International Journal of Engineering and Technology for Industrial Applications (ITEGAM-JETIA) v.7n.28 p-78-82'],
                ['C. K. Wanyama, F. W. Masinde, J. W. Makokha and S.M. Matsitsi', 2020, 'Estimation of Radiological Hazards due to Natural Radionuclides from Rosterman Gold Mine tailings, Lurambi, Kakamega County, Kenya', 'Radiation Protection Dosimetry, pp. 1-7'],
                ['Chepngetich Betty, Fred Wekesa Masinde, Enock Kipnoo Rotich and Conrad Khisa Wanyama', 2023, 'Radiological Hazard Levels of Construction Rocks Excavated from Kericho County, Kenya', 'International Journal of Engineering and Technology for Industrial Applications (ITEGAM-JETIA) v.7n.28 p-78-82'],
                ['C K Rotich, N O Hashim, M W Chege and C Nyambura', 2021, 'Naturally Occurring Radionuclides in Soil Samples of Bureti Sub-County of Kericho County Kenya', 'Radiation Protection Dosimetry, Volume 192, Issue 4, Pages 491-495'],
                ['Charles K Rotich, Nadir O Hashim, Margaret W Chege and Catherine Nyambura', 2020, 'Measurement of Radon Activity Concentration in Underground Water of Bureti Sub-County of Kericho County Kenya', 'Radiation Protection Dosimetry, Volume 192, Issue 1, Pages 56-60']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Nuclear and Radiation Physics',
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
                'summary' => 'Radiological survey involving mapping study area, sample collection from underground water sources and indoor air, laboratory analysis using sodium iodide detector, and data analysis',
                'indicators' => 'GPRS locations, Experimental measurement results, Analyzed data on graphs, Radiological levels of radon-222',
                'verification' => 'Getting GPRS locations, Experimental data, Analyzed data on graphs, Reports through seminars, conferences and publications',
                'assumptions' => 'Study locations are accessible, Samples can be collected and analyzed, Laboratory equipment is available and functional',
                'goal' => 'Assess the levels of radon-222 in underground water sources (boreholes, springs and hand dug wells) and in indoor gases in Belgut sub-county of Kericho County, Kenya',
                'purpose' => 'Inform policy makers about the radiological safety of the inhabitants of the study area regarding radon-222 exposure and its link to cancer',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Mapping out of the study area', 'Months 1-2', 'Obtaining GPRS locations', 'Transportation, camera, smartphone', 'Researchers and student', 'Ascertain availability of study locations'],
                ['Sample collection and preparation', 'Months 3-4', 'On site measurement and water collection', 'Camera, detergents, nitric acid, plastic bottles, CR-39 detectors', 'Researchers and student', 'Obtaining required samples for laboratory analysis'],
                ['Sample laboratory analysis', 'Months 5-6', 'Actual laboratory measurement', 'Laboratory, Sodium iodide detector, sodium hydroxide', 'Researchers and student', 'Radiological Experimental data'],
                ['Data analysis', 'Months 7-8', 'Analysis of experimental results', 'Computer', 'Researchers and student', 'Experimental results'],
                ['Report writing and publications', 'Months 9-10', 'Writing of articles', 'Computer', 'Researchers and student', 'Publication in refereed journals'],
                ['Dissemination of data', 'Months 11-12', 'Presentation of articles', 'Travelling', 'Researchers and student', 'Attending conferences']
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
                ['Plastic containers', 'Equipment and facilities', 20, 50],
                ['Plastic bottles', 'Equipment and facilities', 20, 50],
                ['Solid state track detectors (CR-39)', 'Equipment and facilities', 20, 3000],
                ['Survey meters', 'Equipment and facilities', 1, 600],
                ['Distilled water', 'Consumables', 20, 50],
                ['Detergents', 'Consumables', 5, 80],
                ['Nitric acid', 'Consumables', 2.5, 1200],
                ['Sodium hydroxide', 'Consumables', 1, 2000],
                ['Stickers/labels', 'Consumables', 2, 200],
                ['Permanent markers', 'Consumables', 3, 200],
                ['Transport to study area (thrice)', 'Travel and subsistence', 3, 10000],
                ['Subsistence and meals to study area', 'Travel and subsistence', 1, 30000],
                ['Transport to Kenyatta University', 'Travel and subsistence', 1, 20000],
                ['Subsistence and meals (Kenyatta University)', 'Travel and subsistence', 1, 40000],
                ['Sodium iodide detector (bench fee)', 'Personnel and other cost', 1, 50000],
                ['Publication fee', 'Personnel and other cost', 1, 25000],
                ['Conference facilitation', 'Personnel and other cost', 1, 25000],
                ['Stationary, Printing and data analysis', 'Personnel and other cost', 1, 10000]
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
            
            echo "Successfully created Radon Survey Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Dr. Fred Wekesa Masinde\n";
            echo "Theme: Natural Sciences\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: January 2025 - December 2025 (12 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
