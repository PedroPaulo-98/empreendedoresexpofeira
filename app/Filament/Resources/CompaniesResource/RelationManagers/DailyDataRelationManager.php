<?php

namespace App\Filament\Resources\CompaniesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyDataRelationManager extends RelationManager
{
    protected static string $relationship = 'dailyData';

    protected static ?string $title = 'Dados diários';
    protected static ?string $navigationLabel = 'Dados diários';
    protected static ?string $modelLabel = 'Dados diários';
    protected static ?string $pluralModelLabel = 'Dados diários';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Data')
                    ->required(),
                Forms\Components\TextInput::make('daily_income')
                    ->label('Faturamento do dia')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('future_income')
                    ->label('Faturamento futuro')
                    ->maxLength(255),
                Forms\Components\Textarea::make('day_info')
                    ->label('Informações do dia')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date(),
                Tables\Columns\TextColumn::make('daily_income')
                    ->label('Faturamento do dia'),
                Tables\Columns\TextColumn::make('future_income')
                    ->label('Faturamento futuro'),
                Tables\Columns\TextColumn::make('day_info')
                    ->label('Informações do dia')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
            //    Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
            //    ]),
            ]);
    }
}
