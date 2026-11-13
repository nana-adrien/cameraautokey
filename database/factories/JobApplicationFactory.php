<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_offer_id' => JobOffer::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '+237 6'.fake()->numerify('## ## ## ##'),
            'message' => fake()->sentence(),
            'cv_path' => 'cvs/'.fake()->uuid().'.pdf',
            'cv_original_name' => 'cv.pdf',
            'status' => 'new',
        ];
    }
}
