<?php
namespace App\Http\Controllers\Admin\Security;
use App\Http\Controllers\Controller;
use App\Models\AdminLoginUrl;
use Illuminate\Http\Request;
use Inertia\Inertia;
class BruteForceController extends Controller
{
    public function index(){
        $admin_login_url = AdminLoginUrl::select('id', 'admin_login_url')->first();
        return Inertia::render('Admin/Security/BruteForce', [
            'admin_url' => $admin_login_url
        ]);
    }
    public function update(Request $request, $id){
        $request->validate([
            'adminLoginUrl' => 'required|max:200|min:5|unique:admin_login_urls,admin_login_url,'.$id
        ]);
        $admin_url = AdminLoginUrl::findOrFail($id);
        $admin_url->admin_login_url = $request->adminLoginUrl;
        $admin_url->update();
        return redirect()->back();
    }
}
