<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function download(string $file)
    {
        $path = storage_path() . '/' . 'app' . '/files/' . $file;
        if (file_exists($path)) {
            return \Response::download($path);
        }
    }
}
