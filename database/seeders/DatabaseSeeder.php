<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Historical;
use App\Models\OptionQuestion;
use App\Models\Question;
use App\Models\Response;
use App\Models\Template;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{ public function run(): void
    {
        // Usuários
        User::factory()->count(5)->create();

        // Formulário + Perguntas + Opções
        $template = Template::factory()->create([
            'name' => 'Formulário de Denúncia Padrão',
        ]);

        $questions = Question::factory()->count(3)->create([
            'template_id' => $template->id,
        ]);

        foreach ($questions as $question) {
            if ($question->type === 'select') {
                OptionQuestion::factory()->count(3)->create([
                    'question_id' => $question->id,
                ]);
            }
        }

        // Denúncias + Respostas + Histórico
        Complaint::factory()->count( 10)->create()->each(function ($complaint) use ($questions) {
            foreach ($questions as $question) {
                Response::factory()->create([
                    'complaint_id' => $complaint->id,
                    'question_id' => $question->id,
                ]);
            }

            Historical::factory()->count(2)->create([
                'complaint_id' => $complaint->id,
            ]);
        });
    }
}