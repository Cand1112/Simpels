<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\Province;
use App\Models\StudyProgram;
use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

            'study_program_id' => StudyProgram::query()->inRandomOrder()->first()?->id,
            'province_id' => Province::query()->inRandomOrder()->first()?->id,
            'district_id' => District::query()->inRandomOrder()->first()?->id,
            'subdistrict_id' => Subdistrict::query()->inRandomOrder()->first()?->id,

            'registration_number' => fake()->unique()->numerify('########'),
            'is_active' => fake()->boolean(),
            'c1_subcriteria_id' => fake()->numberBetween(1, 4),
            'c2_subcriteria_id' => fake()->numberBetween(5, 8),
            'c3_subcriteria_id' => fake()->numberBetween(9, 12),
            'c4_subcriteria_id' => fake()->numberBetween(13, 16),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
