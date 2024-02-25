<?php

namespace App\Filament\Resources;

use App\Enums\FuelStatusEnum;
use App\Enums\FuelTypeEnum;
use App\Filament\Resources\RuleResource\Pages;
use App\Filament\Resources\RuleResource\RelationManagers;
use App\Filament\Resources\RuleResource\RelationManagers\DeductionsRelationManager;
use App\Models\Rule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;

use Filament\Forms\Components\Actions\Action;

use Filament\Forms\Components\Repeater\createButtonAction;
use App\Filament\Resources\Filament\Resources\TextColumn;

use App\Models\Deduction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


// THE FOLLOWING IMPORTS ARE FROM THE FILAMENT DOCUMENTATION



class RuleResource extends Resource
{
    protected static ?string $model = Rule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Rules';

    protected static ?string $navigationGroup = 'Valuation API';

    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form

            ->schema([

                Forms\Components\Group::make()

                ->schema([

                Forms\Components\Section::make('Obligatory details')

                ->schema([
                
                Forms\Components\TextInput::make('nameOfRule')
                    ->default('Rule ' . random_int(10000, 99999))
                    ->autofocus()
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->label('Name of rule'),
                
                Forms\Components\TextInput::make('manufacturer')
                    
                    ->datalist([

                        'Audi',
                        'BMW',
                        'Citroen',
                        'Ford',
                        'Honda',
                        'Hyundai',
                        'Kia',
                        'Mazda',
                        'Mercedes',
                        'Mitsubishi',
                        'Nissan',
                        'Opel',
                        'Peugeot',
                        'Renault',
                        'Seat',
                        'Skoda',
                        'Subaru',
                        'Suzuki',
                        'Toyota',
                        'Volkswagen',
                        'Volvo',

                    ])

                    ->required()
                    ->placeholder('Choose manufacturer')
                    ->label('Manufacturer'),

                ])->columns(2),


                // OPTIONAL DETAILS SECTION 

                Forms\Components\Section::make('Optional details of the rule')

                ->schema([
                
                Forms\Components\TextInput::make('minKm')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('Min km')
                    ->label('Min km'),
                
                Forms\Components\TextInput::make('maxKm')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('Max km')
                    ->label('Max km'),
                
                Forms\Components\TextInput::make('modelSeries')
                    ->autofocus()
                    ->placeholder('Enter if this rule applies to a specific model series')
                    ->label('Model series')
                    ->columnSpanFull(),
                
                Forms\Components\Checkbox::make('hasTowBar')
                    ->autofocus()
                    ->label('Does this rule only apply to cars that have TOWBARS?')
                    ->columnSpanFull(),
                
                Forms\Components\CheckboxList::make('fuelType')
                    ->options([
                    'Electric' => 'Electric',
                    'Diesel' => 'Diesel',
                    'Petrol' => 'Petrol',
                    'Hybrid' => 'Hybrid',
                    ])
                    ->label('Which fuel types should this rule apply to')
                    ->columnSpanFull(),

                Forms\Components\CheckboxList::make('gearboxType')
                    ->options([
                        'Automat' => 'Automatic',
                        'Manuell' => 'Manual',
                    ])
                    ->label('Which gearbox types should this rule apply to?'),
                
                Forms\Components\TextInput::make('equipmentLevel')
                    ->autofocus()
                    ->placeholder('Equippmentlevel')
                    ->label('Equipment level for this rule')
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('minEnginePower')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('min HP')
                    ->label('Minimum engine power'),
                
                Forms\Components\TextInput::make('maxEnginePower')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('max HP')
                    ->label('Maximum engine power'),
                
                Forms\Components\TextInput::make('minManufactureYear')
                    ->autofocus()
                    ->placeholder('min year')
                    ->label('Earliest manufacture year'),

                Forms\Components\TextInput::make('maxManufactureYear')
                    ->autofocus()
                    ->placeholder('max year')
                    ->label('Oldest manufacture year'),

                    ])->columns(2),

                    

                // THIS IS THE FORM FOR THE DEDUCTION IN CONNECTION TO THE RULE

                /* This is removed to test to see if the rule can be created first and then the deductions can be added to the rule.

                Forms\Components\Section::make('Deductions connected to this rule')

                ->schema(static::getDetailsFormSchema())
                
                ->columns(2),

                */
                
                // *************************************************************

                
                /* 
                Forms\Components\Section::make('Deductions saved to this rule')

                ->schema([
                    static::getDeductionsRepeater()
                    ])
                
                ->columns(2),
                */ 
                

                ])->columnSpan(['lg' => 2]),

                

            
            Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make('Contender info')

                        ->schema([
        
                        Forms\Components\Toggle::make('isContender')
                        ->autofocus()
                        ->default(true)
                        ->required()
                        ->label('Does this rule make a contender?'),
        
                        ]),

                // SCHEDULE SECTION 

                Forms\Components\Section::make('Schedule details')

                ->schema([

                Forms\Components\Toggle::make('isScheduled')
                    ->autofocus()
                    ->required()
                    ->default(false)
                    ->label('Is this rule scheduled?')
                    ->columnSpanFull(),

                Forms\Components\DatePicker::make('startdate')
                    ->label('Startdate')
                    ->default(now()),

                Forms\Components\DatePicker::make('enddate')
                    ->label('Enddate')
                    ->default(now()),

                    ])->columns(2),


                // RELEVANCE SECTION

                Forms\Components\Section::make('Relevance details')

                ->schema([

                Forms\Components\Toggle::make('isPublished')
                    ->autofocus()
                    ->default(false)
                    ->required()
                    ->label('Is this rule published?'),

                Forms\Components\Toggle::make('isActive')
                    ->autofocus()
                    ->default(false)
                    ->required()
                    ->label('Is this rule active?'),

                ]),
                    


                // RELATIONSHIPS SECTION

                /*
                Forms\Components\Section::make('Relationships')

                ->schema([

                Forms\Components\Select::make('deduction_id')
                ->relationship('deductions', 'name')
                ->multiple()
                ->required(),

                ])->columns(2),
                    
                ])
                ->columnSpan(['lg'=>1]),

                */


                ])

            ])
            ->columns(3)
            
            ;   


    }




