<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PostContentImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
class TynimceController extends Controller
{
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'mimes:jpeg,jpg,png'
        ]);
        $image = $request->file('file');
        $name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $path = 'images/';
        Image::make($image)->save($path.$name, 50);
        PostContentImage::create(['image' => $name]);
        return response()->json(["location" => '/'.$path.$name]);
    }
}
