<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;

use App\Filament\Resources\BrandResource\RelationManagers\ProductsRelationManager;

use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // This variable is describing what sorting ranking this resource should have so that it can be ordered in the menu. 
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\Group::make()

                ->schema([

                    Forms\Components\Section::make()

                    ->schema([
                        
                        Forms\Components\TextInput::make('name')

                        // This method makes sure that this field is filled when submitting the request.
                        ->required()

                        // This is validating that the field is filled whenever it is not the focus of the user. 
                        ->live(onBlur:true)
                        
                        //Prompts that the name shoud be unique
                        ->unique()
                        
                        // This method is prompting that a slug should be generated based on the input in this field. 
                        ->afterStateUpdated(function(string $operation, $state, Forms\Set $set) {

                            if ($operation !== 'create') {
                                return;
                            }

                            $set('slug', Str::slug($state));

                        }),


                        Forms\Components\TextInput::make('slug')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->unique(),



                        Forms\Components\TextInput::make('url')
                        ->label('Website URL')
                        ->required()
                        ->unique()
                        ->columnSpan('full'),


                        Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull(),


                    ])->columns(2)

                    ]),


                    Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_visable')
                            ->label('Visability')
                            ->helperText('Enable or disable the visibility of the brand')
                            ->default('true'),

                        ]),

                        Forms\Components\Group::make()

                        ->schema([
                            Forms\Components\Section::make('Color')
                                ->schema([
                                    Forms\Components\ColorPicker::make('primary-hex')
                                    ->label('Primary Color')

                                ])
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('url')
                ->label('Website URL')
                ->sortable()
                ->searchable(),

                Tables\Columns\ColorColumn::make('primary_hex')
                ->label('Primary Color'),

                Tables\Columns\IconColumn::make('is_visable')
                ->boolean()
                ->sortable()
                ->label('Visibility'),

                Tables\Columns\TextColumn::make('updated_at')
                ->date()
                ->sortable()

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
            
            ProductsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
