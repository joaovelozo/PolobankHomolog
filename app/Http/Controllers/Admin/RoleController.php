<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $role = Role::create([
        'name' => $request->name,
       ]);

       $notification = array(
        'message' => 'Regra Criada Com Sucesso!',
        'alert-type' => 'success'
       );
       return redirect()->route('role.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.role.edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $request->id;

        Permission::findOrFail($id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
       $notification = array(
        'message' => 'Permissão Atualizada Com Sucesso!',
        'alert-type' => 'success'
       );
         return redirect()->route('role.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $notification = array(
            'message' => 'Regra Apagada Com Sucesso!',
            'alert-type' => 'success'
           );
             return redirect()->route('role.index')->with($notification);
        }

        //Add Role Permission

        public function RolePermissionIndex()
        {
            $roles = Role::all();
            return view('admin.rolepermissions.index',compact('roles'));
        }
        public function AddRolesPermission()
        {
            $roles = Role::all();
            $permissions = Permission::all();

            //User Group
            $permission_groups = User::getPermissionGroups();

            return view('admin.rolepermissions.create',compact('roles', 'permissions','permission_groups'));

        }

        //Role Permission Store
        public function RolePermissionStore(Request $request)
        {
            $data = array();
            $permissions = $request->permission;

            foreach($permissions  as $key => $item){
                $data['role_id'] = $request->role_id;
                $data['permission_id'] = $item;

                DB::table('role_has_permissions')->insert($data);

                $notification = array(
                    'message' => 'Permissão Criada com Sucesso',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
            };
        }

        //Edit Role
        public function editRoles($id)
        {
            $role  = Role::findOrFail($id);
            $permissions = Permission::all();
            $permission_groups = User::getPermissionGroups();
            return view('admin.rolepermissions.edit',compact('role','permissions','permission_groups'));
        }

        public function RolesUpdate(Request $request, $id)
        {
            $role = Role::findOrFail($id);
            $permissions = $request->permission;

            if(!empty($permissions))
            {
                $role->syncPermissions($permissions);
            }
            $notification = array(
                'messsage' => 'Permissões atualizadas com sucesso',
                'alert-type' => 'success'
            );

            return redirect()->route('role.permission.index')->with($notification);
        }

        //Delete Role
        public function DeleteRole($id)
        {
            $role = Role::findOrFail($id);
            if (!is_null($role)) {
                $role->delete();
            }
    
             $notification = array(
                'message' => 'Permissão Apagada Com Sucesso!',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification); 

        }

    }
