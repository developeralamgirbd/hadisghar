<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class MembersController extends Controller
{
    public function index(Request $request){
        $permissions = Permission::count();
        $roles = Role::with('permissions')->select('id', 'name')->get();
        return Inertia::render('Admin/Members/Manage', [
            'roles' => $roles,
            'totalPermissions' => $permissions,
        ]);
    }
    public function create(){
        $permissions = Permission::select('id', 'name')->get();
        return Inertia::render('Admin/Members/Create', [
            'permissions' => $permissions
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'role_name' => 'required|unique:roles,name'
        ]);
        try {
            $role = Role::create([
                'name' => Str::lower($request->role_name)
            ]);
            $role->givePermissionTo($request->permissionsArr);
            return redirect()->route('admin.role.index');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function edit($id){
        try {
            $rolePermissions = Role::with('permissions:id,name')->select('id', 'name')->where('id', $id)->get();
            $permissions = Permission::select('id', 'name')->get();
           return Inertia::render('Admin/Members/Edit', [
                'roleAndPermissions' => $rolePermissions,
                'permissions' => $permissions
            ]);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function update (Request $request, $id){
        $request->validate([
           'role_name' => 'required|unique:roles,name,'.$id
        ]);
        $role = Role::findOrFail($id);
        $role->name = $request->role_name;
        $role->save();
        $role->syncPermissions($request->permissionsArr);
        return redirect()->route('admin.role.index');
    }
    public function destroy($id){
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->route('admin.role.index');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}
