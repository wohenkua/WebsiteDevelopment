<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;


Class ImageService{
    /**
     *
     *预留类，用于处理图像相关服务
     * 
     */
    public function createBase64Image($image){
        $encoded_image = 'data:image/png;base64, '.$image;
        $image_name = \Illuminate\Support\Str::uuid() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put($image_name, base64_decode($image));
        $file_path = \Illuminate\Support\Facades\Storage::disk('public')->path($image_name);
        $file_url = \Illuminate\Support\Facades\Storage::disk('public')->url($image_name);
        $file_size = \Illuminate\Support\Facades\Storage::disk('public')->size($image_name);
        $file_mime_type = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($image_name);
        $file_last_modified = \Illuminate\Support\Carbon::createFromTimestamp(\Illuminate\Support\Facades\Storage::disk('public')->lastModified($image_name))->toDateTimeString();

        return response()->json([
            'name' => $image_name,            
            'path' => $file_path,
            'url' => $file_url,
            'size' => $file_size,
            'mime_type' => $file_mime_type,
            'last_modified' => $file_last_modified
        ]);
    }
}