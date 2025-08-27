<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'template_id' => Template::factory(),
            'text' => $this->faker->sentence(6),
            'type' => $this->faker->randomElement(['text', 'number', 'date', 'select','radio']),
            'required' => $this->faker->boolean(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
