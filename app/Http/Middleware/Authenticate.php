<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Cache\RateLimiter;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $routeRole = \Illuminate\Support\Facades\Route::current()->middleware();
        if (! $request->expectsJson()){
            if(isset($routeRole[4]) == "role:".$this->role()){
                return route('admin.login');
            }else{
                return route('login');
            }
        }
    }

    public function role (){
        $role = '';
        if (Schema::hasTable('roles')){
            $roles = Role::where('name', '!=', 'user')->pluck('name');
            if ($roles->count() > 0){
                $array = json_decode($roles, true);
                $array = array_values($array);
                $role = implode('|', $array);
            }
        }
        return $role;
    }
}
