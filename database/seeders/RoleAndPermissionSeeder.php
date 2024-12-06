<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // İzinleri oluştur
        /*Permission::create(['name' => 'Ürün Ekle']);
        Permission::create(['name' => 'Ürün Sil']);
        Permission::create(['name' => 'Ürün Güncelle']);
        Permission::create(['name' => 'Ürün Listele']);

        Permission::create(['name' => 'Kategori Ekle']);
        Permission::create(['name' => 'Kategori Sil']);
        Permission::create(['name' => 'Kategori Güncelle']);
        Permission::create(['name' => 'Kategori Listele']);

        Permission::create(['name' => 'Kullanıcı Ekle']);
        Permission::create(['name' => 'Kullanıcı Sil']);
        Permission::create(['name' => 'Kullanıcı Güncelle']);
        Permission::create(['name' => 'Kullanıcı Listele']);

        Permission::create(['name' => 'Depo Ekle']);
        Permission::create(['name' => 'Depo Sil']);
        Permission::create(['name' => 'Depo Güncelle']);
        Permission::create(['name' => 'Depo Listele']);

        Permission::create(['name' => 'Transfer Ekle']);
        Permission::create(['name' => 'Transfer Sil']);
        Permission::create(['name' => 'Transfer Güncelle']);
        Permission::create(['name' => 'Transfer Listele']);

        Permission::create(['name' => 'Stok Ekle']);
        Permission::create(['name' => 'Stok Sil']);
        Permission::create(['name' => 'Stok Güncelle']);
        Permission::create(['name' => 'Stok Listele']);

        Permission::create(['name' => 'Kullanıcı Rol Ekle']);
        Permission::create(['name' => 'Kullanıcı Rol Sil']);
        Permission::create(['name' => 'Kullanıcı Rol Güncelle']);
        Permission::create(['name' => 'Kullanıcı Rol Listele']);

        Permission::create(['name' => 'Kullanıcı İzin Ekle']);
        Permission::create(['name' => 'Kullanıcı İzin Sil']);
        Permission::create(['name' => 'Kullanıcı İzin Güncelle']);
        Permission::create(['name' => 'Kullanıcı İzin Listele']);*/


        // Rolleri oluştur
        //$role = Role::create(['name' => 'Yönetici']);
        //$role->givePermissionTo('edit articles', 'create articles', 'delete articles', 'read articles');

        //$role = Role::create(['name' => 'Müdür']);
       // $role->givePermissionTo(['edit articles', 'delete articles','read articles', 'create articles']);

        //$role = Role::create(['name' => 'Depo Sorumlusu']);
        //$role->givePermissionTo('read articles');

        //$role = Role::create(['name' => 'superadmin']);
        //$role->givePermissionTo('*');

        $user = User::find(7);
        $user->assignRole('Yönetici');
     


        
    }
}
