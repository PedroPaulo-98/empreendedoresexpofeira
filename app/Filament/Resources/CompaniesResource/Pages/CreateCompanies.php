<?php

namespace App\Filament\Resources\CompaniesResource\Pages;

use App\Filament\Resources\CompaniesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Companies;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class CreateCompanies extends CreateRecord
{
    protected static string $resource = CompaniesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Companies::where('user_id', Auth::id())->exists()) {
            Notification::make()
                ->title('Você já possui uma empresa cadastrada')
                ->body('Não é permitido cadastrar mais de uma empresa por usuário.')
                ->danger()
                ->send();

            throw new Halt();
        }

        $data['user_id'] = Auth::id();

        return $data;
    }
}
