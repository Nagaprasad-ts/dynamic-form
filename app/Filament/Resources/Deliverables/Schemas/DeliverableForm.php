<?php

namespace App\Filament\Resources\Deliverables\Schemas;

use App\Models\Deliverable;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliverableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(table: Deliverable::class, ignoreRecord: true),
            ]);
    }
}
