<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Menurole;
use App\Models\RoleHierarchy;
use App\Roles;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = DB::table('roles')
        ->leftJoin('role_hierarchy', 'roles.id', '=', 'role_hierarchy.role_id')
        ->select('roles.*', 'role_hierarchy.hierarchy')
        ->orderBy('hierarchy', 'asc')
        ->get();
        return view('dashboard.roles.index', array(
            'roles' => $roles,
        ));
    }

    public function moveUp(Request $request){
        $element = RoleHierarchy::where('role_id', '=', $request->input('id'))->first();
        $switchElement = RoleHierarchy::where('hierarchy', '<', $element->hierarchy)
            ->orderBy('hierarchy', 'desc')->first();
        if(!empty($switchElement)){
            $temp = $element->hierarchy;
            $element->hierarchy = $switchElement->hierarchy;
            $switchElement->hierarchy = $temp;
            $element->save();
            $switchElement->save();
        }
        return redirect()->route('roles.index');
    }

    public function moveDown(Request $request){
        $element = RoleHierarchy::where('role_id', '=', $request->input('id'))->first();
        $switchElement = RoleHierarchy::where('hierarchy', '>', $element->hierarchy)
            ->orderBy('hierarchy', 'asc')->first();
        if(!empty($switchElement)){
            $temp = $element->hierarchy;
            $element->hierarchy = $switchElement->hierarchy;
            $switchElement->hierarchy = $temp;
            $element->save();
            $switchElement->save();
        }
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();
        $hierarchy = RoleHierarchy::select('hierarchy')
        ->orderBy('hierarchy', 'desc')->first();
        if(empty($hierarchy)){
            $hierarchy = 0;
        }else{
            $hierarchy = $hierarchy['hierarchy'];
        }
        $hierarchy = ((integer)$hierarchy) + 1;
        $roleHierarchy = new RoleHierarchy();
        $roleHierarchy->role_id = $role->id;
        $roleHierarchy->hierarchy = $hierarchy;
        $roleHierarchy->save();
        $request->session()->flash('message', 'Successfully created role');
        return redirect()->route('roles.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('dashboard.roles.show', array(
            'role' => Role::where('id', '=', $id)->first()
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lims_role_data = Roles::find($id);
        $permissions = Role::findByName($lims_role_data->name)->permissions;
        foreach ($permissions as $permission)
            $all_permission[] = $permission->name;
        if(empty($all_permission))
            $all_permission[] = 'dummy text';

        return view('dashboard.roles.edit', array(
            'role' => Role::where('id', '=', $id)->first(),
            'lims_role_data' =>  $lims_role_data = Role::find($id),
            'all_permission' => $all_permission,
            'lims_role_data' =>  Role::where('id', '=', $id)->first()
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $role = Role::firstOrCreate(['id' => $id]);
          if($request->has('supplier-index')){
            $permission = Permission::firstOrCreate(['name' => 'supplier-index']);
            if(!$role->hasPermissionTo('supplier-index')){
                $role->givePermissionTo($permission);
            }
        }	
        else
            $role->revokePermissionTo('supplier-index');

        if($request->has('supplier-add')){
            $permission = Permission::firstOrCreate(['name' => 'supplier-add']);
            if(!$role->hasPermissionTo('supplier-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('supplier-add');

        if($request->has('supplier-edit')){
            $permission = Permission::firstOrCreate(['name' => 'supplier-edit']);
            if(!$role->hasPermissionTo('supplier-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('supplier-edit');

        if($request->has('supplier-delete')){
            $permission = Permission::firstOrCreate(['name' => 'supplier-delete']);
            if(!$role->hasPermissionTo('supplier-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('supplier-delete');


        if($request->has('prebooking-index')){
            $permission = Permission::firstOrCreate(['name' => 'prebooking-index']);
            if(!$role->hasPermissionTo('prebooking-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('prebooking-index');

        if($request->has('prebooking-add')){
            $permission = Permission::firstOrCreate(['name' => 'prebooking-add']);
            if(!$role->hasPermissionTo('prebooking-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('prebooking-add');

        if($request->has('prebooking-edit')){
            $permission = Permission::firstOrCreate(['name' => 'prebooking-edit']);
            if(!$role->hasPermissionTo('prebooking-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('prebooking-edit');

        if($request->has('prebooking-delete')){
            $permission = Permission::firstOrCreate(['name' => 'prebooking-delete']);
            if(!$role->hasPermissionTo('prebooking-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('prebooking-delete');


        if($request->has('shipment-index')){
            $permission = Permission::firstOrCreate(['name' => 'shipment-index']);
            if(!$role->hasPermissionTo('shipment-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('shipment-index');

        if($request->has('shipment-add')){
            $permission = Permission::firstOrCreate(['name' => 'shipment-add']);
            if(!$role->hasPermissionTo('shipment-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('shipment-add');

        if($request->has('shipment-edit')){
            $permission = Permission::firstOrCreate(['name' => 'shipment-edit']);
            if(!$role->hasPermissionTo('shipment-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('shipment-edit');

        if($request->has('shipment-delete')){
            $permission = Permission::firstOrCreate(['name' => 'shipment-delete']);
            if(!$role->hasPermissionTo('shipment-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('shipment-delete');

        if($request->has('feetype-index')){
            $permission = Permission::firstOrCreate(['name' => 'feetype-index']);
            if(!$role->hasPermissionTo('feetype-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('feetype-index');

        if($request->has('feetype-add')){
            $permission = Permission::firstOrCreate(['name' => 'feetype-add']);
            if(!$role->hasPermissionTo('feetype-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('feetype-add');

        if($request->has('feetype-edit')){
            $permission = Permission::firstOrCreate(['name' => 'feetype-edit']);
            if(!$role->hasPermissionTo('feetype-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('feetype-edit');

        if($request->has('feetype-delete')){
            $permission = Permission::firstOrCreate(['name' => 'feetype-delete']);
            if(!$role->hasPermissionTo('feetype-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('feetype-delete');


        if($request->has('workplan-index')){
            $permission = Permission::firstOrCreate(['name' => 'workplan-index']);
            if(!$role->hasPermissionTo('workplan-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('workplan-index');

        if($request->has('workplan-add')){
            $permission = Permission::firstOrCreate(['name' => 'workplan-add']);
            if(!$role->hasPermissionTo('workplan-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('workplan-add');

        if($request->has('workplan-edit')){
            $permission = Permission::firstOrCreate(['name' => 'workplan-edit']);
            if(!$role->hasPermissionTo('workplan-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('workplan-edit');

        if($request->has('workplan-delete')){
            $permission = Permission::firstOrCreate(['name' => 'workplan-delete']);
            if(!$role->hasPermissionTo('workplan-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('workplan-delete');

        if($request->has('attendance-index')){

            $permission = Permission::firstOrCreate(['name' => 'attendance-index']);

            if(!$role->hasPermissionTo('attendance-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('attendance-index');

        if($request->has('attendance-add')){
            $permission = Permission::firstOrCreate(['name' => 'attendance-add']);
            if(!$role->hasPermissionTo('attendance-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('attendance-add');

        if($request->has('attendance-edit')){
            $permission = Permission::firstOrCreate(['name' => 'attendance-edit']);
            if(!$role->hasPermissionTo('attendance-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('attendance-edit');

        if($request->has('attendance-delete')){
            $permission = Permission::firstOrCreate(['name' => 'attendance-delete']);
            if(!$role->hasPermissionTo('attendance-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('attendance-delete');

        if($request->has('customerfee-index')){
            $permission = Permission::firstOrCreate(['name' => 'customerfee-index']);
            if(!$role->hasPermissionTo('customerfee-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customerfee-index');

        if($request->has('customerfee-add')){
            $permission = Permission::firstOrCreate(['name' => 'customerfee-add']);
            if(!$role->hasPermissionTo('customerfee-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customerfee-add');

        if($request->has('customerfee-edit')){
            $permission = Permission::firstOrCreate(['name' => 'customerfee-edit']);
            if(!$role->hasPermissionTo('customerfee-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customerfee-edit');

        if($request->has('customerfee-delete')){
            $permission = Permission::firstOrCreate(['name' => 'customerfee-delete']);
            if(!$role->hasPermissionTo('customerfee-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customerfee-delete');

        if($request->has('recordregister-index')){
            $permission = Permission::firstOrCreate(['name' => 'recordregister-index']);
            if(!$role->hasPermissionTo('recordregister-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('recordregister-index');

        if($request->has('recordregister-add')){
            $permission = Permission::firstOrCreate(['name' => 'recordregister-add']);
            if(!$role->hasPermissionTo('recordregister-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('recordregister-add');

        if($request->has('recordregister-edit')){
            $permission = Permission::firstOrCreate(['name' => 'recordregister-edit']);
            if(!$role->hasPermissionTo('recordregister-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('recordregister-edit');

        if($request->has('recordregister-delete')){
            $permission = Permission::firstOrCreate(['name' => 'recordregister-delete']);
            if(!$role->hasPermissionTo('recordregister-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('recordregister-delete');


        $role = Role::where('id', '=', $id)->first();
        $role->name = $request->input('name');
        $role->save();

        // $menuRole = new Menurole();
        // $menuRole->role_name = $role;
        // $menuRole->menus_id = $menus->id;
        // $menuRole->save();
        $request->session()->flash('message', 'Successfully updated role');
        return redirect()->route('roles.edit', $id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $role = Role::where('id', '=', $id)->first();
        $roleHierarchy = RoleHierarchy::where('role_id', '=', $id)->first();
        $menuRole = Menurole::where('role_name', '=', $role->name)->first();
        if(!empty($menuRole)){
            $request->session()->flash('message', "Can't delete. Role has assigned one or more menu elements.");
            $request->session()->flash('back', 'roles.index');
            return view('dashboard.shared.universal-info');
        }else{
            $role->delete();
            $roleHierarchy->delete();
            $request->session()->flash('message', "Successfully deleted role");
            $request->session()->flash('back', 'roles.index');
            return view('dashboard.shared.universal-info');
        }
    }
}
