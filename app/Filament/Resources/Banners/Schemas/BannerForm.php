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
                ->disk('public')
                ->directory('banners')
                ->visibility('public')
                ->required(),

                Toggle::make('is_active')
                ->label('Active')
                ->default(false),

                // TextInput::make('order')
                // ->numeric()
                // ->default(0)
            ]);
    }
}
