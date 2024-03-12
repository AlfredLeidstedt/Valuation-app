<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValuationHistoryResource\Pages;
use App\Filament\Resources\ValuationHistoryResource\RelationManagers;
use App\Models\ValuationHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValuationHistoryResource extends Resource
{
    protected static ?string $model = ValuationHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Valuation History';

    protected static ?string $navigationGroup = 'Valuation API';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('regNo')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('manufacturer')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('modelSeries')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('valuation_from_wayke')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('offer_from_bilbolaget')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('condition.name') 
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('rule.nameOfRule') 
                ->sortable()
                ->toggleable(),

                
                Tables\Columns\TextColumn::make('rule.deductions.name') 
                ->sortable()
                ->toggleable(),


            ])

            ->filters([
                //
            ])

            ->actions([
                //Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                /*
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),
                */

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
            'index' => Pages\ListValuationHistories::route('/'),
            'create' => Pages\CreateValuationHistory::route('/create'),
            'edit' => Pages\EditValuationHistory::route('/{record}/edit'),
        ];
    }
}
