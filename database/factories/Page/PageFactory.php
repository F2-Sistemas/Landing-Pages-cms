<?php

namespace Database\Factories\Page;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => str(fake()->unique()->words(3, true))->slug(),
            'id' => fn (array $attributes) => str($attributes['slug'] ?? fake()->unique()->words(3, true))->slug(),
            'fake_column1' => 'Value of fake_column1',
            'fake_column2' => 'Value of fake_column2',
        ];
    }
}
