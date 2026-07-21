<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CaseStudyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('slug'),
                        TextEntry::make('client_name'),
                        TextEntry::make('industry'),
                        TextEntry::make('location'),
                        TextEntry::make('project_date')
                            ->date()
                            ->label('Year'),
                        TextEntry::make('duration'),
                    ]),

                Section::make('Summary')
                    ->schema([
                        ImageEntry::make('cover_image')
                            ->disk('public')
                            ->hiddenLabel(),
                        TextEntry::make('excerpt')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Deliverables & results')
                    ->schema([
                        TextEntry::make('deliverables.name')
                            ->badge(),
                        KeyValueEntry::make('key_results')
                            ->keyLabel('Metric')
                            ->valueLabel('Result'),
                    ]),

                Section::make('Gallery')
                    ->schema([
                        RepeatableEntry::make('gallery')
                            ->hiddenLabel()
                            ->schema([
                                ImageEntry::make('image')
                                    ->disk('public')
                                    ->hiddenLabel(),
                                TextEntry::make('caption'),
                            ])
                            ->columns(2),
                    ])
                    ->visible(fn (Get $get): bool => filled($get('gallery'))),

                Section::make('Applied')
                    ->schema([
                        RepeatableEntry::make('applied_gallery')
                            ->hiddenLabel()
                            ->schema([
                                ImageEntry::make('image')
                                    ->disk('public')
                                    ->hiddenLabel(),
                                TextEntry::make('caption'),
                            ])
                            ->columns(2),
                    ])
                    ->visible(fn (Get $get): bool => filled($get('applied_gallery'))),

                Section::make('FAQ')
                    ->schema([
                        KeyValueEntry::make('faq')
                            ->hiddenLabel()
                            ->keyLabel('Question')
                            ->valueLabel('Answer'),
                    ])
                    ->visible(fn (Get $get): bool => filled($get('faq'))),

                Section::make('Publishing')
                    ->columns(3)
                    ->schema([
                        IconEntry::make('is_featured')
                            ->boolean(),
                        IconEntry::make('is_published')
                            ->boolean(),
                        TextEntry::make('published_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
