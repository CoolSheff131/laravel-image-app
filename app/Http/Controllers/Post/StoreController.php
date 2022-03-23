<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'];
        unset($data['images']);
        $post = Post::firstOrCreate($data);
        foreach ($images as $image) {
            $name = Carbon::now() . '_' . $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
            $filepath = Storage::disk('public')->putFileAs('/images', $image, $name);
            Image::create([
                'path' => $filepath,
                'url' => url('/storage/' . $filepath),
                'post_id' => $post->id,
            ]);
        }

        return response()->json(['message' => 'success']);
    }
}
