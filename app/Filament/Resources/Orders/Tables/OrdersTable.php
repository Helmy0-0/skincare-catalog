<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Models\Order;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_date')->label('Tanggal Order'),
                TextColumn::make('user.name')->label('Nama Pelanggan'),
                TextColumn::make('total_amount')->label('Total')->money('idr'),
                SelectColumn::make('status')
                    ->options([
                        'proses' => 'Proses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai'
                    ])
                    ->disablePlaceholderSelection()
                    ->updateStateUsing(fn($state, $record) => $record->update(['status' => $state])),
                TextColumn::make('discount')->label('Diskon')->money('idr'),
                TextColumn::make('final_total')->label('final total')->money('idr'),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewDetails')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn(Order $record): string => 'Detail Pesanan #' . $record->id)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn(Order $record): \Illuminate\Contracts\View\View => view('filament.resources.orders.order-details', [
                        'order' => $record->load(['items.product', 'user'])
                    ]))
                    ->modalWidth('4xl'),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
