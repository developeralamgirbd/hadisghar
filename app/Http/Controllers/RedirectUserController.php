<?php
namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
class RedirectUserController extends Controller
{
    public function authenticateRedirect(){
        $roles = Role::where('name', '!=', 'user')->get();
       foreach ($roles as $role){
           if(auth()->user()->hasRole($role)){
               return redirect()->route('admin.dashboard');
           }elseif (auth()->user()->hasRole('user')){
               return redirect()->route('user.dashboard');
           }
       }
    }
}
