<?php

namespace App\Http\Controllers;

use App\Http\Resources\CaseStudyResource;
use App\Models\CaseStudy;
use Inertia\Inertia;
use Inertia\Response;

class CaseStudyController extends Controller
{
    public function index(): Response
    {
        $caseStudies = CaseStudy::query()
            ->with('deliverables')
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->get();

        return Inertia::render('case-studies/index', [
            'caseStudies' => CaseStudyResource::collection($caseStudies),
        ]);
    }

    public function show(CaseStudy $caseStudy): Response
    {
        abort_unless($caseStudy->is_published, 404);

        $caseStudy->load('deliverables');

        return Inertia::render('case-studies/show', [
            'caseStudy' => CaseStudyResource::make($caseStudy),
            'faqJsonLd' => $caseStudy->faqJsonLd(),
        ]);
    }
}
