<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyprogramResource\Pages;
use App\Models\Studyprogram;
use App\Models\Majoring;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class StudyprogramResource extends Resource
{
    protected static ?string $model = Studyprogram::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Study Programs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Study Program Information')
                    ->schema([
                        Forms\Components\Select::make('majoring_id')
                            ->relationship('majoring', 'name_majoring')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->label('Majoring'),

                        Forms\Components\TextInput::make('name_studyprogram')
                            ->required()
                            ->maxLength(255)
                            ->label('Study Program Name')
                            ->placeholder('Enter study program name'),

                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('name_studyprogram')
                    ->label('Study Program')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('majoring.name_majoring')
                    ->label('Majoring')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('majoring_id')
                    ->relationship('majoring', 'name_majoring')
                    ->label('Filter by Majoring')
                    ->preload()
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyprograms::route('/'),
            'create' => Pages\CreateStudyprogram::route('/create'),
            'edit' => Pages\EditStudyprogram::route('/{record}/edit'),
        ];
    }
}
