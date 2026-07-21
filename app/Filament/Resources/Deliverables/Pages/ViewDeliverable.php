<?php

namespace App\Filament\Resources\Deliverables\Pages;

use App\Filament\Resources\Deliverables\DeliverableResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeliverable extends ViewRecord
{
    protected static string $resource = DeliverableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
