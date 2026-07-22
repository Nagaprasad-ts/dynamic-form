<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use App\Models\CaseStudy;
use App\Models\Deliverable;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CaseStudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->alphaDash()
                            ->unique(table: CaseStudy::class, ignoreRecord: true),
                        TextInput::make('client_name')
                            ->maxLength(255),
                        TextInput::make('industry')
                            ->maxLength(255),
                    ]),

                Section::make('Project meta')
                    ->description('Shown as the Industry / Location / Year / Duration row on the case study page.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('location')
                            ->maxLength(255),
                        DatePicker::make('project_date')
                            ->label('Date'),
                        TextInput::make('duration')
                            ->placeholder('e.g. 8 Months')
                            ->maxLength(255),
                    ]),

                Section::make('Summary')
                    ->columnSpanFull()
                    ->schema([
                        RichEditor::make('excerpt')
                            ->label('Excerpt')
                            ->helperText('A short summary shown on case study listing cards.')
                            ->resizableImages()
                            ->maxLength(1000),
                    ]),

                Section::make('Cover image')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('cover_image')
                            ->image()
                            ->disk('public')
                            ->directory('case-studies/covers')
                            ->imageEditor(),
                    ]),

                Section::make('Content')
                    ->description('Add one or more numbered sections (e.g. "The Brief", "The Approach"). Each section is built from freely orderable heading, paragraph, and image blocks.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('content')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('section_title')
                                    ->label('Section title')
                                    ->placeholder('e.g. The Brief')
                                    ->maxLength(255)
                                    ->live(onBlur: true),
                                Builder::make('blocks')
                                    ->hiddenLabel()
                                    ->blocks([
                                        Block::make('heading')
                                            ->schema([
                                                TextInput::make('content')
                                                    ->label('Heading')
                                                    ->required()
                                                    ->live(onBlur: true),
                                                Select::make('level')
                                                    ->options([
                                                        'h2' => 'Heading 2',
                                                        'h3' => 'Heading 3',
                                                        'h4' => 'Heading 4',
                                                    ])
                                                    ->default('h2')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->label(fn (?array $state): string => $state['content'] ?? 'Heading'),
                                        Block::make('paragraph')
                                            ->schema([
                                                RichEditor::make('content')
                                                    ->label('Paragraph')
                                                    ->required(),
                                            ])
                                            ->label('Paragraph'),
                                        Block::make('image')
                                            ->schema([
                                                FileUpload::make('url')
                                                    ->label('Image')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('case-studies/content')
                                                    ->required(),
                                                TextInput::make('alt')
                                                    ->label('Alt text')
                                                    ->required()
                                                    ->maxLength(255),
                                            ])
                                            ->label('Image'),
                                        Block::make('key_value')
                                            ->label('Key Value')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label('Title')
                                                    ->maxLength(255),
                                                KeyValue::make('pairs')
                                                    ->keyLabel('Label')
                                                    ->valueLabel('Value')
                                                    ->required(),
                                            ]),
                                        Block::make('date')
                                            ->label('Date')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->maxLength(255),
                                                DatePicker::make('date')
                                                    ->required(),
                                            ])
                                            ->columns(2),
                                        Block::make('checkbox')
                                            ->label('Checkbox')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->required()
                                                    ->maxLength(255),
                                                Checkbox::make('checked'),
                                            ])
                                            ->columns(2),
                                        Block::make('select')
                                            ->label('Select')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->maxLength(255),
                                                Select::make('value')
                                                    ->label('Category')
                                                    ->options(fn (): array => Deliverable::query()->orderBy('name')->pluck('name', 'id')->all())
                                                    ->searchable()
                                                    ->required(),
                                            ])
                                            ->columns(2),
                                        Block::make('multiselect')
                                            ->label('Multi-select')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->maxLength(255),
                                                Select::make('values')
                                                    ->label('Categories')
                                                    ->multiple()
                                                    ->options(fn (): array => Deliverable::query()->orderBy('name')->pluck('name', 'id')->all())
                                                    ->searchable()
                                                    ->required(),
                                            ]),
                                    ])
                                    ->minItems(1)
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['section_title'] ?? null)
                            ->addActionLabel('Add content section')
                            ->collapsible()
                            ->minItems(1)
                            ->required(),
                    ]),

                Section::make('Gallery')
                    ->description('Project photos/screenshots shown alongside the brief.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('gallery')
                            ->hiddenLabel()
                            ->schema([
                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('case-studies/gallery')
                                    ->required(),
                                TextInput::make('caption')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->addActionLabel('Add gallery image')
                            ->defaultItems(0),
                    ]),

                Section::make('Applied')
                    ->description('Selected applications of the work across real touchpoints (packaging mockups, signage, etc.).')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('applied_gallery')
                            ->hiddenLabel()
                            ->schema([
                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('case-studies/applied')
                                    ->required(),
                                TextInput::make('caption')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->addActionLabel('Add applied image')
                            ->defaultItems(0),
                    ]),

                Section::make('Deliverables')
                    ->description('What was delivered as part of this project.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('deliverables')
                            ->hiddenLabel()
                            ->multiple()
                            ->relationship(titleAttribute: 'name')
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(),
                            ]),
                    ]),

                Section::make('Highlights')
                    ->description('Optional measurable impact, e.g. "Revenue growth" => "40%".')
                    ->columnSpanFull()
                    ->schema([
                        KeyValue::make('key_results')
                            ->hiddenLabel()
                            ->keyLabel('Metric')
                            ->valueLabel('Result'),
                    ]),

                Section::make('FAQ')
                    ->description('Question and answer pairs shown on the case study page. When present, these are also emitted as FAQPage JSON-LD structured data for SEO.')
                    ->columnSpanFull()
                    ->schema([
                        KeyValue::make('faq')
                            ->hiddenLabel()
                            ->keyLabel('Question')
                            ->valueLabel('Answer')
                            ->addActionLabel('Add FAQ'),
                    ]),

                Section::make('Publishing')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Checkbox::make('is_featured'),
                        Checkbox::make('is_published')
                            ->live(),
                        DateTimePicker::make('published_at')
                            ->visible(fn (Get $get): bool => (bool) $get('is_published')),
                    ]),
            ]);
    }
}
