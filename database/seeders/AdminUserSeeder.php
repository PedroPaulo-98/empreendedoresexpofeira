<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Forçar o guard durante a execução
        config(['auth.defaults.guard' => 'web']);

        // Resetar cache de permissões/roles
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Usuários
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Administrador', 'password' => Hash::make('password')]
        );

        $empresaUser = User::firstOrCreate(
            ['email' => 'empresa@empresa.com'],
            ['name' => 'Empresa', 'password' => Hash::make('password')]
        );

        // Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $empresaRole = Role::firstOrCreate(['name' => 'empresa', 'guard_name' => 'web']);

        // Permissões conforme prefixes do config/filament-shield.php
        $resourcePrefixes = [
            'visualizar',
            'visualizar_todos',
            'criar',
            'atualizar',
            'deletar',
            'deletar_todos',
        ];

        // Recursos que teremos permissões específicas
        $resources = [
            'companies',
            'daily_data',
        ];

        // Criar permissões de páginas essenciais
        $pages = [
            'Dashboard',
        ];

        // Criar permissões de recursos
        foreach ($resources as $resource) {
            foreach ($resourcePrefixes as $prefix) {
                Permission::firstOrCreate([
                    'name' => $resource . '.' . $prefix,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Criar permissões de página
        foreach ($pages as $page) {
            Permission::firstOrCreate([
                'name' => 'page.' . $page,
                'guard_name' => 'web',
            ]);
        }

        // Super admin recebe todas as permissões
        $superAdminRole->syncPermissions(Permission::all());
        $admin->syncRoles([$superAdminRole]);

        // Empresa: somente visualizar/criar/atualizar em companies e daily_data e dashboard
        $empresaPermissions = [];
        foreach (['companies', 'daily_data'] as $resource) {
            foreach (['visualizar', 'visualizar_todos', 'criar', 'atualizar'] as $prefix) {
                $empresaPermissions[] = $resource . '.' . $prefix;
            }
        }
        $empresaPermissions[] = 'page.Dashboard';

        $empresaRole->syncPermissions(
            Permission::whereIn('name', $empresaPermissions)->get()
        );
        $empresaUser->syncRoles([$empresaRole]);

        // Resetar cache novamente após alterações
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info('Admin e Empresa criados. Emails: admin@admin.com / empresa@empresa.com | Senha: password');
    }
} 