<?php
namespace App\Http\Controllers;
use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
class HomeController extends Controller
{
    public function home(Request $request){
        $search = $request->query('search');
        if ($search){
           $posts = Post::withoutTrashed()->where('title', 'LIKE', "%{$search}%")->where('status', 'published')->paginate(18);
        }else{
            $posts = Post::withoutTrashed()->with('category:category_name')->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(18);
        }

        SEOMeta::setTitle('হোম');
        return view('home',compact('posts'));
    }
}
