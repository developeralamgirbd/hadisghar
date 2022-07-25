<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailUserCreate;
use App\Mail\UserUpdateNotify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
class UsersController extends Controller
{
    use Notifiable;
    public function index(){
        $users = User::with('roles:id,name')->select('id', 'name', 'email')->withCount('posts')->get();
        return Inertia::render('Admin/Users/Manage', [
            'users' => $users,
        ]);
    }
    public function create(){
        $roles = Role::select('id', 'name')->get();
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'user_role' => ['required', 'string']
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole($request->user_role);
            if ($user && $request->send_notification){
                $reset_token = Str::lower(Str::random(64));
                $password_resets = DB::table('password_resets')->where('email', $request->email);
                if ($password_resets->count() > 0){
                    $password_resets->update([
                        'token' => bcrypt($reset_token),
                        'created_at' => Carbon::now()
                    ]);
                }else{
                    DB::table('password_resets')->insert([
                        'email' => $request->email,
                        'token' => bcrypt($reset_token),
                        'created_at' => Carbon::now(),
                    ]);
                }
                $action_link = route('admin.password.reset',['token'=> $reset_token, 'email' => $request->email]);
//                Mail::to($request->email)->queue(new UserCreateNotify($request->user_role, $request->email, $action_link));
                $expire = config('auth.passwords.users.expire');
               dispatch(new SendEmailUserCreate($request->user_role, $request->email, $action_link, $expire));
            }
            return redirect()->route('admin.user.index');
        }catch (\Exception $e){
            if (env('APP_DEBUG') === true){
                echo $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
    public function edit($id){
        $user = User::with('roles')->where('id', $id)->first();
        $roles = Role::select('id', 'name')->get();
        $role_user = '';
       foreach ($user->roles as $role){
           $role_user .= $role->name;
       }
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'role' => $role_user
        ]);
    }
    //    Update
    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'user_role' => ['required', 'string']
        ]);
        try {
            $user = User::findOrFail($id);
            if ($user){
               $user->name = $request->name;
               $user->email = $request->email;
               $user->update();
               $user->removeRole($user->getRoleNames()[0]);
               $user->assignRole($request->user_role);
                Mail::to($request->email)->queue(new UserUpdateNotify($request->user_role, $request->email,$request->name));
            }
            return redirect()->back();
        }catch (\Exception $e){
           echo $e->getMessage();
        }
    }
    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    }
}
