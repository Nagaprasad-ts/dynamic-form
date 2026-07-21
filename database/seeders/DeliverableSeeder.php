<?php

namespace Database\Seeders;

use App\Models\Deliverable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Logo Design',
            'Rebranding',
            'Brand Guideline Book',
            'Brand Patterns and Elements',
            'Colour Palette',
            'Packaging Design',
            'Spatial / Environmental Branding',
            'Animation',
            'Website Design',
            'Content Production',
            'AI Automation',
        ])->each(fn (string $name) => Deliverable::firstOrCreate(['name' => $name]));
    }
}
