<?php

namespace App\Http\Resources;

use App\Models\CaseStudy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property CaseStudy $resource
 */
class CaseStudyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $caseStudy = $this->resource;

        return [
            'id' => $caseStudy->id,
            'title' => $caseStudy->title,
            'slug' => $caseStudy->slug,
            'client_name' => $caseStudy->client_name,
            'industry' => $caseStudy->industry,
            'location' => $caseStudy->location,
            'project_date' => $caseStudy->project_date?->toDateString(),
            'duration' => $caseStudy->duration,
            'excerpt' => $caseStudy->excerpt,
            'cover_image' => $caseStudy->cover_image ? Storage::disk('public')->url($caseStudy->cover_image) : null,
            'content' => collect($caseStudy->content ?? [])
                ->map(fn (array $section): array => [
                    'section_title' => $section['section_title'] ?? null,
                    'blocks' => collect($section['blocks'])
                        ->map(fn (array $block): array => [
                            'type' => $block['type'],
                            'data' => $block['type'] === 'image'
                                ? [...$block['data'], 'url' => Storage::disk('public')->url($block['data']['url'])]
                                : $block['data'],
                        ])
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),
            'gallery' => $this->mapGallery($caseStudy->gallery ?? []),
            'applied_gallery' => $this->mapGallery($caseStudy->applied_gallery ?? []),
            'deliverables' => $caseStudy->deliverables->pluck('name')->values()->all(),
            'key_results' => $caseStudy->key_results,
            'faq' => $caseStudy->faq,
            'is_featured' => $caseStudy->is_featured,
            'published_at' => $caseStudy->published_at?->toIso8601String(),
        ];
    }

    /**
     * @param  array<int, array{image: string, caption: ?string}>  $items
     * @return array<int, array{image: string, caption: ?string}>
     */
    protected function mapGallery(array $items): array
    {
        return collect($items)
            ->map(fn (array $item): array => [
                'image' => Storage::disk('public')->url($item['image']),
                'caption' => $item['caption'] ?? null,
            ])
            ->values()
            ->all();
    }
}
