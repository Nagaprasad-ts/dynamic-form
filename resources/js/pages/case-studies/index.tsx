import { Head, Link } from '@inertiajs/react';
import { show } from '@/routes/case-studies';
import type { CaseStudy } from '@/types';

interface CaseStudiesIndexProps {
    caseStudies: CaseStudy[];
}

export default function CaseStudiesIndex({
    caseStudies,
}: CaseStudiesIndexProps) {
    return (
        <>
            <Head title="Case Studies" />
            <div className="min-h-screen bg-white text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
                <div className="mx-auto max-w-6xl px-6 py-16">
                    <h1 className="text-4xl font-semibold tracking-tight">
                        Case Studies
                    </h1>
                    <p className="mt-2 text-neutral-600 dark:text-neutral-400">
                        Selected work and results from our clients.
                    </p>

                    <div className="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        {caseStudies.map((caseStudy) => (
                            <Link
                                key={caseStudy.id}
                                href={show.url(caseStudy.slug)}
                                className="group flex flex-col overflow-hidden rounded-lg border border-neutral-200 transition hover:shadow-lg dark:border-neutral-800"
                            >
                                <div className="aspect-[4/3] w-full overflow-hidden bg-neutral-100 dark:bg-neutral-900">
                                    {caseStudy.cover_image && (
                                        <img
                                            src={caseStudy.cover_image}
                                            alt={caseStudy.title}
                                            className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                    )}
                                </div>
                                <div className="flex flex-1 flex-col gap-2 p-5">
                                    {caseStudy.industry && (
                                        <span className="text-xs font-medium tracking-wide text-neutral-500 uppercase dark:text-neutral-400">
                                            {caseStudy.industry}
                                        </span>
                                    )}
                                    <h2 className="text-lg font-semibold">
                                        {caseStudy.title}
                                    </h2>
                                    {caseStudy.excerpt && (
                                        <div
                                            className="line-clamp-3 text-sm text-neutral-600 dark:text-neutral-400 [&_img]:hidden"
                                            dangerouslySetInnerHTML={{
                                                __html: caseStudy.excerpt,
                                            }}
                                        />
                                    )}
                                </div>
                            </Link>
                        ))}
                    </div>

                    {caseStudies.length === 0 && (
                        <p className="mt-12 text-neutral-500 dark:text-neutral-400">
                            No case studies published yet.
                        </p>
                    )}
                </div>
            </div>
        </>
    );
}
