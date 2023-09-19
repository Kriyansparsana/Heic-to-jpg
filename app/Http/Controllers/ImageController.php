<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maestroerror\HeicToJpg;



class ImageController extends Controller
{
    public function convertHeicToJpeg(Request $request)
    {

        $image = $request->file('heicImage');
            $jpgFileName = "uploads/" . uniqid() . '.jpg';
        $converted = HeicToJpg::convert($image->getRealPath())->saveAs($jpgFileName);

        if (File::exists($jpgFileName)) {
            return response()->json(['convertedJpegPath' => asset($jpgFileName)]);
        } else {
            return response()->json(['error' => 'Failed to convert HEIC to JPEG.']);
        }
    }


}
