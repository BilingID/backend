<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\QuestionBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 50 questions
        for ($i = 0; $i < 100; $i++) {
            $question = QuestionBank::create([
                'question_text' => 'Question ' . ($i + 1)
            ]);

            // create 2 choices for each question
            $question->choices()->createMany([
                [
                    'choice_text' => 'Choice 1 - ' . ($i + 1),
                ],
                [
                    'choice_text' => 'Choice 2 - ' . ($i + 1),
                ],
            ]);        
        }
    }
}