// TABLE VIEW AT THE START PAGE OF THE RULES RESOURCE

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('nameOfRule')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('manufacturer') 
                ->sortable()
                ->toggleable(),

                Tables\Columns\BooleanColumn::make('isScheduled') 
    
                ->falseIcon($icon = 'heroicon-o-x-circle') // Set the icon that should be displayed when the cell is false.
                ->trueIcon($icon = 'heroicon-s-check-circle') // Set the icon that should be displayed when the cell is true.
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('startdate') 
                ->sortable()
                ->toggleable(),

                Tables\Columns\BooleanColumn::make('isPublished') 

                ->falseIcon($icon = 'heroicon-o-x-circle') // Set the icon that should be displayed when the cell is false.
                ->trueIcon($icon = 'heroicon-s-check-circle') // Set the icon that should be displayed when the cell is true.

                ->sortable()
                ->toggleable(),

                Tables\Columns\BooleanColumn::make('isContender') 

                ->falseIcon($icon = 'heroicon-o-x-circle') // Set the icon that should be displayed when the cell is false.
                ->trueIcon($icon = 'heroicon-s-check-circle') // Set the icon that should be displayed when the cell is true.

                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('deductions.name') 
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





    // Ny deklaration av relations managern. 

    public static function getRelations(): array
    {
        return [

            'deductions' => DeductionsRelationManager::class,
            
        ];
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRules::route('/'),
            'create' => Pages\CreateRule::route('/create'),
            'edit' => Pages\EditRule::route('/{record}/edit'),
        ];
    }


    public static function getDetailsFormSchema(): array
    {
        return [
            
            Forms\Components\Repeater::make('deductions')


                // ->relationship()

                ->schema([


                    // TEST TO SEE IF WE CAN GET THE DEDUCTIONS DISPLAYED 

                    
                    Forms\Components\Select::make('deduction_id')


                        ->relationship('deductions', 'name')

                        ->label('Deduction')
                        ->options(Deduction::query()->pluck('name', 'id'))
                        
    
                        ->searchable()

                        ->nullable()
                        ->searchable()


                        ->createOptionForm([

                            Forms\Components\TextInput::make('name')
                                ->autofocus()
                                ->required()
                                ->placeholder('Enter name of deduction')
                                ->label('Name of deduction'),

                            Forms\Components\TextInput::make('deductionPercentage')
                                ->validationAttribute('deductionPercentage')
                                ->numeric()
                                ->required()
                                ->placeholder('E.g. 12.575')
                                ->label('Deduction Percentage'),

                            Forms\Components\TextInput::make('minValueInterval')
                                ->autofocus()
                                ->numeric()
                                ->required()
                                ->placeholder('Min value of the Car')
                                ->label('Minimum value '),
            
                            Forms\Components\TextInput::make('maxValueInterval')
                                ->autofocus()
                                ->numeric()
                                ->required()
                                ->placeholder('Max value of the Car')
                                ->label('Maximum value'),

                            Forms\Components\TextInput::make('minDeduction')
                                ->autofocus()
                                ->numeric()
                                ->required()
                                ->placeholder('Minimum deduction')
                                ->label('Minimum deduction'),

                            Forms\Components\TextInput::make('maxDeduction')
                                ->autofocus()
                                ->numeric()
                                ->required()
                                ->placeholder('Maximum deduction')
                                ->label('Maximum deduction'),


                        ])

                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Create deduction')
                                ->modalButton('Create deduction')
                                ->modalWidth('lg');

                                
                        }),
                        

                ])
            ];        
    }




    // Under development
    public static function getDeductionsRepeater(): Repeater
    {
        return Repeater::make('deductions')

            ->relationship()

            ->schema([

                Forms\Components\Select::make('deduction_id')
                    ->label('Deduction')
                    ->options(Deduction::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()

                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>

                        $set('deductionPercentage', Deduction::find($state)?->deductionPercentage ?? 0)
                    )

                    ->searchable(),

                Forms\Components\TextInput::make('deductionPercentage')
                    ->label('Deduction %')
                    ->numeric()
                    ->default(14.578),

                Forms\Components\TextInput::make('minValueInterval')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('Enter minimum value interval')
                    ->label('Minimum value interval'),

                Forms\Components\TextInput::make('maxValueInterval')
                    ->autofocus()
                    ->numeric()
                    ->placeholder('Enter maximum value interval')
                    ->label('Maximum value interval'),

                Forms\Components\TextInput::make('minDeduction')
                    ->label('Min deduction')
                    ->dehydrated()
                    ->numeric(),

                Forms\Components\TextInput::make('maxDeduction')
                    ->label('Max deduction')
                    ->dehydrated()
                    ->numeric(),
            ])

            ->hiddenLabel()


            ->required();


    }

}



