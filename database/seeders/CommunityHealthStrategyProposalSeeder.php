<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CommunityHealthStrategyProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'James Opondo Ouma',
                'email' => 'j.ouma@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '0702329507',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: James Opondo Ouma (Email: j.ouma@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Health%')->orWhere('themename', 'LIKE', '%Community Development%')->first();
            
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
                'highqualification' => 'MPH',
                'officephone' => 'N/A',
                'cellphone' => '0702329507',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Evaluating the effectiveness of the community health strategy to develop additional guidelines for improving its capacity for prevention of child under-nutrition in Seme Sub-County, Kenya',
                'commencingdate' => '2023-09-01',
                'terminationdate' => '2024-06-30',
                'objectives' => "General Objective:\nTo evaluate the capacity of Community Health Strategy (CHS) for prevention of child under-nutrition, and propose new guidelines to improve this capacity.\n\nSpecific Objectives:\n1. To describe factors linked to the implementation of the community health strategy for prevention of child under-nutrition so as to identify gaps in the implementation\n2. To determine the effectiveness of the community health strategy in the prevention of child under-nutrition\n3. To assess how the understanding of child nutrition and health policies by sub-county health management teams and relate them to the CHS outcomes on child nutrition\n4. To develop guidelines for an improved community health strategy that would better prevent child under-nutrition compared to the current CHS implementation guidelines",
                'hypothesis' => "Hypothesis:\nThe exposure variable will be lack of Community Health Strategy (CHS) activation and outcome variable will be child under-nutrition.\n\nNull hypothesis (H0): The burden of child under-nutrition will be same regardless of CHS activation (R0 - R1 = 0)\n\nAlternative hypothesis (HA): The burden will differ due to CHS activation (R0 - R1 ≠ 0)\n\nWhere R0 represents the burden of child under-nutrition in areas where CHS has been activated and R1 represents burden where CHS is not yet activated",
                'significance' => "Child under-nutrition is a public health problem that needs urgent intervention. Nutrition-specific programs against child under-nutrition has had little progress. Increasing evidence show that etiology of under-nutrition is complex, and ecological with nutrition-sensitive programs proposed as better suited interventions.\n\nThe Kenyan Community Health Strategy, a primary health care model, has offered opportunities to integrate these approaches with the promise on improvements in child nutritional outcomes, especially in rural and underserved communities. However, CHS has not been evaluated for the effectiveness in prevention of child malnutrition and the paucity of evidence to support this approach hampers precise policy formulation and action.\n\nThis study will evaluate the effectiveness of the CHS in the prevention of child under-nutrition in the rural health care setting of Seme Sub-County, Kenya against the gold standard as contained in the CHS program vision. It will improve CHS guidelines for better prevention of child under-nutrition.",
                'ethicals' => "The basis of ethical consideration is that research is done to benefit humanity. However, the conduct of research involves significant risks that if not checked may obliterate the benefits. Guidelines against the risks exist on conduct of research with human participants, including the Nuremberg Code of 1947, the Helsinki Declaration of 1964 and the Belmont Report of 1979.\n\nPrinciples from these guidelines are: respect for persons, beneficence, non-maleficence, and justice.\n\n1. Respect will be achieved by the informed consent processes\n2. Beneficence via maximizing the benefits of CHS to society through the new guidelines\n3. Non-maleficence by not seeking sensitive data from participants and keeping the interviews and discussions short\n4. Justice will be achieved by making the benefits accrued from the study go to the health system, especially CHS and the service beneficiaries\n\nThe proposal is approved by accredited Research and Ethics Committee of the University of Eastern Africa, Baraton for compliance with these ethics principles.",
                'expoutput' => "Expected Outputs:\n1. Evidence base to improve and better inform the implementation of the community health strategy for the prevention of child undernutrition\n2. Improved child survival through prevention of undernutrition\n3. Draft CHS guideline for improved implementation\n4. Policy brief documents\n5. Research publications in peer-reviewed journals\n6. Conference presentations\n7. Dissertation published in libraries and digital repositories",
                'socio_impact' => "Socio-Economic Impact:\n1. Improved child survival through prevention of undernutrition or achieving better nutrition\n2. Better child development leading to economic empowerment and community development\n3. Children who suffer and survive under-nutrition usually do not reach their full potential in adulthood\n4. Prevention of the cycle where undernourished children have higher probability of achieving less in school\n5. Prevention of low productivity at work in adulthood and earning less\n6. Breaking the cycle of poverty where childhood under-nutrition linked disadvantage is passed to their children\n7. Enhanced community health outcomes through improved CHS implementation",
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
                'collaboratorname' => 'Prof. Theresa Sheila Makoboto-Zwane',
                'position' => 'Professor',
                'institution' => 'University of South Africa, Department of Health Studies',
                'researcharea' => 'Health Science, Public Health, Mental Health, HIV & AIDS, Early Childhood Development',
                'experience' => 'Professor with extensive experience in public health research',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Publications
            $publications = [
                ['Nyang\'au, I., Asweto, C. O., Ouma, P., & Ouma, J.', 2021, 'Utilization of Individual Birth Plan Among Women Attending Postnatal Clinic at Jaramogi Oginga Odinga Teaching and Referral Hospital, Kisumu Kenya', 'The Columbia University Journal of Global Health, 10(2)'],
                ['Muga R, Ajwang\' A, Ouma J, et al.', 2020, 'Efficacy of the Nutritional Supplement, EvenFlo, in the Management of Sickle Cell Disease: A Randomized Controlled Trial', 'Nursing & Health Sciences Research Journal. 3(1):35-45'],
                ['Nziok, J. M., Korir, A. J., Ombaka, J. H., Ouma, J. O., & Onyango, R. O.', 2018, 'Effect of A Community Health Worker led Intervention on Skilled Birth Care in Rural Mwingi West Sub-County, Kenya', 'African journal of reproductive health, 22(3), 59–70'],
                ['Budambula, V., Matoka, C., Ouma, J., et al.', 2018, 'Socio-demographic and sexual practices associated with HIV infection in Kenyan injection and non-injection drug users', 'BMC public health, 18(1), 193'],
                ['Natarajan, J., & Mokoboto-Zwane, S.', 2022, 'Health-related Quality of Life and Domain-specific Associated Factors among Patients with Type2 Diabetes Mellitus in South India', 'The review of diabetic studies: RDS, 18(1), 34–41']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Public Health',
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
                'summary' => '3-phase mixed-methods study: Phase 1 (qualitative phenomenology and quantitative retrospective longitudinal), Phase 2 (systematic policy review), Phase 3 (guideline development using Delphi Method)',
                'indicators' => 'Description of lived experiences, Medical records data, Policy review notes, Expert panel meetings, Stakeholder validation meetings',
                'verification' => 'Interview and focus group records, Hospital datasets, Annotated policy review notes, Attendance lists, Meeting minutes, Draft guidelines',
                'assumptions' => 'Health workers will consent to interviews, Hospital management will consent for data use, Access to all policy documents, Participants will consent to attend meetings',
                'goal' => 'Evaluate the capacity of Community Health Strategy for prevention of child under-nutrition and propose new guidelines',
                'purpose' => 'Propose improved strategy guidelines to better facilitate prevention of child under-nutrition in Seme Sub-County based on evaluation of existing CHS',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Phase 1 Part 1 - Interviews and focus group discussions', 'September-December 2023', 'In-depth interviews, Focus groups', 'Voice recording devices', 'Researcher and research assistants', 'Audio records of interviews'],
                ['Phase 1 Part 2 - Medical Record reviews', 'September-December 2023', 'Medical charts of children', 'Hospital medical records', 'Researcher and research assistants', 'Datasets of child medical records'],
                ['Policy review', 'September-December 2023', 'Health policy documents', 'Laptops', 'Researcher with experts', 'Annotated review notes'],
                ['Guideline development', 'November 2023-February 2024', 'Experts and stakeholders', 'Boardrooms', 'Researcher and experts', 'Draft CHS guideline, Policy brief'],
                ['Research publications', 'January-April 2024', 'Reference resources, reviewers', 'Laptop computers', 'Researcher', 'Research paper, Policy brief'],
                ['Project closeout', 'May-June 2024', 'Resource accounting', 'Statement of accounts', 'Researcher', 'Approved expenses account']
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
                ['Voice Recorders', 'Equipment and facilities', 10, 200],
                ['NVivo Software', 'Equipment and facilities', 40, 1500],
                ['SAS Software', 'Equipment and facilities', 16, 1000],
                ['Tents', 'Equipment and facilities', 18, 3000],
                ['Flipcharts stand', 'Equipment and facilities', 1, 15000],
                ['Flipchart paper', 'Consumables', 6, 1750],
                ['Printing paper', 'Consumables', 2, 800],
                ['Marker Pens', 'Consumables', 10, 125],
                ['Printing', 'Consumables', 1, 6400],
                ['Communication', 'Consumables', 1, 8750],
                ['Potable water (500ml pack)', 'Consumables', 240, 700],
                ['Stakeholder meeting meals', 'Travel and subsistence', 10, 875],
                ['Research Assistants', 'Personnel and other cost', 40, 1500],
                ['Expert Panelist Fees', 'Personnel and other cost', 3, 10000],
                ['CHV Travel', 'Personnel and other cost', 24, 500],
                ['Researcher Travel (petrol)', 'Personnel and other cost', 40, 200]
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
            
            echo "Successfully created Community Health Strategy Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: James Opondo Ouma\n";
            echo "Theme: Health & Nutrition / Community Development\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: September 2023 - June 2024 (10 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
