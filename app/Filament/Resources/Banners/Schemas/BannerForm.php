<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('benners')
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
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false),

            ]);
    }
}
