<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Throwable;

class RoleController extends Controller
{
    // Tüm rollerin ve izinlerin listelenmesi
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get(); // Rolleri ve izinleri alıyoruz
            return view('roles.index', compact('roles')); // 'roles' değişkeni 'index' sayfasına gönderiliyor
        } catch (Throwable $th) {
            return back()->with('error', 'Rol ve İzin listesi alınamadı: ' . $th->getMessage());
        }
    }

    // Rol oluşturma formunu gösterme
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Yeni bir rol kaydetme
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'copy_role_id' => 'sometimes|required_without:permissions|exists:roles,id',
                'permissions' => 'required_without:copy_role_id|array',
                'permissions.*' => 'required_without:copy_role_id|exists:permissions,name',
            ]);

            $role = Role::create(['name' => $validatedData['name']]);

            if (isset($validatedData['copy_role_id'])) {
                $copyRole = Role::find($validatedData['copy_role_id']);
                $role->syncPermissions($copyRole->permissions()->pluck('name')->toArray());
            } elseif (isset($validatedData['permissions'])) {
                $role->syncPermissions($validatedData['permissions']);
            }

            return redirect()->route('roles.index')->with('success', 'Rol başarıyla eklendi');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    // Rol düzenleme formunu gösterme
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('role.role_edit', compact('role', 'permissions')); // Değişiklik: Görünüm adını doğru yazdım
    }

    // Rol güncelleme
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'permissions' => 'sometimes|array',
                'permissions.*' => 'sometimes|exists:permissions,name',
            ]);

            $role = Role::findOrFail($id);
            $role->syncPermissions($validatedData['permissions'] ?? []);

            return redirect()->route('roles.index')->with('success', 'Rol başarıyla güncellendi');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    // Rol silme
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->permissions()->detach();
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Rol başarıyla silindi');
        } catch (Throwable $e) {
            return back()->with('error', 'Rol silinemedi: ' . $e->getMessage());
        }
    }
}
