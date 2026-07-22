import { Head, Link } from '@inertiajs/react';
import { index } from '@/routes/case-studies';
import type { CaseStudy, ContentBlock } from '@/types';

interface CaseStudyShowProps {
    caseStudy: CaseStudy;
    faqJsonLd: Record<string, unknown> | null;
}

function ContentBlockView({ block }: { block: ContentBlock }) {
    if (block.type === 'heading') {
        const HeadingTag = block.data.level;

        return (
            <HeadingTag className="text-2xl font-semibold">
                {block.data.content}
            </HeadingTag>
        );
    }

    if (block.type === 'paragraph') {
        return (
            <div
                className="leading-relaxed text-neutral-700 dark:text-neutral-300 [&_a]:underline [&_ol]:list-decimal [&_ol]:pl-5 [&_strong]:font-semibold [&_ul]:list-disc [&_ul]:pl-5"
                dangerouslySetInnerHTML={{ __html: block.data.content }}
            />
        );
    }

    if (block.type === 'image') {
        return (
            <figure>
                <img
                    src={block.data.url}
                    alt={block.data.alt}
                    className="w-full rounded-lg"
                />
            </figure>
        );
    }

    if (block.type === 'key_value') {
        return (
            <div>
                {block.data.label && (
                    <h3 className="mb-3 text-sm font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                        {block.data.label}
                    </h3>
                )}
                <dl className="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    {Object.entries(block.data.pairs).map(([key, value]) => (
                        <div key={key}>
                            <dt className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                {key}
                            </dt>
                            <dd className="mt-1 text-sm font-medium">{value}</dd>
                        </div>
                    ))}
                </dl>
            </div>
        );
    }

    if (block.type === 'date') {
        const formatted = new Date(block.data.date).toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });

        return (
            <div>
                {block.data.label && (
                    <dt className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                        {block.data.label}
                    </dt>
                )}
                <dd className="mt-1 text-sm font-medium">{formatted}</dd>
            </div>
        );
    }

    if (block.type === 'checkbox') {
        return (
            <div className="flex items-center gap-2">
                <span
                    className={`flex h-5 w-5 shrink-0 items-center justify-center rounded border ${
                        block.data.checked
                            ? 'border-neutral-900 bg-neutral-900 text-white dark:border-neutral-100 dark:bg-neutral-100 dark:text-neutral-900'
                            : 'border-neutral-300 dark:border-neutral-700'
                    }`}
                >
                    {block.data.checked && (
                        <svg viewBox="0 0 16 16" fill="none" className="h-3 w-3">
                            <path
                                d="M3 8.5L6.5 12L13 4.5"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    )}
                </span>
                <span className="text-sm">{block.data.label}</span>
            </div>
        );
    }

    if (block.type === 'select') {
        return (
            <div>
                {block.data.label && (
                    <dt className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                        {block.data.label}
                    </dt>
                )}
                {block.data.value && (
                    <span className="mt-1 inline-block rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                        {block.data.value}
                    </span>
                )}
            </div>
        );
    }

    return (
        <div>
            {block.data.label && (
                <dt className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                    {block.data.label}
                </dt>
            )}
            <div className="mt-1 flex flex-wrap gap-2">
                {block.data.values.map((value) => (
                    <span
                        key={value}
                        className="rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-900 dark:text-neutral-300"
                    >
                        {value}
                    </span>
                ))}
            </div>
        </div>
    );
}

