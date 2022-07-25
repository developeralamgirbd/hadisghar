<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
class PostController extends Controller
{
    public $slug;
    public function categoryPost($slug){
        $category_id = Category::withoutTrashed()->where('slug', $slug)->select('id')->first();
        $posts = Post::withoutTrashed()->with(['category:category_name', 'user:name'])->where('category_id', $category_id->id)->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(10);
        return view('post.category-posts',compact('posts'));
    }

    public function view($slug){
        try {
            $this->slug = $slug;
            $post = Post::withoutTrashed()->with('category:id,category_name')->select('id','title','description','meta_description', 'category_id', 'feature_img', 'status','slug', 'updated_at')->where('slug', $this->slug)->where('status', 'published')->first();
            SEOMeta::setTitle($post->title);
            SEOMeta::setDescription($post->meta_description);
            return view('post.single-post', compact('post'));
        }catch (\Exception $e){
            return abort(404);
        }
    }
}
