<?php

namespace niallcusack\LaravelUppyS3MultipartUpload;

use Illuminate\Support\Facades\Facade;

/**
 * @see \niallcusack\LaravelUppyS3MultipartUpload\LaravelUppyS3MultipartUpload
 */
class LaravelUppyS3MultipartUploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-uppy-s3-multipart-upload';
    }
}
