<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenderMathematicsProposalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $userId = Str::uuid();
            DB::table('users')->insert([
                'userid' => $userId,
                'pfno' => 'PF' . rand(1000, 9999),
                'name' => 'Andrew Kibet Kipkosgei',
                'email' => 'akipkosgei@kabianga.ac.ke',
                'password' => Hash::make('password123'),
                'phonenumber' => '0721236398',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $user = DB::table('users')->where('userid', $userId)->first();
            echo "Created user: Andrew Kibet Kipkosgei (Email: akipkosgei@kabianga.ac.ke, Password: password123)\n";
            
            $grants = DB::table('grants')->first();
            $departments = DB::table('departments')->first();
            $theme = DB::table('researchthemes')->where('themename', 'LIKE', '%Gender%')->orWhere('themename', 'LIKE', '%Education%')->first();
            
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
                'highqualification' => 'PhD in Mathematics Education',
                'officephone' => 'N/A',
                'cellphone' => '0721236398',
                'faxnumber' => 'N/A',
                'researchtitle' => 'Gender Perception in Mathematics and Its Influence on Career Choices in Engineering and Technology among Students in Colleges and Universities Offering Technology Programs in the South Region of Kenya',
                'commencingdate' => '2025-09-01',
                'terminationdate' => '2026-03-31',
                'objectives' => "General Objective:\nThe main objective is to understand how gender-based beliefs and stereotypes about mathematics ability affect students decisions to pursue or avoid careers in engineering and technology, with the aim of identifying barriers to gender equity and proposing interventions to promote increased participation especially by women.\n\nSpecific Objectives:\n1. To assess gender perceptions of mathematics among students enrolled in technology programs in the South region of Kenya\n2. To analyze how these perceptions influence career choices in engineering and technology\n3. To examine the influence of teachers, parents, and peers on students' gendered attitudes toward mathematics\n4. To recommend strategies for promoting gender equity in STEM education and career pathways in the South region",
                'hypothesis' => "Research Questions:\n1. What are the gender-based perceptions of mathematics among students enrolled in technology programs in the South region of Kenya?\n2. How do gendered perceptions of mathematics influence students' career choices in engineering and technology?\n3. How do teachers, parents, and peers influence students' gendered attitudes toward mathematics?\n4. What strategies can be implemented to promote gender equity in STEM education and career pathways in the South region of Kenya?",
                'significance' => "Significance:\n1. Promotion of Gender Equality: Understanding gender perceptions in mathematics can help highlight existing biases that discourage female students from pursuing careers in engineering and technology\n2. Enhancing Student Participation and Diversity: Addressing gender-based stereotypes can increase student participation in mathematics, engineering, and technology programs\n3. Guiding Policy and Curriculum Development: Recognizing how gender perceptions influence career choices provides evidence to guide the creation of inclusive policies and curricula\n4. Empowering Economic Development: By reducing the gender gap in STEM, more students are empowered to enter high-demand fields, helping to meet the region's workforce needs\n5. Improved Career Counseling: Insights enable educators and counselors to provide better career advice to all students\n6. Combating Cultural Stereotypes: Research helps challenge and change cultural stereotypes that undermine the potential of female students\n7. Supporting Sustainable Development Goals: Contributes to achieving SDG 5 (Gender Equality) and SDG 4 (Quality Education)\n\nJustification:\nPersistent gender biases and stereotypes surrounding mathematics discourage many female students from considering STEM careers. Investigating these perceptions can help identify and address barriers, fostering a more equitable educational environment and promoting inclusive educational policies.",
                'ethicals' => "1. Informed Consent: Ensure that all participants voluntarily agree to participate after receiving clear, comprehensive information about the study's purpose, procedures, risks, and benefits. Consent must be obtained without coercion and participants should be free to withdraw anytime without penalty\n\n2. Confidentiality and Privacy: Protect the identity and personal data of participants by anonymizing responses, securely storing data, and only using information for research purposes. Assure participants that their information will not be disclosed in ways that could cause harm or embarrassment\n\n3. Respect for Autonomy: Respect participants' rights to make their own decisions about participation and to control how their data is used, especially considering gender and cultural sensitivities in the South region context\n\n4. Minimizing Harm and Maximizing Benefits: Avoid emotional, psychological, or social harm to participants",
                'expoutput' => "Expected Outputs:\n1. Understanding of Gender Perceptions in Mathematics: Identification of prevailing gender stereotypes surrounding mathematics among students in technology programs\n2. Impact of Gender Perceptions on Career Choices: Clear evidence showing how gendered views on mathematics influence students' career preferences in engineering and technology\n3. Role of Social Influencers on Gender Attitudes: Evaluation of how teachers, parents, and peers shape students' attitudes toward mathematics and STEM\n4. Actionable Strategies for Promoting Gender Equity in STEM: Development of practical recommendations to address gender disparities in STEM education\n5. Research publications in peer-reviewed journals\n6. Conference presentations",
                'socio_impact' => "Socio-Economic Impact:\n1. Reduced Female Participation in STEM Careers: Persistent stereotypes discourage female students from pursuing related degrees and careers\n2. Widened Gender Gaps in Academic Performance: Boys consistently outperform girls in mathematics due to societal norms and school-based gender biases\n3. Economic Loss from Untapped Talent: Under-representation of women means the economy misses out on substantial talent and skills\n4. Inter-generational and Community Effects: Parental and societal gender perceptions influence students' self-efficacy and motivation\n5. Impacts on Social Mobility and Gender Equality: Limited access to STEM education reduces women's ability to access higher income jobs\n6. Workforce Diversity: Addressing gender disparities will create a more diverse and innovative workforce in engineering and technology sectors",
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
                    'collaboratorname' => 'Dr. John Keter',
                    'position' => 'Lecturer',
                    'institution' => 'CIEM',
                    'researcharea' => 'Communication Technology',
                    'experience' => 'ECT-Chemistry Methods and General methods of teaching and educational Media',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'collaboratorid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'collaboratorname' => 'Harrison Rutto',
                    'position' => 'Administrator and Masters Student-MBA-HRM',
                    'institution' => 'University of Kabianga',
                    'researcharea' => 'Human Resource/Public Administration',
                    'experience' => 'Human Resource/Public Administration',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);

            // Publications
            $publications = [
                ['Kilel,M, Kipkosgei A & Rotumoi, j', 2023, 'School Based Factors Influencing Transition of Pupils from Lower Grades to Upper Grades in Public Primary Schools in Sotik Sub-County, Kenya', 'International Journal of Recent Research in Social Sciences and Humanities (IJRRESSH). Vol 10. Issue 3'],
                ['Kipkosgei A', 2025, 'Gender Dynamics in Mathematics learning Attitudinal Inclinations', 'East African Journal of Education Studies, Vol 8, Issue 2'],
                ['Kipkosgei A. Kibet', 2025, 'Breaking Barriers: The Impact of Teacher Expectations on Gender Attitudes Towards Learning Mathematics in Keiyo District, Kenya', 'EPRA International Journal of Environmental Economics, Commerce and Educational Management: Volume 12, Issue 3'],
                ['Kipkosgei A. Kibet', 2025, 'Trends in B.ED(ODL) Enrolment: A study of Kenyan Universities (2006-2010)', 'EPRA International Journal of Environmental Economics, Commerce and Educational Management: Volume 12, Issue 2'],
                ['Keter, J. K.', 2025, 'VTC managers views on the implementation of the modularized competency based education and training (CBET) curriculum by TVET CDACC in Kenya', 'International Journal for Multidisciplinary Research (IJFMR), 7(4)']
            ];

            foreach ($publications as $pub) {
                DB::table('publications')->insert([
                    'publicationid' => Str::uuid(),
                    'proposalidfk' => $proposalId,
                    'authors' => $pub[0],
                    'year' => $pub[1],
                    'title' => $pub[2],
                    'researcharea' => 'Mathematics Education',
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
                'summary' => 'Mixed-methods research to assess gender perceptions in mathematics and their influence on career choices in engineering and technology',
                'indicators' => 'Percentage of students with positive/negative perceptions by gender, Correlation between math perception and career choices, Influence from teachers/parents/peers, Gender-disaggregated enrollment data',
                'verification' => 'Student surveys, Focus group discussions, Statistical analysis reports, Institutional enrollment records, Interview transcripts',
                'assumptions' => 'Students respond honestly to surveys, Sample is representative, Survey tools are valid, Institutional data is accurate, Respondents provide accurate information',
                'goal' => 'Understand how gender-based beliefs about mathematics affect career decisions in engineering and technology',
                'purpose' => 'Identify barriers to gender equity and propose interventions to promote increased participation especially by women in STEM',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Workplan
            $workplan = [
                ['Designing of secondary data collection tools', 'September 2025', 'Library research', 'Library', 'Researchers', 'Secondary data'],
                ['Pilot testing', 'October 2025', 'Analysis', 'Field', 'Researchers', 'Data'],
                ['Re-testing', 'December 2025', 'Field work', 'Field', 'Researchers and research assistants', 'Data'],
                ['Data Analysis', 'January 2026', 'Editing, coding and analysis', 'Computer', 'Researchers', 'Analyzed data'],
                ['Report writing', 'February 2026', 'Writing first draft and final report', 'Office', 'Researchers', 'Research paper'],
                ['Dissemination of research findings', 'March-April 2026', 'Seminars, workshops', 'Conference venues', 'Researchers', 'Paper published in relevant journal']
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
                ['Car travel (1,500 Km)', 'Equipment and facilities', 150, 200],
                ['Research instruments: Data collection, typing and editing', 'Equipment and facilities', 2, 48000],
                ['Subsistence - Four research assistants', 'Travel and subsistence', 28, 2000],
                ['Data analysis assistant', 'Personnel and publication', 1, 20000],
                ['Journal publications', 'Personnel and publication', 2, 20000],
                ['Conference presentation', 'Personnel and publication', 2, 30000]
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
            
            echo "Successfully created Gender Mathematics Proposal!\n";
            echo "Proposal Code: {$proposalCode}\n";
            echo "Principal Investigator: Andrew Kibet Kipkosgei\n";
            echo "Theme: Gender/Education\n";
            echo "Total Budget: KES " . number_format($totalBudget) . "\n";
            echo "Duration: September 2025 - March 2026 (7 months)\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
