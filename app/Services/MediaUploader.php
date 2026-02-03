<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Media;

class MediaUploader
{
  public function upload(string $folder, UploadedFile $file )
  {
    $newFolder = $folder.'/'.date('Y/m');
    $pathname = Storage::disk('uploads')->put($newFolder ,$file );
    $url = Storage::disk('uploads')->url($pathname);
    $extension = $file->extension();
    $filename = str_replace(  $newFolder.'/', '', $pathname );

    $media = Media::create([
        'file_name' => $filename,
        'file_path' => $pathname,
        'file_size' => $file->getSize(),
        'extension' => $extension,
        'mime_type' => $file->getMimeType(),
    ]);
    $media->url = $url;
    return $media;
  }
}
