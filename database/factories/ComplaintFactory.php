<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
     protected $model = Complaint::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      public function definition(): array
    {
        return [
            'protocol' => 'DEN-' . now()->format('Ymd') . '-' . strtoupper($this->faker->lexify('??????')),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['ABERTA', 'EM_ANALISE', 'CONCLUIDA', 'ARQUIVADA']),
        ];
    }
}
