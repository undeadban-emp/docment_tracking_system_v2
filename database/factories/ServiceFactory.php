<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Submission of DTR',
            'description' => 'Submission of DTR',
            'office' => Office::get()[1]->code,
            'service_process_id' => 'SOD-I',
            // 'service_process_id' => 'TN-|',
        ];
    }
}
