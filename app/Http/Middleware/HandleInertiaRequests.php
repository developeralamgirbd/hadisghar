<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

        $role = '';
        $name = '';
        $permisions = null;
        $active_user_id = null;
        if (Auth::check()){
            $role = auth()->user()->getRoleNames()[0];
            $name = auth()->user()->name;
            $active_user_id = Auth::id();
            $permisions = auth()->user()->getAllPermissions();
        }

        return array_merge(parent::share($request), [
            'user_role_check' => $role,
            'user_name' => $name,
            'active_user_id' => $active_user_id,
            'userPermissions' => $permisions,
            'csrf_token' => csrf_token(),
            'app_name' => config('app.name'),
            'flash' => [
                'status' => fn() => $request->session()->get('status'),
                'msg' => fn() => $request->session()->get('msg'),
                'retries' => fn() => $request->session()->get('retries'),
                'status_error' => fn() => $request->session()->get('status_error'),
                'backup_status' => fn() => $request->session()->get('backupStatus'),
                'output' => fn() => $request->session()->get('output'),
            ],
        ]);
    }


}
