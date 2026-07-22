<?php

namespace App\Http\Resources;

use App\Models\CaseStudy;
use App\Models\Deliverable;
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
        $sections = $caseStudy->content ?? [];
        $deliverableNames = $this->resolveContentDeliverableNames($sections);

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
            'content' => collect($sections)
                ->map(fn (array $section): array => [
                    'section_title' => $section['section_title'] ?? null,
                    'blocks' => collect($section['blocks'])
                        ->map(fn (array $block): array => $this->transformBlock($block, $deliverableNames))
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
     * @param  array{type: string, data: array<string, mixed>}  $block
     * @param  array<int, string>  $deliverableNames
     * @return array{type: string, data: array<string, mixed>}
     */
    protected function transformBlock(array $block, array $deliverableNames): array
    {
        return match ($block['type']) {
            'image' => [
                'type' => 'image',
                'data' => [...$block['data'], 'url' => Storage::disk('public')->url($block['data']['url'])],
            ],
            'select' => [
                'type' => 'select',
                'data' => [
                    'label' => $block['data']['label'] ?? null,
                    'value' => $deliverableNames[(int) $block['data']['value']] ?? null,
                ],
            ],
            'multiselect' => [
                'type' => 'multiselect',
                'data' => [
                    'label' => $block['data']['label'] ?? null,
                    'values' => collect((array) ($block['data']['values'] ?? []))
                        ->map(fn (mixed $id): ?string => $deliverableNames[(int) $id] ?? null)
                        ->filter()
                        ->values()
                        ->all(),
                ],
            ],
            default => $block,
        };
    }

    /**
     * Batch-resolve every Deliverable referenced by a select/multiselect block across all sections.
     *
     * @param  array<int, array{section_title: ?string, blocks: array<int, array{type: string, data: array<string, mixed>}>}>  $sections
     * @return array<int, string>
     */
    protected function resolveContentDeliverableNames(array $sections): array
    {
        $ids = collect($sections)
            ->flatMap(fn (array $section): array => $section['blocks'])
            ->flatMap(fn (array $block): array => match ($block['type']) {
                'select' => [$block['data']['value'] ?? null],
                'multiselect' => $block['data']['values'] ?? [],
                default => [],
            })
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        return Deliverable::query()->whereIn('id', $ids)->pluck('name', 'id')->all();
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
