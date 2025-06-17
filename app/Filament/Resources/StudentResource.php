<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Students';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Pilih user yang berperan sebagai mahasiswa, atau isi data mahasiswa baru.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Mahasiswa')
                            ->options(fn () => \App\Models\User::where('role', 'student')->pluck('name', 'id'))
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->label('Name')->required(),
                                Forms\Components\TextInput::make('email')->label('Email')->email()->required()->unique(),
                                Forms\Components\TextInput::make('password')->label('Password')->password()->required(),
                            ])
                            ->createOptionAction(function ($action) {
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['role'] = 'student';
                                    $data['password'] = Hash::make($data['password']);
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
                        Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nim')->label('NIM')->required(),
                        Forms\Components\TextInput::make('phone_number')->label('Phone Number')->required(),
                        Forms\Components\TextInput::make('address')->label('Address')->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nim')->label('NIM')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('majoring.name_majoring')->label('Majoring')->searchable()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'student' => 'Student'
                    ])
                    ->query(fn ($query) => $query->whereHas('user', fn ($q) => $q->where('role', 'student'))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Model $record) {
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('user', function ($query) {
            $query->where('role', 'student');
        });
    }
}
