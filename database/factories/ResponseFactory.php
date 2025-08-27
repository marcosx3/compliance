<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Response>
 */
class ResponseFactory extends Factory
{
    protected $model = Response::class;

    public function definition(): array
    {
        return [
            'complaint_id' => Complaint::factory(),
            'question_id' => Question::factory(),
            'text_response' => $this->faker->sentence(),
            'response_option_id' => null, // só se MULTIPLA_ESCOLHA
        ];
    }
}
