export interface HeadingBlockData {
    content: string;
    level: 'h2' | 'h3' | 'h4';
}

export interface ParagraphBlockData {
    content: string;
}

export interface ImageBlockData {
    url: string;
    alt: string;
}

export interface KeyValueBlockData {
    label: string | null;
    pairs: Record<string, string>;
}

export interface DateBlockData {
    label: string | null;
    date: string;
}

export interface CheckboxBlockData {
    label: string;
    checked: boolean;
}

export interface SelectBlockData {
    label: string | null;
    value: string | null;
}

export interface MultiSelectBlockData {
    label: string | null;
    values: string[];
}

export type ContentBlock =
    | { type: 'heading'; data: HeadingBlockData }
    | { type: 'paragraph'; data: ParagraphBlockData }
    | { type: 'image'; data: ImageBlockData }
    | { type: 'key_value'; data: KeyValueBlockData }
    | { type: 'date'; data: DateBlockData }
    | { type: 'checkbox'; data: CheckboxBlockData }
    | { type: 'select'; data: SelectBlockData }
    | { type: 'multiselect'; data: MultiSelectBlockData };

export interface ContentSection {
    section_title: string | null;
    blocks: ContentBlock[];
}

export interface GalleryItem {
    image: string;
    caption: string | null;
}

export interface CaseStudy {
    id: number;
    title: string;
    slug: string;
    client_name: string | null;
    industry: string | null;
    location: string | null;
    project_date: string | null;
    duration: string | null;
    excerpt: string | null;
    cover_image: string | null;
    content: ContentSection[];
    gallery: GalleryItem[];
    applied_gallery: GalleryItem[];
    deliverables: string[];
    key_results: Record<string, string> | null;
    faq: Record<string, string> | null;
    is_featured: boolean;
    published_at: string | null;
}
