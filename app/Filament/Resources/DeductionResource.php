<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeductionResource\Pages;
use App\Filament\Resources\DeductionResource\RelationManagers;
use App\Models\Deduction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Deductions';

    protected static ?string $navigationGroup = 'Valuation API';

    protected static ?int $navigationSort = 1;



    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Group::make()

                ->schema([

                Forms\Components\Section::make('Identificator')

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
                    ->label('Deduction Percentage'),

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

            ]),
        
    ]);
    
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name of deduction'),

                Tables\Columns\TextColumn::make('deductionPercentage')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('minDeduction')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('maxDeduction')
                    ->sortable()
                    ->toggleable(),
            ])

            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeductions::route('/'),
            'create' => Pages\CreateDeduction::route('/create'),
            'edit' => Pages\EditDeduction::route('/{record}/edit'),
        ];
    }
}
