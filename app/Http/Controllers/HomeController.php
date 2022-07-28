<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\PrivacyPolicy;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
class HomeController extends Controller
{
    public function home(Request $request){
        try {
            $search = $request->query('search');
            if ($search){
                $posts = Post::withoutTrashed()->where('title', 'LIKE', "%{$search}%")->where('status', 'published')->paginate(18);
            }else{
                $posts = Post::withoutTrashed()->with('category:category_name')->where('status', 'published')->select('id','title','description', 'feature_img','category_id', 'status','slug')->orderBy('id', 'desc')->paginate(18);
            }
            SEOMeta::setTitle('হোম');
            SEOMeta::setDescription('হাদিসঘর একটি ইসলামিক সাইট এখানে সকলপ্রকার সহী হাদিস প্রকাশ করা হয়। হাদিস গ্রন্থ সহী বুখারী শরীফ, সহী মুসলিম শরীফ, সহী সুনানে আবু দাউদ ইত্যাদি এবং মাসায়েল');
            return view('home',compact('posts'));
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort('404');
            }
        }
    }

    public function privacy(){
        try {
            $privacy = PrivacyPolicy::first();
            return view('privacy', compact('privacy'));
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort('404');
            }
        }
    }

}
