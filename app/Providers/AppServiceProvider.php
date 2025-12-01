<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Aws\S3\S3Client;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Order Observer
        Order::observe(OrderObserver::class);
        
        // Configure S3 for Cloudflare R2 with SSL verification disabled
        Storage::extend('r2', function ($app, $config): FilesystemAdapter {
            $client = new S3Client([
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'endpoint' => $config['endpoint'],
                'use_path_style_endpoint' => false,
                'http' => [
                    'verify' => false, 
                ],
            ]);

            $adapter = new AwsS3V3Adapter(
                $client,
                $config['bucket'],
                '',
                new PortableVisibilityConverter()
            );
            
            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}


