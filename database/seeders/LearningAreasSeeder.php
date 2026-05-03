<?php

namespace Database\Seeders;

use App\Models\LearningArea;
use App\Models\Strand;
use App\Models\SubStrand;
use Illuminate\Database\Seeder;

class LearningAreasSeeder extends Seeder
{
    public function run(): void
    {
        $curriculum = [
            // ─── LOWER PRIMARY (Grades 1–3) ─────────────────────────────────
            ['name'=>'Literacy Activities','code'=>'LIT-LP','grade'=>'Grade 1','color'=>'#3B82F6','weekly_lessons'=>10,
             'strands'=>[['Listening & Speaking',['Phonological Awareness','Vocabulary','Oral Narrative']],
                         ['Reading',['Word Recognition','Fluency','Reading Comprehension']],
                         ['Writing',['Emergent Writing','Guided Writing','Creative Writing']]]],
            ['name'=>'Kiswahili Activities','code'=>'KIS-LP','grade'=>'Grade 1','color'=>'#10B981','weekly_lessons'=>8,
             'strands'=>[['Kusikiliza na Kuzungumza',['Matamshi','Msamiati','Mazungumzo']],
                         ['Kusoma',['Usomaji wa Maneno','Ufahamu']],
                         ['Kuandika',['Uandishi wa Herufi','Insha']]]],
            ['name'=>'Mathematical Activities','code'=>'MTH-LP','grade'=>'Grade 1','color'=>'#F59E0B','weekly_lessons'=>10,
             'strands'=>[['Numbers',['Counting','Place Value','Operations']],
                         ['Measurement',['Length','Mass','Capacity']],
                         ['Geometry',['2D Shapes','3D Shapes','Patterns']]]],
            ['name'=>'Environmental Activities','code'=>'ENV-LP','grade'=>'Grade 1','color'=>'#84CC16','weekly_lessons'=>5,
             'strands'=>[['Living Things',['Plants','Animals','Habitats']],
                         ['Non-Living Things',['Materials','Water','Air']],
                         ['Environment & Health',['Hygiene','Safety','Conservation']]]],
            ['name'=>'Creative Arts Activities','code'=>'CRA-LP','grade'=>'Grade 1','color'=>'#EC4899','weekly_lessons'=>5,
             'strands'=>[['Visual Arts',['Drawing','Painting','Craft']],
                         ['Music',['Singing','Rhythm','Instruments']],
                         ['Movement & Dance',['Movement','Dance Styles','Drama']]]],
            ['name'=>'Physical Education','code'=>'PE-LP','grade'=>'Grade 1','color'=>'#F97316','weekly_lessons'=>3,
             'strands'=>[['Physical Fitness',['Cardiovascular','Flexibility','Strength']],
                         ['Games & Sports',['Athletics','Ball Games','Gymnastics']]]],
            ['name'=>'Religious Education','code'=>'CRE-LP','grade'=>'Grade 1','color'=>'#8B5CF6','weekly_lessons'=>3,
             'strands'=>[['Christian Values',['Creation','Family','Prayer']]]],

            // ─── UPPER PRIMARY (Grades 4–6) ─────────────────────────────────
            ['name'=>'English','code'=>'ENG-UP','grade'=>'Grade 4','color'=>'#3B82F6','weekly_lessons'=>8,
             'strands'=>[['Listening & Speaking',['Listening Comprehension','Speech & Pronunciation','Oral Communication']],
                         ['Reading',['Reading Fluency','Reading Comprehension','Critical Reading']],
                         ['Writing',['Functional Writing','Creative Writing','Grammar & Mechanics']],
                         ['Language Structure & Use',['Grammar','Vocabulary','Punctuation']]]],
            ['name'=>'Kiswahili','code'=>'KIS-UP','grade'=>'Grade 4','color'=>'#10B981','weekly_lessons'=>8,
             'strands'=>[['Kusikiliza na Kuzungumza',['Ufahamu wa Kusikiliza','Mazungumzo']],
                         ['Kusoma',['Usomaji Fasaha','Ufahamu wa Kusoma']],
                         ['Kuandika',['Uandishi wa Ubunifu','Sarufi na Tahajia']]]],
            ['name'=>'Mathematics','code'=>'MTH-UP','grade'=>'Grade 4','color'=>'#F59E0B','weekly_lessons'=>8,
             'strands'=>[['Numbers',['Whole Numbers','Fractions','Decimals','Percentages']],
                         ['Measurement',['Length & Perimeter','Area','Volume','Time & Money']],
                         ['Geometry',['Angles','Polygons','Symmetry','Coordinates']],
                         ['Statistics & Probability',['Data Handling','Probability']]]],
            ['name'=>'Integrated Science','code'=>'SCI-UP','grade'=>'Grade 4','color'=>'#06B6D4','weekly_lessons'=>5,
             'strands'=>[['Living Things',['Cells','Nutrition','Reproduction','Ecology']],
                         ['Non-Living Things',['Matter','Force & Energy','Simple Machines']],
                         ['Environment',['Weather','Conservation','Pollution']]]],
            ['name'=>'Social Studies','code'=>'SST-UP','grade'=>'Grade 4','color'=>'#84CC16','weekly_lessons'=>5,
             'strands'=>[['Our Community',['Family','School','Neighbourhood']],
                         ['Our Country Kenya',['History','Geography','Government','Culture']],
                         ['Africa & the World',['African History','World Geography']]]],
            ['name'=>'Creative Arts','code'=>'CRA-UP','grade'=>'Grade 4','color'=>'#EC4899','weekly_lessons'=>4,
             'strands'=>[['Visual Arts',['Drawing','Painting','Sculpture','Design']],
                         ['Performing Arts',['Music','Dance','Drama & Theatre']]]],
            ['name'=>'Physical & Health Education','code'=>'PHE-UP','grade'=>'Grade 4','color'=>'#F97316','weekly_lessons'=>3,
             'strands'=>[['Physical Fitness',['Fitness Components','Exercise']],
                         ['Games & Sports',['Athletics','Team Sports','Gymnastics']],
                         ['Health Education',['Personal Hygiene','Nutrition','Safety']]]],
            ['name'=>'CRE / IRE / HRE','code'=>'RE-UP','grade'=>'Grade 4','color'=>'#8B5CF6','weekly_lessons'=>3,
             'strands'=>[['Faith & Belief',['Creation & God','Prayer & Worship','Moral Values']],
                         ['Family & Community',['Respect','Responsibility','Service']]]],

            // ─── JUNIOR SECONDARY (Grades 7–9) ──────────────────────────────
            ['name'=>'English','code'=>'ENG-JS','grade'=>'Grade 7','color'=>'#3B82F6','weekly_lessons'=>6,
             'strands'=>[['Communication & Collaboration',['Listening','Speaking','Reading','Writing']],
                         ['Language Structure',['Grammar','Vocabulary','Composition']]]],
            ['name'=>'Kiswahili','code'=>'KIS-JS','grade'=>'Grade 7','color'=>'#10B981','weekly_lessons'=>6,
             'strands'=>[['Mawasiliano',['Kusikiliza','Kuzungumza','Kusoma','Kuandika']]]],
            ['name'=>'Mathematics','code'=>'MTH-JS','grade'=>'Grade 7','color'=>'#F59E0B','weekly_lessons'=>6,
             'strands'=>[['Numbers & Algebra',['Integers','Algebra','Indices','Sequences']],
                         ['Measurement & Geometry',['Perimeter','Area','Volume','Angles','Trigonometry']],
                         ['Statistics & Probability',['Data','Probability','Statistics']]]],
            ['name'=>'Integrated Science','code'=>'SCI-JS','grade'=>'Grade 7','color'=>'#06B6D4','weekly_lessons'=>5,
             'strands'=>[['Biology',['Cells','Nutrition','Respiration','Reproduction']],
                         ['Chemistry',['Matter','Acids & Bases','Reactions']],
                         ['Physics',['Force','Energy','Waves','Electricity']]]],
            ['name'=>'Social Studies','code'=>'SST-JS','grade'=>'Grade 7','color'=>'#84CC16','weekly_lessons'=>4,
             'strands'=>[['History',['Pre-Colonial','Colonial','Independence']],
                         ['Geography',['Physical Geography','Human Geography','Map Reading']],
                         ['Citizenship',['Government','Rights & Responsibilities','Democracy']]]],
            ['name'=>'Pre-Technical Studies','code'=>'PTS-JS','grade'=>'Grade 7','color'=>'#64748B','weekly_lessons'=>4,
             'strands'=>[['Technology',['ICT Basics','Digital Literacy','Programming Introduction']],
                         ['Home Science',['Cooking','Nutrition','Textiles']],
                         ['Agriculture',['Crop Production','Livestock','Agribusiness']]]],
            ['name'=>'Creative Arts','code'=>'CRA-JS','grade'=>'Grade 7','color'=>'#EC4899','weekly_lessons'=>3,
             'strands'=>[['Visual Arts',['Painting','Sculpture','Design']],['Performing Arts',['Music','Dance','Drama']]]],
            ['name'=>'Physical & Health Education','code'=>'PHE-JS','grade'=>'Grade 7','color'=>'#F97316','weekly_lessons'=>3,
             'strands'=>[['Physical Education',['Athletics','Games','Gymnastics']],['Health',['Reproductive Health','Nutrition','Safety']]]],
            ['name'=>'CRE / IRE / HRE','code'=>'RE-JS','grade'=>'Grade 7','color'=>'#8B5CF6','weekly_lessons'=>2,
             'strands'=>[['Religious Studies',['Faith','Ethics','Values']]]],
        ];

        foreach ($curriculum as $areaData) {
            $area = LearningArea::firstOrCreate(
                ['code' => $areaData['code']],
                [
                    'name'           => $areaData['name'],
                    'grade_level'    => $areaData['grade'],
                    'color'          => $areaData['color'],
                    'weekly_lessons' => $areaData['weekly_lessons'],
                    'is_active'      => true,
                ]
            );

            foreach ($areaData['strands'] as $order => [$strandName, $subStrands]) {
                $strand = Strand::firstOrCreate(
                    ['learning_area_id' => $area->id, 'name' => $strandName],
                    ['order' => $order + 1]
                );

                foreach ($subStrands as $ssOrder => $ssName) {
                    SubStrand::firstOrCreate(
                        ['strand_id' => $strand->id, 'name' => $ssName],
                        ['order' => $ssOrder + 1]
                    );
                }
            }
        }

        $this->command->info('CBC Curriculum seeded: ' . LearningArea::count() . ' learning areas, ' . Strand::count() . ' strands, ' . SubStrand::count() . ' sub-strands.');
    }
}
