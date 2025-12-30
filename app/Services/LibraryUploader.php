<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Library;

class LibraryUploader
{
  public function upload(string $folder, UploadedFile $file )
  {
    $newFolder = $folder.'/'.date('Y/m');
    $pathname = Storage::disk('uploads')->put($newFolder ,$file );
    $url = Storage::disk('uploads')->url($pathname);
    $extension = $file->extension();
    $filename = str_replace(  $newFolder.'/', '', $pathname );
    $library = Library::create([
        'name' => $filename,
        'pathname' => $pathname,
        'extension' => $extension
    ]);
    $library->url = $url;
    return $library;
  }
}
