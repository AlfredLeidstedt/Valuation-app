<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Product;
use App\Enums\ProductTypeEnum;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Tabs::make('Products')
                    ->tabs([

                        //NEW TAB

                        Forms\Components\Tabs\Tab::make('Information')
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
                            
                            // Method to make the user unable to interact with this field(?)
                            ->disabled()
                            
                            //Transforms the data.
                            ->dehydrated()
                            
                            ->required()

                            ->unique(Product::class, 'slug', ignoreRecord:true),

                            Forms\Components\MarkdownEditor::make('description')
                            ->columnSpan('full')

                            ])->columns(),
                        
                        //NEW TAB    
                        Forms\Components\Tabs\Tab::make('Pricing and Inventory')
                            ->schema([

                                Forms\Components\TextInput::make('sku')
                        
                            // This method makes you change the name of the field so it is not automatically set to the column name.
                            ->label("SKU (Stock keeping unit")
                            ->unique()
                            ->required(),

                            Forms\Components\TextInput::make('price')

                            // This method makes it possible to only put in a numeric value and toggle digits.
                            ->numeric()
                            
                            // This is where I can set up any rules for the input. It is stored in an array. 
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])

                            ->required(),

                            Forms\Components\TextInput::make('quantity')
                            
                            // We want to set the rules so that this value is numeric.
                            ->rules(['integer'])

                            ->minValue(0)
                            
                            ->maxValue(2000)
                            
                            ->required(),


                            Forms\Components\Select::make('type')
                            ->options([
                                'downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                                'deliverable'=> ProductTypeEnum::DELIVERABLE->value,
                            ])->required()


                            ])->columns(2),

                        // NEW TAB
                        Forms\Components\Tabs\Tab::make('Additional information')
                            ->schema([

                                Forms\Components\Toggle::make('is_visable')
                        
                                ->label('Visibility')
                                
                                ->helperText('Enable or disable product visibility')
                                
                                -> default(true),

                                Forms\Components\Toggle::make('is_featured')
                                
                                ->label('Featured')
                                
                                ->helperText('Enable or disable products featured status'),

                                Forms\Components\DatePicker::make('published')

                                ->label('Availability')

                                ->default(now()),

                                Forms\Components\Select::make('categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->required(),

                                Forms\Components\FileUpload::make('image')

                                // Set where the files should be stored
                                ->directory('form-attachments')
        
                                // This sets all the names to the original name otherwise it is auto changed 
                                ->preserveFilenames()
        
                                // This make sure it is an image.
                                ->image()
        
                                // This makes sure that the image is editable. Config of the image. 
                                ->imageEditor()

                                ->columnSpanFull()
        

                                ])->columns(2)

                            ])->columnSpanFull()

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                
                Tables\Columns\ImageColumn::make('image'),
                
                Tables\Columns\TextColumn::make('name')
                
                // This makes it possible to search for name. 
                ->searchable()
                
                // This makes it possible to sort all the products by the corresponding column, in this case 'name'.
                ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                
                ->searchable()
                ->sortable(),

                Tables\Columns\IconColumn::make('is_visable')->boolean()
                ->label('Visibility')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('published')
                ->date()
                ->sortable(),

                Tables\Columns\TextColumn::make('type')


                ])
            ->filters([
                //
            ])

            ->headerActions([

                Tables\Actions\CreateAction::make(),

            ])

            ->actions([

                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make(),

                    Tables\Actions\DeleteAction::make()

            ])
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
