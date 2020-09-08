<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\Facades\DB;
/*
 Di Controller ini, akan diterapkan manajemen user dengan role
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * Menampilkan list user
     */
    public function index()
    {
        //
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * Menampilkan halaman penambahan role untuk user
     */
    public function create()
    {
        //
        $role = Role::orderBy('name', 'ASC')->get();
        var_dump($role);
        return view('users.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * Menyimpan user yang dibuat beserta rolenya
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::firstOrCreate([
            'email' => $request->email],
            [ 'name' => $request->name,
                'password' => bcrypt($request->password),
                'status' => true
        ]);

        $user->assignRole($request->role);

        return redirect(route('users.index'))
            ->with(['success' => 'User ' .$user->name. ' berhasil dibuat']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
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
        //
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|exists:users,email',
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);
        //mengecek apakah field password terisi, kalo terisi bcrypt valuenya
        //jika tidak, gunakan password yang lama
        $password = !empty($request->password) ? bcrypt($request->password): $user->password;

        $user->update([
            'name' => $request->name,
            'password' => $password
        ]);

        return redirect(route('users.index'))
            ->with(['success' => 'User ' .$user->name. ' berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with([
            'success' => 'User '.$user->name.' berhasil diperbaharui'
        ]);
    }

    /*
     * FUngsi untuk mengatur permission sesuai role
     */

    public function rolePermission(Request $request)
    {
        $role = $request->role;

        $permissions = null;
        $hasPermission = null;

        //Mengambil data role
        $roles = Role::all()->pluck('name');

        if(!empty($role)){
            //select role berdasarkan namanya
            $getRole = Role::findByName($role);

            //query untuk mengambil permission yang telah dimiliki role terkait
            $hasPermission = DB::table('role_has_permissions')
                ->select('permissions.name')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->where('role_id', $getRole->id)
                ->get()
                ->pluck('name')
                ->all();

            //Mengambil data permission
            $permissions = Permission::all()->pluck('name');
        }
        return view('users.role_permission', compact('roles', 'permissions', 'hasPermission'));
    }

    public function addPermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions'
        ]);

        $permission = Permission::firstOrCreate([
            'name' => $request->name
        ]);

        return redirect()->back();
    }

    public function setRolePermission(Request $request, $role)
    {
        $roles = Role::findByName($role);

        //syncPermission akan menghapus semua permission yang dimiliki role tersebut
        //kemudian diassign kembali sehingga tidak terjadi duplicate data
        $roles->syncPermissions($request->permission);
        return redirect()->back()
            ->with(['success' => 'Permission to Role Saved']);
    }

    public function roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->pluck('name');

        return view('users.roles', compact('user', 'roles'));
    }

    public function setRole(Request $request, $id)
    {
        $this->validate($request,
        ['role' => 'required']);

        $user = User::findOrFail($id);

        //syncRoles agar terlebih dahulu menghapus semua role yang dimiliki
        //kemudia diset kembali agar tidak terjadi duplicate data
        $user->syncRoles($request->role);
        return redirect()->route('users.index')
            ->with(['success' => 'Role untuk user ' .$user->name.' sudah diset']);
    }
}
