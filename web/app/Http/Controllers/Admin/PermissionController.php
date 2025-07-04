<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $permission = Permission::create([
        'name' => $request->name,
        'group_name' => $request->group_name,
       ]);

       $notification = array(
        'message' => 'Permissão Criada Com Sucesso!',
        'alert-type' => 'success'
       );
       return redirect()->route('permission.index')->with($notification);
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
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit',compact('permission'));
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
         return redirect()->route('permission.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        $notification = array(
            'message' => 'Permissão Apagada Com Sucesso!',
            'alert-type' => 'success'
           );
             return redirect()->route('permission.index')->with($notification);
        }
    }