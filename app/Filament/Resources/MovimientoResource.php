<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoResource\Pages;
use App\Filament\Resources\MovimientoResource\RelationManagers;
use App\Models\Movimiento;
use App\Models\User;
use App\Models\Categoria;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->columns(2) // El formulario principal tiene 2 columnas
            ->schema([
                Card::make('Llene los campos del formulario')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->required()
                                    ->options(User::all()->pluck('name', 'id')),
                                Forms\Components\Select::make('categoria_id')
                                    ->label('Categoría')
                                    ->required()
                                    ->options(Categoria::all()->pluck('nombre', 'id')),
                                Forms\Components\Select::make('tipo')
                                    ->required()
                                    ->options([
                                        'ingreso' => 'Ingreso',
                                        'gasto' => "Gasto"
                                    ]),
                                Forms\Components\TextInput::make('monto')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\RichEditor::make('descripcion')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('movimientos'),
                                Forms\Components\DatePicker::make('fecha')
                                    ->required(),
                            ])
                            ->columns(2) // Grid interno de 2 columnas
                    ])
                    ->columnSpan(2) // La Card ocupa las 2 columnas del formulario
            ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('N°')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
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
                Tables\Columns\TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                /* Tables\Columns\TextColumn::make('descripcion')  
                    ->limit(50)
                    ->html()
                    ->searchable()
                    ->sortable(), */
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable()
                    ->width(100)
                    ->height(100),
                    
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options([
                        'ingreso' => 'Ingreso',
                        'gasto' => 'Gasto'
                    ])
                    ->placeholder('Filtrar por tipo de categoría')
                    ->label('Tipo')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('success'),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Movimiento eliminado')
                            ->body('"El movimiento se ha eliminado con éxito.')
                            ->success()
                    )
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
            'index' => Pages\ListMovimientos::route('/'),
            'create' => Pages\CreateMovimiento::route('/create'),
            'edit' => Pages\EditMovimiento::route('/{record}/edit'),
        ];
    }
}
