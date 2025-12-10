<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ExportAction;
use App\Filament\Exports\OrderExporter;

class ZLatestOrders extends TableWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Latest Order';
    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                return Order::query()->latest();
            })
            ->columns([
                TextColumn::make('order_date')
                    ->label('Tanggal Order')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'proses' => 'warning',
                        'dikirim' => 'info',
                        'selesai' => 'success',
                        default => 'gray'
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()->exporter(OrderExporter::class),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
