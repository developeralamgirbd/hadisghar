<?php
namespace App\Http\Controllers\Admin\Post;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostContentImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
class PostController extends Controller
{
    public $posts;
    public function index(Request $request){
        $trashed = Post::onlyTrashed()->count();
        $total = Post::count();
        $draft = Post::where('status','draft')->count();
        $pending = Post::where('status','pending')->count();
        $published = Post::where('status','published')->count();
        if ($request->has('status')){
            $this->posts = Post::with(['user:id,name', 'category:id,category_name'])->select('id','title', 'category_id', 'user_id', 'status','slug', 'created_at')->where('status', 'LIKE', '%'.$request->status.'%')->orderBy('id', 'desc')->get();
        }else{
            $this->posts = Post::with(['user:id,name', 'category:id,category_name'])->select('id','title', 'category_id', 'user_id', 'status','slug', 'created_at')->orderBy('id', 'desc')->get();
        }
        return Inertia::render('Admin/Posts/Manage', [
            'posts' => $this->posts,
            'trashed' => $trashed,
            'draft' => $draft,
            'pending' => $pending,
            'published' => $published,
            'total' => $total,
        ]);
    }
    public function review($slug){
        try {
            $post = Post::where('slug', $slug)->first();
            return view('post.review', compact('post'));
        }catch (\Exception $e){
            if (config('app.debug') === false){
                echo $e->getMessage();
            }else{
                return abort(404);
            }
        }


    }
    public function create(){
        $categories = Category::select('id', 'category_name')->get();
        return Inertia::render('Admin/Posts/Create', [
            'categories' => $categories
        ]);
    }
    public function store(Request $request){
        try {
            $post = Post::create([
                'title' => $request->title,
                'slug' => Str::slug($request->slug),
                'meta_description' => $request->meta_description,
                'description' => $request->description,
                'category_id' => $request->category,
                'user_id' => Auth::id(),
            ]);
            return response()->json($post);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function edit($id){
        try {
            $categories = Category::withoutTrashed()->get();
            $post = Post::with('category:id,category_name', 'user:id,name')->where('id', $id)->first();
            return Inertia::render('Admin/Posts/Edit', [
                'post' => $post,
                'categories' => $categories,
            ]);
        }catch (\Exception $e){
            if (config('app.debug') === false){
                echo $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
    public function draftUpdate(Request $request, $id){
        try {
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->slug = Str::slug($request->slug);
            $post->meta_description = $request->meta_description;
            $post->description = $request->description;
            $post->category_id = $request->category;
            $post->user_id = Auth::id();
            $post->update();
            return response()->json(['update_time' => $post->updated_at]);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|unique:posts,title,'.$id,
            'description' => 'required',
            'slug' => 'required|unique:posts,slug,'.$id,
            'category' => 'required',
            'photo' => 'mimes:jpg,jpeg,png|max:10240',
        ]);
        try {
            $uploadPath = $request->old_feature;
            if ($request->hasFile('photo')){
                $image = $request->file('photo');
                $imageExt = $request->file('photo')->getClientOriginalExtension();
                $imageName = hexdec(uniqid()). '.'.$imageExt;
                $path = 'images/feature/';
                Image::make($image)->save($path.$imageName, 70);
                if (file_exists(public_path($request->old_feature))){
                    File::delete(public_path($request->old_feature));
                }
                $uploadPath = '/'.$path.$imageName;
            }
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->description = $request->description;
            $post->slug = Str::slug($request->slug);
            $post->category_id = $request->category;
            $post->user_id = Auth::id();
            $post->feature_img = $uploadPath;
            $post->created_at = Carbon::now();
            if (Auth::user()->can('published post')){
                $post->status = $request->status;
            }else{
                $post->status = 'pending';
            }
            $post->update();
            /********************
            - below code is find images name from request from description
             ********************/
            $dom = new \DOMDocument();
            $dom->loadHTML($request->description);
            $images = array();
            $request_image = array();
            foreach ($dom->getElementsByTagName('img') as $tag){
                $images[] = $tag->getAttribute('src');
            }
            foreach ($images as $image){
                $request_image[] =  Str::remove('/uploads/',  $image);
            }
            /********************
            - below code is find images name from request form description
             ********************/
            $all_post = Post::select('description')->get();
            $postDescImage_src = array();
            foreach ($all_post as $db_post){
                $dom_forPostDesc = new \DOMDocument();
                $dom_forPostDesc->loadHTML($db_post->description);
                $postDescImages = array();
                foreach ($dom_forPostDesc->getElementsByTagName('img') as $tag){
                    $postDescImages[] = $tag->getAttribute('src');
                }
                foreach ($postDescImages as $image){
                    $postDescImage_src[] =  Str::remove('/uploads/',  $image);
                }
            }
            /********************
            - below code is merge two array which are post description finded image array and request form description finded images array
             ********************/
            $dbSetImage = Arr::collapse([$postDescImage_src, $request_image ]);
            /********************
            - below query is find images name without post table description images and request images
             ********************/
            $postContentImage = PostContentImage::whereNotIn('image',$dbSetImage)->get();
            $notUploadImages = array();
            foreach ($postContentImage as $image){
                $notUploadImages[] = $image->image;
            }
            foreach ($notUploadImages as $image){
                if (file_exists(public_path('uploads/'.$image) )){
                    File::delete(public_path('uploads/'.$image));
                }
            }
            $postContentImage->each->delete();
            return redirect()->route('admin.post.index');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function destroy($id){
        try {
            $post = null;
            if (Auth::user()->can('delete others post')){
                $post = Post::findOrFail($id);
            }else{
                $post = Post::where('user_id', Auth::id())->first();
            }
            $post->delete();
            return redirect()->back();
        }catch (\Exception $e){
        }
    }
    public function trashed(){
        $trashed = Post::with('category','user')->onlyTrashed()->get();
        return Inertia::render('Admin/Posts/Trashed', [
            'trashed' => $trashed,
        ]);
    }
    public function restore($id){
        try {
            $post = Post::onlyTrashed()->where('id', $id)->first();
            $post->restore();
            return redirect()->route('admin.post.index');
        }catch (\Exception $e){
            if (env("APP_DEBUG") === true){
                echo $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
    public function permanentlyDestroy($id){
        try {
            $post = Post::onlyTrashed()->where('id', $id)->first();
            $dom = new \DOMDocument();
            $dom->loadHTML($post->description);
            $images = array();
            $src = array();
            foreach ($dom->getElementsByTagName('img') as $tag){
                $images[] = $tag->getAttribute('src');
            }
            foreach ($images as $image){
                $src[] = $image;
            }
            foreach ($src as $file){
                if (file_exists(public_path($file))){
                    File::delete(public_path($file));
                }
            }
            $post->forceDelete();
            if (file_exists(public_path($post->feature_img))){
                File::delete(public_path($post->feature_img));
            }
            return redirect()->back();
        }catch (\Exception $e){
        }
    }
}
