<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LecturerResource\Pages;
use App\Filament\Resources\LecturerResource\RelationManagers;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Pilih user yang berperan sebagai dosen, atau isi data dosen baru.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Dosen')
                            ->options(fn () => \App\Models\User::where('role', 'lecturer')->pluck('name', 'id'))
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->label('Name')->required(),
                                Forms\Components\TextInput::make('email')->label('Email')->email()->required()->unique(),
                                Forms\Components\TextInput::make('password')->label('Password')->password()->required(),
                            ])
                            ->createOptionAction(function ($action) {
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['role'] = 'lecturer';
                                    $data['password'] = bcrypt($data['password']);
                                    return $data;
                                });
                            })
                            ->createOptionUsing(function (array $data) {
                                return \App\Models\User::create($data)->getKey();
                            })
                            ->required(),
                        Forms\Components\Select::make('majoring_id')
                            ->label('Majoring')
                            ->options(fn () => \App\Models\Majoring::pluck('name_majoring', 'id'))
                            ->searchable()
                            ->required(),
                        // Forms\Components\Select::make('gender')
                        //     ->label('Gender')
                        //     ->options([
                        //         'male' => 'Male',
                        //         'female' => 'Female',
                        //     ])
                        //     ->required(),
                        Forms\Components\TextInput::make('nip')->label('NIP')->required(),
                        Forms\Components\TextInput::make('phone_number')->label('Phone Number')->required(),
                        // Forms\Components\TextInput::make('address')->label('Address')->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Profile Image')
                            ->image()
                            ->directory('lecturer-profiles')
                            ->imagePreviewHeight('100')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Profile')
                    ->circular()
                    ->height(48)
                    ->width(48),
                Tables\Columns\TextColumn::make('nip')->label('NIP')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('address')->label('Address')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'lecturer' => 'Lecturer'
                    ])
                    ->query(fn ($query) => $query->whereHas('user', fn ($q) => $q->where('role', 'lecturer'))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        $record->user?->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            $records->each(fn ($record) => $record->user?->delete());
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('user', function ($query) {
            $query->where('role', 'lecturer');
        });
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
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
        ];
    }
}
