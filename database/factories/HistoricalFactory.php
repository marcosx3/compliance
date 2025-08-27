<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\Historical;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Historical>
 */
class HistoricalFactory extends Factory
{
    protected $model = Historical::class;

    public function definition(): array
    {
        return [
            'complaint_id' => Complaint::factory(),
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['CRIADA', 'STATUS ALTERADO', 'ANEXO ADICIONADO']),
        ];
    }
}
