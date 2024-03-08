<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarModelResource\Pages;
use App\Filament\Resources\CarModelResource\RelationManagers;
use App\Models\CarModel;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarModelResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Car Model';

    protected static ?string $navigationGroup = 'Valuation API';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([

            Forms\Components\Group::make()

            ->schema([

                Forms\Components\Section::make()

                ->schema([

                    Forms\Components\TextInput::make('make')
                    ->autofocus()
                    ->required()
                    ->placeholder(__('Make')),

                Forms\Components\TextInput::make('model')
                    ->autofocus()
                    ->required()
                    ->placeholder(__('Model')),

                ]),
        
    ])->columnSpan(2),
    
]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('make')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('model')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCarModels::route('/'),
            'create' => Pages\CreateCarModel::route('/create'),
            'edit' => Pages\EditCarModel::route('/{record}/edit'),
        ];
    }
}
