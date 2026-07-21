<?php

namespace App\Models;

use Database\Factories\CaseStudyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $client_name
 * @property string|null $industry
 * @property string|null $location
 * @property Carbon|null $project_date
 * @property string|null $duration
 * @property string|null $excerpt
 * @property string|null $cover_image
 * @property array<int, array{section_title: ?string, blocks: array<int, array{type: string, data: array<string, mixed>}>}>|null $content
 * @property array<int, array{image: string, caption: ?string}>|null $gallery
 * @property array<int, array{image: string, caption: ?string}>|null $applied_gallery
 * @property array<string, string>|null $key_results
 * @property array<string, string>|null $faq
 * @property bool $is_featured
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'title',
    'slug',
    'client_name',
    'industry',
    'location',
    'project_date',
    'duration',
    'excerpt',
    'cover_image',
    'content',
    'gallery',
    'applied_gallery',
    'key_results',
    'faq',
    'is_featured',
    'is_published',
    'published_at',
])]
class CaseStudy extends Model
{
    /** @use HasFactory<CaseStudyFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'project_date' => 'date',
            'content' => 'array',
            'gallery' => 'array',
            'applied_gallery' => 'array',
            'key_results' => 'array',
            'faq' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<Deliverable, $this>
     */
    public function deliverables(): BelongsToMany
    {
        return $this->belongsToMany(Deliverable::class);
    }

    /**
     * The schema.org FAQPage JSON-LD structured data for this case study's FAQ, or null if it has none.
     *
     * @return array<string, mixed>|null
     */
    public function faqJsonLd(): ?array
    {
        if (blank($this->faq)) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($this->faq)
                ->map(fn (string $answer, string $question): array => [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer,
                    ],
                ])
                ->values()
                ->all(),
        ];
    }
}
