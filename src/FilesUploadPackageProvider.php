<?php

namespace Shortcodes\FilesUpload;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shortcodes\FilesUpload\Controllers\UploadController;

class FilesUploadPackageProvider extends ServiceProvider
{
    public function boot()
    {
        Route::macro('uploadRoutes', function () {
            Route::group(['prefix' => 'files'], function () {
                Route::post('/', [UploadController::class, 'store']);
            });
        });

        $this->publishes([
            __DIR__ . '/config/upload.php' => config_path('upload.php'),
        ]);
    }
}
