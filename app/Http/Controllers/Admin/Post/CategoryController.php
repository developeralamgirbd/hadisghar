<?php
namespace App\Http\Controllers\Admin\Post;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
class CategoryController extends Controller
{
    public function index(Request $request){
        try {
            $trashed = Category::onlyTrashed()->count();
            $categories = Category::with('posts')->select('id','category_name', 'slug')->get();
            return Inertia::render('Admin/Posts/Category', [
                'categories' => $categories,
                'trashed' => $trashed,
            ]);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function create(){
    }
    public function store(Request $request){
        $request->validate([
            'category_name' => 'required|unique:categories,category_name',
            'slug' => 'required|unique:categories,slug',
        ]);
        try {
            $category = Category::create([
                'category_name' => $request->category_name,
                'slug' => $request->slug,
            ]);
            return redirect()->route('admin.category.index');
//            return Inertia::render('Admin/Posts/Category', [
//               'categories' => $category
//            ]);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function update(Request $request, $id){
        $request->validate([
            'category' => 'required|unique:categories,category_name,'.$id,
            'categorySlug' => 'required|unique:categories,slug,'.$id,
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->category_name = $request->category;
            $category->slug = $request->categorySlug;
            $category->update();
            return redirect()->route('admin.category.index');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function destroy($id){
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return redirect()->back();
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function trashed(){
        $trashed = Category::with('posts')->onlyTrashed()->get();
        return Inertia::render('Admin/Posts/CategoryTrashed', [
            'trashed' => $trashed,
        ]);
    }
    public function restore($id){
        try {
            $post = Category::onlyTrashed()->where('id', $id)->first();
            $post->restore();
            return redirect()->route('admin.category.index');
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
            $post = Category::onlyTrashed()->where('id', $id)->first();
            $post->forceDelete();
            return redirect()->back();
        }catch (\Exception $e){
        }
    }
}
