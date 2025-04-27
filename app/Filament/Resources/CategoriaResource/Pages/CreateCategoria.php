<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getRedirectUrl(): string 
    {
        return $this->getResource()::getUrl('index', ['highlight' => 'new', 'timestamp' => now()->timestamp]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate()
    {
        try {
            Notification::make()
                ->title('Categoría creada')
                ->body('La categoría se ha creado con éxito.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al crear categoría')
                ->body($e->getMessage())
                ->danger()
                ->send();
                
            // Opcional: eliminar el registro si falla
            $this->record->delete();
        }
    }

    /* En este método se puede personalizar los botones de la vista para Crear Categoría */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Registrar')
                /* ->color('success') */,

            /* $this->getCreateAnotherFormAction()
            ->label('Guardar y nuevo'),
            */
            
            $this->getCancelFormAction()
                ->label('Cancelar')
        ];
    }
}
