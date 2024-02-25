<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\ProductTypeEnum;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

class ProductResource extends Resource
{
    protected $table = 'products';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Products :)';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 0;

    // This property defines what should be displayed as a hit regarding the global search. 
    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 10;

    // protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    

    public static function getGloballySearchableAttributes(): array
    {

        return ['name', 'slug', 'description'];

    }


    public static function getGlobalSearchEloquentQuery(): Builder

    {

        return parent::getGlobalSearchEloquentQuery()->with(['brand']);

    }


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
                        
                        // Method to make the user unable to interact with this field(?)
                        ->disabled()
                        
                        //Transforms the data.
                        ->dehydrated()
                        
                        ->required()

                        ->unique(Product::class, 'slug', ignoreRecord:true),

                        Forms\Components\MarkdownEditor::make('description')
                        ->columnSpan('full')

                    ])->columns(2),

                Forms\Components\Section::make('Pricing and inventory')
                    
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

                ]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
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

                        ->default(now())
                    ]),
                    
                    Forms\Components\Section::make('Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')

                        // Set where the files should be stored
                        ->directory('form-attachments')

                        // This sets all the names to the original name otherwise it is auto changed 
                        ->preserveFilenames()

                        // This make sure it is an image.
                        ->image()

                        // This makes sure that the image is editable. Config of the image. 
                        ->imageEditor()

                        
                    ])->collapsible(),

                    Forms\Components\Section::make('Association')
                        ->schema([
                            
                                Forms\Components\Select::make('brand_id')
                                ->relationship('brand', 'name')
                                ->required(),

                                Forms\Components\Select::make('categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->required(),
                        
                        ]),

                    ]),
        
        ]);

            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image'),
                
                Tables\Columns\TextColumn::make('name')
                
                // This makes it possible to search for name. 
                ->searchable()
                
                // This makes it possible to sort all the products by the corresponding column, in this case 'name'.
                ->sortable(),

                TextColumn::make('brand.name')
                
                ->searchable()
                ->sortable(),

                IconColumn::make('is_visable')->boolean()
                ->label('Visibility')
                ->sortable()
                ->toggleable(),

                TextColumn::make('price')
                ->sortable()
                ->toggleable(),

                TextColumn::make('quantity')
                ->sortable()
                ->toggleable(),

                TextColumn::make('published')
                ->date()
                ->sortable(),

                TextColumn::make('type')

            ])


            ->filters([
                
                Tables\Filters\TernaryFilter::make('is_visable')
                ->label ('Visibility')
                ->boolean()
                ->trueLabel('Only Visible Products')
                ->falseLabel('Only Hidden Products')
                ->native(false),

                Tables\Filters\SelectFilter::make('Select brand')

                ->relationship('brand','name')

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
