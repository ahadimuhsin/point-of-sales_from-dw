<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //menampilkan role apa saja yang ada
    public function index()
    {
        $role = Role::orderBy('created_at', 'desc')->paginate(10);

        return view('roles.index', compact('role'));
    }

    //menyimpan role ke database
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:100'
        ]);

        $role = Role::firstOrCreate(['name' => $request->name]);

        return redirect()->back()->with(['success' => 'Role '.$role->name.' berhasil dibuat']);
    }

    //menghapus role
    public function destroy($id){
        $role = Role::findOrFail($id);

        $role->delete();

        return redirect()->back()->with(['success' => 'Role '.$role->name.' berhasil dihapus']);
    }
}
