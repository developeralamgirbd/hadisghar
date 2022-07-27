<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public $slug;
    public function categoryPost($slug){
        $category_id = Category::withoutTrashed()->where('slug', $slug)->select('id')->first();
        $posts = Post::withoutTrashed()->with(['category:category_name', 'user:name'])->where('category_id', $category_id->id)->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(18);
        return view('post.category-posts',compact('posts'));
    }

    public function view(Request $request, $slug){
        try {
            $this->slug = $slug;
            $post_id = Post::withoutTrashed()->where('slug', $slug)->select('id')->first();
            $post_cat_ids = Post::withoutTrashed()->where('slug', $slug)
                ->select('category_id')->get();

            $previous = Post::withoutTrashed()->where('id', '<', $post_id->id)->where('status', 'published')
                ->select('title', 'slug')
                ->orderBy('id', 'desc')->first();

            $next = Post::withoutTrashed()->where('id', '>', $post_id->id)->where('status', 'published')->select('title', 'slug')->orderBy('id', 'asc')->first();

            $related_post = Post::withoutTrashed()->with('category:id,category_name')
                ->where('status', 'published')
                ->whereIn('category_id', $post_cat_ids)
                ->where('slug', '!=', $slug)
                ->select('id','title', 'slug')->take(8)->latest('id')->get();

            $popular_posts = Post::withoutTrashed()->orderBy('reads', 'DESC')
                ->where('status', 'published')->where('created_at', '>=', Carbon::now()->subDays(30))
                ->select('title', 'slug')
                ->take(8)->get();

            $posts = Post::withoutTrashed()->with('category:id,category_name')
                ->select('id','title','description','meta_description', 'category_id', 'feature_img', 'status','slug','reads', 'updated_at')
                ->where('slug', $this->slug)->where('status', 'published')->first();

            SEOMeta::setTitle($posts->title);
            SEOMeta::setDescription($posts->meta_description);

            if(! Auth::check()){//guest user identified by ip
                $cookie_name = (Str::replace('.','',($request->ip())).'-'. $post_id->id);
            } else {
                $cookie_name = (Auth::user()->id.'-'. $post_id->id);//logged in user
            }

            if(Cookie::get($cookie_name) == ''){//check if cookie is set
                $cookie = cookie($cookie_name, '1', 60);//set the cookie
                $posts->reads =  $posts->reads + 1; //count the view
                $posts->update();
                return response()->view('post.single-post', compact('posts','previous', 'next','related_post', 'popular_posts'))->withCookie($cookie);
            } else {
                return view('post.single-post', compact('posts','previous', 'next','related_post', 'popular_posts'));
            }

        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(404);
            }

        }
    }

    public function search(Request $request){
        dd($request->all());
    }
}
