<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Grid;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make('4')  // Create a 4-column grid
                    ->schema([
                        Section::make('Complaint Information')
                            ->icon('heroicon-o-information-circle')
                            ->iconColor('success')
                            ->description('This section contains the details of the complaint.')
                            ->schema([
                                Fieldset::make('Complaint Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->disabled()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('type')
                                            ->required()
                                            ->disabled(),
                                        Forms\Components\DatePicker::make('date')
                                            ->required()
                                            ->disabled(),
                                    ])
                                    ->columns(2),

                                Fieldset::make('Content')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('complaints')
                                            ->visibility('public')
                                            ->required()
                                            ->disabled(),
                                        Forms\Components\Textarea::make('description')
                                            ->required()
                                            ->columnSpanFull()
                                            ->disabled(),
                                    ])
                                    ->columns(1),

                                Fieldset::make('Complaint Code')
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->label('Complaint Code')
                                            ->disabled()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan(3),  // Takes up 3 columns (75% width)

                        Section::make('Status Information')
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->description('This section contains the.')
                            ->schema([
                                Fieldset::make('Status Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('complainant_name')
                                            ->label('Student Name')
                                            ->formatStateUsing(function ($state, $record) {
                                                return $record->is_anonymous ? 'Anonymous' : $record->student->user->name;
                                            })
                                            ->disabled(),
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'pending' => 'Pending',
                                                'progress' => 'In Progress',
                                                'resolved' => 'Resolved',
                                                'rejected' => 'Rejected',
                                            ])
                                            ->required(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan(1),  // Takes up 1 column (25% width)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Complaint Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.user.name')
                    ->label('Student Name')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->is_anonymous ? 'Anonymous' : $record->student->user->name
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->size(100),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'progress' => 'warning',
                        'pending' => 'info',
                        'resolved' => 'success',
                        'rejected' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'view' => Pages\ViewComplaint::route('/{record}'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
