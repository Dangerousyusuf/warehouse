<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class ImageController extends Controller
{
    public function uploadImages($images)
    {
        $imagePaths = [];
        foreach ($images as $image) {
            $resizedImage = Image::make($image);
            $year = now()->format('Y');
            $month = now()->format('m');
            $directory = "images/$year/$month";
            $imagePaths[] = Storage::disk('public')->put($directory, $resizedImage); // Resimleri 'public' diskine, yıl ve ay klasöründe yükle
        }
        return $imagePaths;
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('images');
        $imageName = time() .'.'. $image->getClientOriginalName();

        $image->move(public_path('storage/images'), $imageName);
       
        $imageManager = new ImageManager(new Driver());

        $thumbImage = $imageManager->read('storage/images/' . $imageName);

        $thumbImage->resize(400, 400);


        $thumbImage->save(public_path('storage/images/thumbnails' . $imageName));
    
        return response()->json([
            'success' => true,
            'message' => 'Resim Başarıyla Yüklendi',
            'image' => $imageName
        ]);
        
    }
}
