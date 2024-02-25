<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConditionResource\Pages;
use App\Filament\Resources\ConditionResource\RelationManagers;
use App\Models\Condition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConditionResource extends Resource
{
    protected static ?string $model = Condition::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Conditions';

    protected static ?string $navigationGroup = 'Valuation API';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([

                Forms\Components\Group::make()

                ->schema([

                    Forms\Components\Section::make()

                    ->schema([

                            Forms\Components\TextInput::make('name')
                                ->autofocus()
                                ->required()
                                ->placeholder(__('Name')),

                            Forms\Components\MarkdownEditor::make('description')
                                ->autofocus()
                                ->required()
                                ->placeholder(__('Description')),

                            Forms\Components\TextInput::make('deduction')
                                ->autofocus()
                                ->numeric()
                                ->required()
                                ->placeholder(__('Deduction (SEK)')),

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

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deduction')
                    ->searchable()
                    ->sortable(),

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
            'index' => Pages\ListConditions::route('/'),
            'create' => Pages\CreateCondition::route('/create'),
            'edit' => Pages\EditCondition::route('/{record}/edit'),
        ];
    }
}
