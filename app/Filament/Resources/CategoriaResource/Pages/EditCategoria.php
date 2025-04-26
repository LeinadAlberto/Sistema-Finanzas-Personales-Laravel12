<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getRedirectUrl(): string 
    {
        return $this->getResource()::getUrl('index', ['layout' => 'default']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Categoría eliminada')
                        ->body('"La categoría se ha eliminado con éxito.')
                        ->success()
                )
                ->after(function () {
                    $this->redirect(
                        $this->getResource()::getUrl('index'),
                        navigate: true  // Crucial para mantener el layout
                    );
                }),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function afterSave()
    {
        Notification::make()
            ->title('Categoría actualizada')
            ->body('La categoría se actualizó con éxito.')
            ->success()
            ->send();
    }

}
