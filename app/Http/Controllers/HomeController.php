<?php
namespace App\Http\Controllers;
use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
class HomeController extends Controller
{
    public function home(){
       $currentPage = request()->get('page', 1);
//        $posts = Cache::remember('posts-'. $currentPage,300, function (){
//           return Post::withoutTrashed()->with('category:category_name')->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(10);
//        });
      $posts = Post::withoutTrashed()->with('category:category_name')->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(10);
        SEOMeta::setTitle('Home');
        return view('home',compact('posts'));
    }
    public function dropbox(){
        Artisan::call('backup:run --only-db');
        $backup = Artisan::output();
        return Inertia::render('Dropbox', [
            'backup' =>$backup
        ]);
    }
}
