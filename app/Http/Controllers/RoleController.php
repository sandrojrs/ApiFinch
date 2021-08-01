<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
  

/**
 * @group Role
 *
 * @authenticated
 */

class RoleController extends Controller
{
   
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    
    public function index()
    {
        $roles = Role::orderBy('id','DESC')->paginate();
        return response()->json([
          'success' => true,       
          'data' => $roles
        ], Response::HTTP_OK);
    }
    
    
    public function create()
    {
        $permission = Permission::get();
        return response()->json([
          'success' => true,         
          'data' => $permission
        ], Response::HTTP_OK);
    }
    
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        try {
          $role = Role::create(['name' => $request->input('name')]);
          $role->syncPermissions($request->input('permission'));
    
          return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role
          ], Response::HTTP_OK);         
        } catch (\Throwable $th) {
            return response()->json([
              'success' => false,
              'message' =>  $th->getMessage()
            ]);
        }
    

    }
    
    public function show(Role $role)
    {
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$role->id)
            ->get();

        $data = ['role'=> $role, 'permissions' =>$rolePermissions];

          return response()->json([
            'success' => true,
            'message' => 'Role show successfully',
            'data' => $data
          ], Response::HTTP_OK);
  
    }
    
   
    public function edit($id)
    {
      try {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return response()->json([
          'success' => true,         
          'data' => ['permission'=>$permission , 'role' => $role, 'rolePermissions' => $rolePermissions]
        ], Response::HTTP_OK);

      } catch (\Throwable $th) {
          return response()->json([
          'success' => true,         
          'data' => $th->getMessage()
        ], 202);
      }
       
    }
    
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        try {

         $default_roles = config('defaults.roles');
        if (in_array($role->name, $default_roles)) {
            foreach($default_roles as $roleName) {
                if ($role->name == $roleName && $request->input('name') != $roleName) {
                    return response()
                        ->json([
                            'success' => true,
                            'message' => 'it is not possible to update a default group',
                        ]);
                }
            }
        }
        $role->name = $request->input('name');
        $role->save();    
        $role->syncPermissions($request->input('permission'));

        return response()->json([
          'success' => true,
          'data' => $role,
          'message' => 'Role updated successfully'
      ], Response::HTTP_OK);        
        } catch (\Throwable $th) {
          return response()
            ->json([
              'success' => false,
              'message' => $th->getMessage(),
          ]);
        }
    }
    
    public function destroy(Role $role)
    {

      $default_roles = config('defaults.roles');
      if (in_array($role->name, $default_roles)) {
          return response()->json([
              'success' => false,
              'message' => 'it is not possible to remove a default group',
          ]);
      }

      try {
          $role->delete();
          return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ], Response::HTTP_OK);
      } catch (\Throwable $th) {
          return response()
          ->json([
            'success' => false,
            'message' => $th->getMessage(),
        ]);
      }
  
    }
}