export default function CaseStudyShow({
    caseStudy,
    faqJsonLd,
}: CaseStudyShowProps) {
    const metaRow = [
        { label: 'Industry', value: caseStudy.industry },
        { label: 'Location', value: caseStudy.location },
        {
            label: 'Year',
            value: caseStudy.project_date
                ? new Date(caseStudy.project_date).toLocaleDateString(
                      undefined,
                      {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric',
                      },
                  )
                : null,
        },
        { label: 'Duration', value: caseStudy.duration },
    ].filter((item) => item.value);

    return (
        <>
            <Head title={caseStudy.title}>
                {faqJsonLd && (
                    <script type="application/ld+json">
                        {JSON.stringify(faqJsonLd)}
                    </script>
                )}
            </Head>
            <div className="min-h-screen bg-white text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
                <div className="mx-auto max-w-3xl px-6 py-16">
                    <Link
                        href={index.url()}
                        className="text-sm font-medium text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                    >
                        ← Case studies
                    </Link>

                    <p className="mt-6 text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                        Case study
                    </p>
                    <h1 className="mt-2 text-4xl font-semibold tracking-tight">
                        {caseStudy.title}
                    </h1>
                    {caseStudy.client_name && (
                        <p className="mt-2 text-lg text-neutral-600 dark:text-neutral-400">
                            {caseStudy.client_name}
                        </p>
                    )}

                    {metaRow.length > 0 && (
                        <dl className="mt-8 grid grid-cols-2 gap-6 border-y border-neutral-200 py-6 sm:grid-cols-4 dark:border-neutral-800">
                            {metaRow.map((item) => (
                                <div key={item.label}>
                                    <dt className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                        {item.label}
                                    </dt>
                                    <dd className="mt-1 text-sm font-medium">
                                        {item.value}
                                    </dd>
                                </div>
                            ))}
                        </dl>
                    )}

                    {caseStudy.cover_image && (
                        <img
                            src={caseStudy.cover_image}
                            alt={caseStudy.title}
                            className="mt-10 w-full rounded-lg"
                        />
                    )}

                    {caseStudy.excerpt && (
                        <div
                            className="mt-8 text-lg leading-relaxed text-neutral-700 dark:text-neutral-300 [&_a]:underline [&_img]:rounded-lg [&_ol]:list-decimal [&_ol]:pl-5 [&_strong]:font-semibold [&_ul]:list-disc [&_ul]:pl-5"
                            dangerouslySetInnerHTML={{
                                __html: caseStudy.excerpt,
                            }}
                        />
                    )}

                    {caseStudy.deliverables.length > 0 && (
                        <div className="mt-8 flex flex-wrap gap-2">
                            {caseStudy.deliverables.map((deliverable) => (
                                <span
                                    key={deliverable}
                                    className="rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-900 dark:text-neutral-300"
                                >
                                    {deliverable}
                                </span>
                            ))}
                        </div>
                    )}

                    {caseStudy.content.map((section, sectionIndex) => (
                        <section key={sectionIndex} className="mt-16">
                            {section.section_title && (
                                <div className="mb-6 flex items-baseline gap-3">
                                    <span className="text-sm font-semibold text-neutral-400 dark:text-neutral-600">
                                        {String(sectionIndex + 1).padStart(
                                            2,
                                            '0',
                                        )}
                                    </span>
                                    <h2 className="text-sm font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                        {section.section_title}
                                    </h2>
                                </div>
                            )}
                            <div className="flex flex-col gap-6">
                                {section.blocks.map((block, blockIndex) => (
                                    <ContentBlockView
                                        key={blockIndex}
                                        block={block}
                                    />
                                ))}
                            </div>
                        </section>
                    ))}

                    {caseStudy.gallery.length > 0 && (
                        <div className="mt-16 grid grid-cols-2 gap-4">
                            {caseStudy.gallery.map((item, itemIndex) => (
                                <figure key={itemIndex}>
                                    <img
                                        src={item.image}
                                        alt={item.caption ?? ''}
                                        className="w-full rounded-lg"
                                    />
                                    {item.caption && (
                                        <figcaption className="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                            {item.caption}
                                        </figcaption>
                                    )}
                                </figure>
                            ))}
                        </div>
                    )}

                    {caseStudy.key_results &&
                        Object.keys(caseStudy.key_results).length > 0 && (
                            <div className="mt-16 grid grid-cols-2 gap-6 sm:grid-cols-3">
                                {Object.entries(caseStudy.key_results).map(
                                    ([metric, result]) => (
                                        <div key={metric}>
                                            <p className="text-2xl font-semibold">
                                                {result}
                                            </p>
                                            <p className="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                                                {metric}
                                            </p>
                                        </div>
                                    ),
                                )}
                            </div>
                        )}

                    {caseStudy.applied_gallery.length > 0 && (
                        <section className="mt-16">
                            <h2 className="text-sm font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                Applied
                            </h2>
                            <p className="mt-2 text-neutral-600 dark:text-neutral-400">
                                Selected applications of the work across real
                                touchpoints.
                            </p>
                            <div className="mt-6 grid grid-cols-2 gap-4">
                                {caseStudy.applied_gallery.map(
                                    (item, itemIndex) => (
                                        <figure key={itemIndex}>
                                            <img
                                                src={item.image}
                                                alt={item.caption ?? ''}
                                                className="w-full rounded-lg"
                                            />
                                            {item.caption && (
                                                <figcaption className="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                                    {item.caption}
                                                </figcaption>
                                            )}
                                        </figure>
                                    ),
                                )}
                            </div>
                        </section>
                    )}

                    {caseStudy.faq && Object.keys(caseStudy.faq).length > 0 && (
                        <section className="mt-16">
                            <h2 className="text-sm font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                FAQ
                            </h2>
                            <dl className="mt-6 flex flex-col divide-y divide-neutral-200 dark:divide-neutral-800">
                                {Object.entries(caseStudy.faq).map(
                                    ([question, answer]) => (
                                        <div key={question} className="py-4">
                                            <dt className="font-medium">
                                                {question}
                                            </dt>
                                            <dd className="mt-2 text-neutral-600 dark:text-neutral-400">
                                                {answer}
                                            </dd>
                                        </div>
                                    ),
                                )}
                            </dl>
                        </section>
                    )}
                </div>
            </div>
        </>
    );
}
