<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MosquitoRepellentProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
        DB::table('users')->insert([
            'userid' => $userId,
            'pfno' => 'PF' . rand(1000, 9999),
            'name' => 'Prof. Joyce Kiplimo',
            'email' => 'jkiplimo@kabianga.ac.ke',
            'password' => Hash::make('password123'),
            'phonenumber' => '0722281416',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $user = DB::table('users')->where('userid', $userId)->first();
        echo "Created user: Prof. Joyce Kiplimo (Email: jkiplimo@kabianga.ac.ke, Password: password123)\n";
        
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
            'officephone' => 'N/A',
            'cellphone' => '0722281416',
            'faxnumber' => 'N/A',
            'researchtitle' => 'Mosquito Repellant Jelly from Essential Oil Extracted from Selected Medicinal Plants',
            'commencingdate' => '2025-06-01',
            'terminationdate' => '2026-06-01',
            'objectives' => "General Objective:\nThe main goal of this research is to develop a safe, effective, and sustainable mosquito-repellent jelly using essential oil from plant material.\n\nSpecific Objectives:\n1. To extract essential oil from selected plant material\n2. To analyze the composition of the extracted essential oil using GC-MS\n3. To formulate a stable jelly using the extracted essential oils\n4. To evaluate the jelly's repellent effectiveness against mosquitoes under controlled conditions\n5. To determine how long the jelly remains effective and whether preservatives are needed",
            'hypothesis' => 'N/A',
            'significance' => "Significance:\nMosquitoes are vectors for diseases such as malaria, developing an effective plant-based repellent can help reduce mosquito bites and the spread of disease, especially in regions with high mosquito populations. Many commercial repellents contain synthetic chemicals, which some users prefer to avoid due to potential skin irritation and environmental concerns. Using plant essential oils offers a more natural alternative.\n\nThe selected plants can be cultivated sustainably, providing an eco-friendly solution compared to synthetic repellents. While sprays and lotions are common, a jelly formulation offers advantages such as longer-lasting effects, easier application, and better adherence to the skin. The production and commercialization of plant-based mosquito repellents can create business opportunities, especially in regions where the plant is naturally abundant.\n\nJustification:\nStudies indicate that the selected plant contains bioactive compounds with insect-repelling properties, making it a viable ingredient for mosquito repellents. With increasing interest in herbal and organic products, a jelly-based repellent from plant extracts can attract consumers looking for safe and natural insect protection. Testing the jelly formulation ensures that it is both effective in repelling mosquitoes and safe for skin application. Developing a properly formulated repellent helps meet cosmetic and health product regulations, ensuring it can be marketed safely. This study can pave the way for additional investigations into optimizing formulations, increasing efficacy, and understanding the full spectrum of mosquito-repelling compounds in the selected plants.",
            'ethicals' => "1. Ensure the jelly formulation is safe for human use, avoiding harmful or allergenic ingredients\n2. Conduct ethical testing procedures, prioritizing non-invasive and non-harmful methods for evaluating efficacy\n3. Ensure transparency in labelling, informing consumers about the ingredients, potential side effects, and safe usage guidelines\n4. Selected plant will be harvested responsibly, avoiding overharvesting and promoting sustainable cultivation methods\n5. Ensure the production process does not lead to environmental degradation or biodiversity loss\n6. Assess whether the repellent could negatively impact non-target insect species, such as pollinators\n7. Compliance with cosmetic and insect repellent regulations to protect consumers from unsafe formulations\n8. Promote community involvement by supporting local industries and creating economic opportunities through ethical sourcing and production\n9. Consider collaborating with scientific researchers, health organizations, and environmental experts to ensure responsible development and distribution\n10. Obtain informed consent from participants in product testing, ensuring they fully understand the risks and benefits\n11. Follow ethical guidelines for laboratory and field testing, particularly when studying the impact on mosquitoes and human safety\n12. Data integrity will be maintained by accurately reporting results without manipulation or bias",
            'expoutput' => "1. A stable jelly formulation infused with plant essential oil for mosquito-repelling properties and with good skin compatibility\n2. Research findings such as data on mosquito-repellent effectiveness of plant essential oil compared to other natural and synthetic repellents\n3. Scientific publications in peer-reviewed journals\n4. Conference presentations at global and regional conferences",
            'socio_impact' => "Public Health & Disease Prevention:\n- Reducing mosquito bites will help prevent the spread of diseases such as malaria\n- Lower healthcare costs by decreasing mosquito-related illness rates\n- Enhance quality of life by providing safer, natural alternatives to synthetic repellents\n\nEconomic Growth & Job Creation:\n- Increased demand for plant cultivation can support local farmers, creating jobs and stimulating rural economies\n- Large-scale production can generate employment in processing, packaging, and distribution\n- Small businesses and start-ups can enter the mosquito repellent market\n\nEnvironmental & Agricultural Sustainability:\n- Shift to natural mosquito repellents reduces reliance on synthetic chemicals\n- Sustainable cultivation encourages biodiversity-friendly agricultural practices\n\nMarket Expansion & Consumer Accessibility:\n- Increased availability of affordable, plant-based mosquito repellents\n- Growth in natural skincare and insect repellent markets\n- Enhancing consumer choice with long-lasting protection and convenience",
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
                'collaboratorname' => 'Dr Denis Chirchir',
                'position' => 'Lecturer',
                'institution' => 'University of Kabianga',
                'researcharea' => 'Chemistry of Natural Products',
                'experience' => 'Over 10 years experience',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'collaboratorid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'collaboratorname' => 'Dr Douglas Kemboi',
                'position' => 'Lecturer',
                'institution' => 'University of Kabianga',
                'researcharea' => 'Chemistry of Natural Products',
                'experience' => '5 years experience',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Publications
        $publications = [
            ['Towett Kipngetich Erick, Joyce Kiplimo, Josphat Matasyoh', 2019, 'Two New Aliphatic Alkenol Geometric Isomers and a Phenolic Derivate from Endophytic Fungus Diaporthe sp. Host to Syzygium cordatum (Myrtaceae)', 'Volume 7 Issue 3 Pages 99-103'],
            ['Gilbert K. Cheruiyot, Wycliffe C. Wanyonyi, Joyce J. Kiplimo and Esther N. Maina', 2019, 'Adsorption of toxic crystal violet dye using coffee husks: Equilibrium, kinetics and thermodynamics study', 'Scientific African 5'],
            ['Yugi J. O. Kiplimo J. J., Misire C.', 2017, 'Pupicidal Activity of Ethanol and Water Extracts of Phytolacca dodecandra (L\' Herit) on Anopheles gambiae (Diptera: Culicidae) Pupae', 'Journal of Mosquito Research Vol. 17 (13) 104-110'],
            ['Jared O. Yugi and Joyce J. Kiplimo', 2017, 'Inhibitory Effect of Crude Ethanol and Water Extracts of Phytolacca dodecandra (L\' Herit) on Embryonic Development of Anopheles gambiae (Diptera: Culicidae)', 'Jordan Journal of Biological Sciences. Volume 10, Number 3, September 2017 page 177-183'],
            ['Racheal Musembei, Kiplimo Jepkorir Joyce', 2017, 'Chemical Composition and Antibacterial Activity of Essential Oil from Kenyan Conyza bonariensis (L.) Cronquist', 'Science Letters ISSN 2345-5463']
        ];

        foreach ($publications as $pub) {
            DB::table('publications')->insert([
                'publicationid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'authors' => $pub[0],
                'year' => $pub[1],
                'title' => $pub[2],
                'researcharea' => 'Natural Products Chemistry',
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
            'summary' => 'Development of mosquito repellent jelly from plant essential oils',
            'indicators' => 'Essential oil extracted, GC-MS analysis completed, Stable jelly formulated, Repellent effectiveness tested',
            'verification' => 'Laboratory analysis and reports',
            'assumptions' => 'Plant material is rich in essential oils with mosquito-repelling properties',
            'goal' => 'Develop a safe, effective, and sustainable mosquito-repellent jelly',
            'purpose' => 'Provide natural alternative to synthetic mosquito repellents',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Workplan
        $workplan = [
            ['Identification and collection of plant material', 'Quarter 1', 'Raw material', 'Transportation', 'Researcher', 'Plant material collected'],
            ['Extraction using Clevenger apparatus', 'Quarter 1', 'Essential oil', 'Laboratory', 'Researcher and student', 'Essential oil extracted'],
            ['GC-MS analysis of essential oil', 'Quarter 2', 'Analyzed oil', 'GC-MS', 'Researcher and student', 'Oil composition determined'],
            ['Formulation of Mosquito repellant Jelly', 'Quarter 3', 'Jelly formulation', 'Laboratory', 'Researcher and student', 'Stable jelly produced'],
            ['Effectiveness and stability testing', 'Quarter 3', 'Test results', 'Laboratory', 'Researcher and student', 'Efficacy confirmed'],
            ['Publication and Dissemination of Data', 'Quarter 4', 'Publications', 'Conference', 'Researcher', 'Research disseminated']
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

        // Budget - Equipment
        $equipment = [
            ['Clevenger-type apparatus', 1, 30000],
            ['Round-bottom flask', 2, 10000],
            ['Liebigs condenser', 1, 20000],
            ['Hot plate', 1, 20000],
            ['Petri dishes (pyrex)', 50, 100],
            ['Petri dishes (plastic)', 200, 10],
            ['Package material', 200, 25],
            ['Labels', 200, 10]
        ];

        foreach ($equipment as $item) {
            DB::table('expenditures')->insert([
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => $item[0],
                'itemtype' => 'Equipment and facilities',
                'quantity' => $item[1],
                'unitprice' => $item[2],
                'total' => $item[1] * $item[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Budget - Consumables
        $consumables = [
            ['Dichloromethane', 20, 500],
            ['Hexane', 20, 500],
            ['Methanol', 10, 1000],
            ['Ethyl acetate', 20, 700],
            ['Silica gel', 10, 1140],
            ['Jelly base', 11, 1000],
            ['PDA media', 2, 3500],
            ['MEA media', 2, 3325]
        ];

        foreach ($consumables as $item) {
            DB::table('expenditures')->insert([
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => $item[0],
                'itemtype' => 'Consumables',
                'quantity' => $item[1],
                'unitprice' => $item[2],
                'total' => $item[1] * $item[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Budget - Travel and Subsistence
        DB::table('expenditures')->insert([
            [
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => 'Transport',
                'itemtype' => 'Travel and subsistence',
                'quantity' => 1,
                'unitprice' => 10000,
                'total' => 10000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => 'Subsistence for 3 days',
                'itemtype' => 'Travel and subsistence',
                'quantity' => 1,
                'unitprice' => 50000,
                'total' => 50000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Budget - Conference and Publication
        DB::table('expenditures')->insert([
            [
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => 'Publication fee',
                'itemtype' => 'Conference and publication',
                'quantity' => 1,
                'unitprice' => 30000,
                'total' => 30000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'expenditureid' => Str::uuid(),
                'proposalidfk' => $proposalId,
                'item' => 'Conference facilitation',
                'itemtype' => 'Conference and publication',
                'quantity' => 1,
                'unitprice' => 30000,
                'total' => 30000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $totalBudget = DB::table('expenditures')
            ->where('proposalidfk', $proposalId)
            ->sum('total');

            DB::commit();
            
            echo "Successfully created Mosquito Repellent Jelly Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Prof. Joyce Kiplimo\n";
            echo "Theme: Natural Sciences\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: June 2025 - June 2026 (1 year)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
