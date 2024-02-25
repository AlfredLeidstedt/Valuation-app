


// THIS CAN BE ADDED TO THE CREATE FORM WHEN RELATIONSHIP CONNECTIONG TO THE DEDUCTIONS. -
// THIS WILL ENABLE YOU TO CREATE A NEW DEDUCTION FROM THE RULES FORM. 


Forms\Components\Select::make('shop_customer_id')

->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email address')
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->unique(),

                    Forms\Components\TextInput::make('phone')
                        ->maxLength(255),

                    Forms\Components\Select::make('gender')
                        ->placeholder('Select gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                        ])
                        ->required()
                        ->native(false),
                ])


THIS IS THE WAY I NEED TO REFERENCE TO THE DEDUCTIONS TABLE SINCE DEDUCTION ID IS NOT A PROPERTY IN THE RULES TABLE.

                Forms\Components\Select::make('shop_product_id')
                    ->label('Product')
          ->>>          ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),