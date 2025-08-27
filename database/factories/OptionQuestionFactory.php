<?php

namespace Database\Factories;

use App\Models\OptionQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OptionQuestion>
 */
class OptionQuestionFactory extends Factory
{
    protected $model = OptionQuestion::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'value' => $this->faker->word(),
        ];
    }
}
