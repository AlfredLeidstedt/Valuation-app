<?php

namespace App\Filament\Resources\RuleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeductionsRelationManager extends RelationManager
{
    protected static string $relationship = 'deductions';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form

        ->schema([

            Forms\Components\Group::make(),

            Forms\Components\Section::make('Name of deduction')            

            ->schema([
                
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->placeholder('Enter name of deduction')
                    ->label('Name of deduction'),
                
                ]),

                /*
                Forms\Components\Section::make('Valuation of car')

                ->schema([

                Forms\Components\TextInput::make('valuationOfCar')
                    ->autofocus()
                    ->required()
                    ->placeholder('Enter valuation of car')
                    ->label('Valuation of car'),

                ]),

                */

                Forms\Components\Section::make('Deduction Percentage')
 
                ->schema([

                Forms\Components\TextInput::make('deductionPercentage')
                    ->validationAttribute('deductionPercentage')
                    ->numeric()
                    ->required()
                    ->placeholder('Enter deduction Percentage')
                    ->label('Deduction (%)'),

                ]),
                
                Forms\Components\Section::make('Deduction applies to cars valuated between...')

                ->schema([

                Forms\Components\TextInput::make('minValueInterval')
                    ->autofocus()
                    ->numeric()
                    ->required()
                    ->placeholder('E.g. 100000')
                    ->label('Minimum valuation (SEK)'),

                Forms\Components\TextInput::make('maxValueInterval')
                    ->autofocus()
                    ->numeric()
                    ->required()
                    ->placeholder('E.g. 200000')
                    ->label('Maximum valuation (SEK)'),

                ]),

                Forms\Components\Section::make('Minimum and maximum deduction to this instance (SEK)')

                ->schema([
                    
                Forms\Components\TextInput::make('minDeduction')
                    ->autofocus()
                    ->numeric()
                    ->required()
                    ->placeholder('Enter minimum deduction')
                    ->label('Minimum deduction'),

                Forms\Components\TextInput::make('maxDeduction')
                    ->autofocus()
                    ->numeric()
                    ->required()
                    ->placeholder('Enter maximum deduction')
                    ->label('Maximum deduction'),

                ]),

        ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name of deduction'),

                Tables\Columns\TextColumn::make('minValueInterval')
                    ->searchable()
                    ->sortable()
                    ->label('Car value min (sek)'),

                Tables\Columns\TextColumn::make('maxValueInterval')
                    ->searchable()
                    ->sortable()
                    ->label('Car value max (sek)'),

                Tables\Columns\TextColumn::make('deductionPercentage')
                    ->sortable()
                    ->toggleable()
                    ->label('Deduct (%)'),

                Tables\Columns\TextColumn::make('minDeduction')
                    ->sortable()
                    ->toggleable()
                    ->label('At least (sek)'),

                Tables\Columns\TextColumn::make('maxDeduction')
                    ->sortable()
                    ->toggleable()
                    ->label('And maximum (sek)'),
            ])

            ->filters([
                //
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])

            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    
                ]),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
