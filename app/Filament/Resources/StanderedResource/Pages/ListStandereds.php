<?php

namespace App\Filament\Resources\StanderedResource\Pages;

use App\Filament\Resources\StanderedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandereds extends ListRecords
{
    protected static string $resource = StanderedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
