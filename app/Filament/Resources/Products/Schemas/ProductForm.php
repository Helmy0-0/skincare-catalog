<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(500),
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->integer(),
                FileUpload::make('image')
                    ->label('Product Image')
                    ->nullable()
                    ->directory('products')
                    ->maxSize(5120)
                    ->getUploadedFileUsing(function ($component, $file, $storedFileNames) {
                       $record = null;

                        try {
                            $livewire = $component->getLivewire();

                            if (method_exists($livewire, 'getRecord')) {
                                $record = $livewire->getRecord();
                            } elseif (isset($livewire->record)) {
                                $record = $livewire->record;
                            }
                        } catch (\Throwable $e) {
                        }

                        if ($record && isset($record->image_url) && filled($record->image_url)) {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => $record->image_url,
                            ];
                        }

                        $base = config('filesystems.disks.s3.url') ?: env('AWS_URL');

                        if ($base) {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => rtrim($base, '/') . '/' . ltrim($file, '/'),
                            ];
                        }

                        return null;
                    }),
            ]);
    }
}
