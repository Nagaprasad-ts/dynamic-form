<?php

namespace Database\Factories;

use App\Models\CaseStudy;
use App\Models\Deliverable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CaseStudy>
 */
class CaseStudyFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (CaseStudy $caseStudy): void {
            $caseStudy->deliverables()->attach(
                Deliverable::factory()->count(fake()->numberBetween(1, 3))->create(),
            );
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(4), '.');

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 100000),
            'client_name' => fake()->company(),
            'industry' => fake()->randomElement(['Education', 'Healthcare', 'Retail', 'Finance', 'Technology']),
            'location' => fake()->city(),
            'project_date' => fake()->dateTimeBetween('-1 year'),
            'duration' => fake()->randomElement(['4 Weeks', '2 Months', '8 Months', '1 Year']),
            'excerpt' => fake()->sentence(20),
            'cover_image' => null,
            'content' => [
                [
                    'section_title' => 'The Brief',
                    'blocks' => [
                        [
                            'type' => 'heading',
                            'data' => ['content' => 'Scope of work', 'level' => 'h2'],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => ['content' => fake()->paragraph()],
                        ],
                    ],
                ],
                [
                    'section_title' => 'The Approach',
                    'blocks' => [
                        [
                            'type' => 'paragraph',
                            'data' => ['content' => fake()->paragraph()],
                        ],
                    ],
                ],
            ],
            'gallery' => [],
            'applied_gallery' => [],
            'key_results' => [
                'Revenue growth' => fake()->numberBetween(10, 90).'%',
                'Time saved' => fake()->numberBetween(1, 20).' hrs/week',
            ],
            'faq' => [
                'How long did this project take?' => fake()->randomElement(['4 Weeks', '2 Months', '8 Months', '1 Year']),
                'What was the outcome?' => fake()->sentence(),
            ],
            'is_featured' => fake()->boolean(20),
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
