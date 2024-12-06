<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{

   
    public function index()
    {
        // Tüm rolleri al
        try {
            $roles = Role::with('permissions')->get(); // Değişiklik: İzinlerle birlikte rolleri al
            return $this->sendSuccess("Rol ve İzin listesi",  $roles); // Değişiklik: İzinleri de içeren veri döndürülüyor
        } catch (\Throwable $th) {
            return $this->sendError("Rol ve İzin listesi alınamadı", 500, $th->getMessage());
        }
    }

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
                // Kopyalanacak rolün izinlerini bul
                $copyRole = Role::find($validatedData['copy_role_id']);
                // Kopyalanacak rolün izinlerini yeni role uygula
                $role->syncPermissions($copyRole->permissions()->pluck('name')->toArray());
            } elseif (isset($validatedData['permissions'])) {
                // Doğrulanmış izinleri yeni role uygula
                $role->syncPermissions($validatedData['permissions']);
            }

            return $this->sendSuccess("Rol eklendi", $role);
        } catch (ValidationException $e) {
            return $this->sendError("Doğrulama hatası", 422, $e->errors());
        }
    }



    public function edit(Request $request, $id)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'permissions' => 'sometimes|array',
                'permissions.*' => 'sometimes|exists:permissions,name', // Check that each permission exists
            ]);

            // Fetch the role by ID
            $role = Role::findOrFail($id);

            // Revoke all current permissions first if needed (optional, depending on the use case)
            $role->syncPermissions([]);

            // Grant new permissions
            if (isset($validatedData['permissions'])) {
                // Add the validated permissions
                $role->syncPermissions($validatedData['permissions']);
            }

            return $this->sendSuccess("Rol güncellendi", $role);

        } catch (ValidationException $e) {
            // Return validation error response
            return $this->sendError("Doğrulama hatası", 422, $e->errors());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->permissions()->detach();
            $role->delete();
            return $this->sendSuccess("Rol ve izinleri silindi", $role);
        } catch (Throwable $e) { // Değişiklik: Exception yerine Throwable kullanıldı
            return $this->sendError("Rol silinemedi", 500, $e->getMessage());
        }
    }


}
