<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Inertia\Concerns\ConfirmsTwoFactorAuthentication;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class AdminController extends Controller
{
    use ConfirmsTwoFactorAuthentication;
    public function __construct(){
        $role = Role::where('name', 'admin')->first();
        $permission = Permission::get();
        $role->givePermissionTo($permission);
    }
    public function login(){
        $roles = Role::where('name', '!=', 'user')->get();
        foreach ($roles as $role){
            if (Auth::check()){
                if(auth()->user()->hasRole($role)){
                    return redirect()->route('admin.dashboard');
                }
            }else{
                return Inertia::render('Admin/Login');
            }
        }
    }
    public function dashboard(){
      return Inertia::render('Admin/Dashboard', [
          'total_posts' => Post::withoutTrashed()->count(),
          'pending_posts' => Post::withoutTrashed()->where('status', 'pending')->count(),
          'published_posts' => Post::withoutTrashed()->where('status', 'published')->count(),
          'draft_posts' => Post::withoutTrashed()->where('status', 'draft')->count(),
          'total_user' => User::all()->count(),
          'schedule' => Artisan::output(),
      ]);
    }
    public function show(Request $request)
    {
        $this->validateTwoFactorAuthenticationState($request);
        return Jetstream::inertia()->render($request, 'Admin/Profile/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            'sessions' => $this->sessions($request)->all()
        ]);
    }
    public function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }
        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);
            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }
    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}
