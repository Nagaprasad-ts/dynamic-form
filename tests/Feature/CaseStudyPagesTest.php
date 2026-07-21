<?php

use App\Models\CaseStudy;
use Inertia\Testing\AssertableInertia as Assert;

test('the case studies index only lists published case studies', function () {
    CaseStudy::factory()->create(['title' => 'Published one', 'is_published' => true]);
    CaseStudy::factory()->create(['title' => 'Draft one', 'is_published' => false]);

    $this->get('/case-studies')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('case-studies/index')
            ->has('caseStudies', 1)
            ->where('caseStudies.0.title', 'Published one')
        );
});

test('a published case study can be viewed by its slug', function () {
    $caseStudy = CaseStudy::factory()->create([
        'slug' => 'acme-rebrand',
        'is_published' => true,
        'faq' => ['What was delivered?' => 'A full rebrand.'],
    ]);

    $this->get("/case-studies/{$caseStudy->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('case-studies/show')
            ->where('caseStudy.slug', 'acme-rebrand')
            ->where('faqJsonLd.@type', 'FAQPage')
        );
});

test('an unpublished case study 404s on the public page', function () {
    $caseStudy = CaseStudy::factory()->create(['is_published' => false]);

    $this->get("/case-studies/{$caseStudy->slug}")->assertNotFound();
});

test('faqJsonLd returns null when there is no faq', function () {
    $caseStudy = CaseStudy::factory()->create(['faq' => null]);

    expect($caseStudy->faqJsonLd())->toBeNull();
});

test('faqJsonLd builds schema.org FAQPage structured data', function () {
    $caseStudy = CaseStudy::factory()->create([
        'faq' => [
            'How long did this take?' => '8 months.',
            'What was delivered?' => 'A full rebrand.',
        ],
    ]);

    expect($caseStudy->faqJsonLd())->toBe([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'How long did this take?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => '8 months.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'What was delivered?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'A full rebrand.',
                ],
            ],
        ],
    ]);
});
