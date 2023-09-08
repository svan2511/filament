<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\View\Components\Modal;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

     protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->minLength(3)->maxLength(10),
                TextInput::make('email')->required()->email(),
                Select::make('standered_id')->required()->relationship('standered','name'),
                FileUpload::make('image')->required()->image(),
                
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('standered.name')->searchable(),
                // TextColumn::make('status')->description(function(Student $record){
                //     return $record->status?'Active':'Inactive';
                // })
                // TextColumn::make('status')->label('Status')->formatStateUsing(function(Student $record) {
                //     return $record->status ? "Active" : "Inactive";
                // })
                BooleanColumn::make('status')->label('Status')

                //Columns\Boolean::make('is_active')->label('Active?'),
            ])
            ->defaultSort('id','desc')
            ->filters([
             SelectFilter::make('Filter By standard')->relationship('standered' ,'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

                ActionGroup::make([Action::make('Active')->action(function(Student $record){
                    $record->status = 1;
                    $record->save();
                }),
                Action::make('Inactive')->action(function(Student $record){
                    $record->status = 0;
                    $record->save();
                })])
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Active')->action(function(Collection $records) {
                        $records->each(function($record) {
                            $record->status = 1;
                            $record->save();
                        });
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->color('success')->icon('heroicon-o-check-circle'),

                    BulkAction::make('InActive')->action(function(Collection $records) {
                        $records->each(function($record) {
                            $record->status = 0;
                            $record->save();
                        });
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->color('danger')->icon('heroicon-o-x-circle')

                ]),

                
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }    

      public static function getGlobalSearchResultDetails(Model $record): array
        {
            return [
                'Name' => $record->name,
                'Standard' => $record->standered->name,
            ];
        }
}
