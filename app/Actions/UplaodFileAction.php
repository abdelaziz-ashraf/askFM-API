<?php

namespace App\Actions;
use Illuminate\Http\UploadedFile;

class UplaodFileAction {

    public function handle(UploadedFile $file) {
        $fileName = time() . '-' . $file->extension();
        $file->move(public_path('images'), $fileName);
        return $fileName;
    }

}
