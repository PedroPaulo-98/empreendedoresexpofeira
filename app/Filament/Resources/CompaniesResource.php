<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompaniesResource\Pages;
use App\Filament\Resources\CompaniesResource\RelationManagers;
use App\Models\Companies;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class CompaniesResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Companies::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Empresas'; // Altera o texto no menu
    protected static ?string $modelLabel = 'Empresa'; // Para uso singular
    protected static ?string $pluralModelLabel = 'Empresas'; // Para uso plural


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cnpj_cpf')
                        ->label('CNPJ/CPF')
                        ->numeric()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefone')
                        ->mask('(99) 99999-9999')
                        ->maxLength(15),
                    Forms\Components\TextInput::make('address')
                        ->label('Endereço (Real da empresa)')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('location')
                        ->label('Localização (Onde está localizada na Expofeira)')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('information')
                        ->label('Informações')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('amount_of_employment')
                        ->numeric()
                        ->label('Quantidade de Empregados')
                        ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cnpj_cpf')
                    ->label('CNPJ/CPF')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            //    Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
            //    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DailyDataRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompanies::route('/create'),
            'edit' => Pages\EditCompanies::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'visualizar',
            'visualizar_todos',
            'criar',
            'atualizar',
            'deletar',
            'deletar_todos',
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if ($user && $user->hasRole('empresa')) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
