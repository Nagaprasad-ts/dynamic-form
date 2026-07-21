<?php

namespace App\Filament\Resources\Deliverables\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DeliverableInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('caseStudies.title')
                    ->label('Used by')
                    ->badge()
                    ->placeholder('No case studies yet'),
            ]);
    }
}
