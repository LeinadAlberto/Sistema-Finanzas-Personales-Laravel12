<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoriaResource\Pages;
use App\Filament\Resources\CategoriaResource\RelationManagers;
use App\Models\Categoria;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Categoría';

    protected static string $layout = 'filament-panels::components.layout.index';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2) // El formulario principal tiene 2 columnas
            ->schema([
                Card::make('Llene los campos del formulario')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('nombre')
                                    ->required()
                                    ->label('Nombre de la categoría')
                                    ->placeholder('Ingrese el nombre de la categoría')
                                    ->unique(ignoreRecord: true) // Esto validará que el nombre sea único
                                    ->validationMessages([
                                        'unique' => 'Ya existe una categoría con este nombre.'
                                    ]) // Mensaje en español
                                    ->maxLength(255),
                                Select::make('tipo')
                                    ->options([
                                        'ingreso' => 'Ingreso',
                                        'gasto' => 'Gasto'
                                    ])
                                    ->label('Tipo de movimiento')
                                    ->required(),
                            ])
                            ->columns(2) // Grid interno de 2 columnas
                    ])
                    ->columnSpan(2) // La Card ocupa las 2 columnas del formulario
            ]);
    }

    public static function getModelLabel(): string
    {
        return 'Categorías'; // Esto cambiará el título principal
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Categoria::query())
            ->defaultSort('created_at', 'desc') // Orden por defecto (últimos primero)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('N°')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gasto' => 'danger',
                        'ingreso' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gasto' => 'Gasto',
                        'ingreso' => 'Ingreso',
                        default => $state,
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'gasto' => 'heroicon-o-arrow-down',
                        'ingreso' => 'heroicon-o-arrow-up',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->extraAttributes(fn (string $state): array => [
                        'class' => 'inline-block px-4 py-1 mx-auto', // Padding fijo
                        'style' => match ($state) {
                            'gasto' => 'box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.1), 0 2px 4px -1px rgba(220, 38, 38, 0.06);',
                            'ingreso' => 'box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.1), 0 2px 4px -1px rgba(5, 150, 105, 0.06);',
                        },
                    ])
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Creado en')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Actualizado en')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(function (Categoria $record) {
                // Solo resalta si la URL tiene ?highlight=new
                if (request()->query('highlight') === 'new') {
                    $latestRecord = Categoria::latest('created_at')->first();
                    if ($latestRecord && $record->id === $latestRecord->id) {
                        return 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500';
                    }
                }
                return null;
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCategorias::route('/'),
            'create' => Pages\CreateCategoria::route('/create'),
            'edit' => Pages\EditCategoria::route('/{record}/edit'),
        ];
    }
}
