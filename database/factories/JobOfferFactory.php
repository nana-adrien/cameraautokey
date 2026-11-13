<?php

namespace Database\Factories;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->jobTitle();

        return [
            'company' => fake()->randomElement(array_keys(JobOffer::COMPANIES)),
            'title' => $title,
            'slug' => Str::slug($title),
            'contract_type' => 'CDI',
            'location' => 'Douala',
            'summary' => fake()->sentence(12),
            'description' => "Accueillir les clients\nRéaliser les interventions",
            'requirements' => "2 ans d’expérience\nPermis B",
            'closes_at' => null,
            'is_published' => true,
        ];
    }

    public function closed(): static
    {
        return $this->state(['closes_at' => now()->subDay()]);
    }
}
