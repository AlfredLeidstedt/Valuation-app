<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{

    protected static ?string $model = Category::class;


    protected static ?string $navigationIcon = 'heroicon-o-tag';


    protected static ?int $navigationSort = 4;


    protected static ?string $navigationGroup = 'Shop';

    protected static bool $shouldRegisterNavigation = true;


    public static function form(Form $form): Form
    {

        return $form


            ->schema([

                
                Group::make()


                ->schema([


                Forms\Components\Section::make()


                    ->schema([

                        // Section::make([

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

                    ->unique(Category::class, 'slug', ignoreRecord:true),

                    Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull(),

                    //TextInput::make('description')->columnSpanFull()

                    ])

                ])->columns(2),

                Group::make()  

                ->schema([
                    Section::make('Status')

                    ->schema([
                        
                        Toggle::make('is_visible')
                        ->label('Visibility')
                        ->helperText('Enable or disable category visibility')
                        ->default(true),

                    Select::make('parent_id')
                        ->relationship('parent', 'name')
                    ])
                ])
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('name')
                ->sortable()
                ->searchable(),

                TextColumn::make('parent.name')
                ->label('Parent')
                ->sortable()
                ->searchable(),

                IconColumn::make('is_visable')
                ->label('Visibility')
                ->boolean()
                ->sortable(),

                TextColumn::make('updated_at')
                ->date()
                ->label('Updated date')
                ->sortable(),


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
            RelationManagers\ProductsRelationManager::class,
        ];
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
