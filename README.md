# Files upload

Package is created for speedup managing upload of files to Larave storage

#Install

    composer require shortcodes/files-upload
    
# Usage

Below there is a list of instructions how to use package

###Settings

Publish config file of module 
    
    php artisan vendor:publish --provider="Shortcodes\FilesUpload\FilesUploadPackageProvider"
    
Now you can modify settings of module in ```config/upload.php```

###Routes
Add package routes to your routes file using predefined method:

    Route::uploadRoutes()


    
It creates two routes

    POST /files         - to add new files to tmp directory and
    GET /files/{url}    - to view files
    
If file is image you can generate thumbnail using request query string parameters ```width``` and ```hright```

###Model

To be able to use package in module it is nessesary to add ```CanUploadFiles``` trait to model

    class YourModel extends Model{
        use CanUploadFiles;
        
        ...
    }

And provide ```fileFields``` property as array of fields that can be uploadable