<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StanderedResource\Pages;
use App\Filament\Resources\StanderedResource\RelationManagers;
use App\Filament\Resources\StanderedResource\RelationManagers\StudentsRelationManager;
use App\Models\Standered;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StanderedResource extends Resource
{
    protected static ?string $model = Standered::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('name')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('students_count')->counts('students'),
             
                ])
            ->filters([

                ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStandereds::route('/'),
            'create' => Pages\CreateStandered::route('/create'),
            'edit' => Pages\EditStandered::route('/{record}/edit'),
        ];
    }    

   
}
