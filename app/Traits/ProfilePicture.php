<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait ProfilePicture
{
    public function uploadProfilePicture($file)
    {
        $filename = time() . $file->getClientOriginalName();
        $file->move('storage/account/', $filename);
        return $filename;
    }
}