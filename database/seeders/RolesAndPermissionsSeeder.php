<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear roles y permisos cacheados
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // -----------------------------------------------------------------
        // DEFINICIÓN DE PERMISOS
        // Los nombres de los permisos siguen la convención "acción-recurso"
        // -----------------------------------------------------------------
        // Permisos para Ajustes Generales
        Permission::create(['name' => 'manage-settings', 'guard_name' => 'web']);
        // Permisos para Especialidades de Laboratorio (del módulo existente)
        Permission::create(['name' => 'manage-specialties', 'guard_name' => 'web']);
        // Permisos para Aulas (Classrooms)
        Permission::create(['name' => 'manage-classrooms', 'guard_name' => 'web']);
        // Permisos para Materias (Subjects)
        Permission::create(['name' => 'manage-subjects', 'guard_name' => 'web']);
        // Permisos para Sesiones de Laboratorio (Lab Sessions)
        Permission::create(['name' => 'create-lab-session', 'guard_name' => 'web']);
        Permission::create(['name' => 'view-own-lab-sessions', 'guard_name' => 'web']);
        Permission::create(['name' => 'view-all-lab-sessions', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit-lab-session', 'guard_name' => 'web']); // Permiso opcional, por si se necesita
        Permission::create(['name' => 'delete-lab-session', 'guard_name' => 'web']);
        Permission::create(['name' => 'review-lab-session', 'guard_name' => 'web']);
        Permission::create(['name' => 'download-lab-session-pdf', 'guard_name' => 'web']);
        // Permisos para gestión de usuarios
        Permission::create(['name' => 'manage-users', 'guard_name' => 'web']);
        // -----------------------------------------------------------------
        // DEFINICIÓN DE ROLES Y ASIGNACIÓN DE PERMISOS
        // -----------------------------------------------------------------
        // ROL 1: Administrador (Tiene acceso a todo)
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());
        // ROL 2: Docente
        $teacherRole = Role::create(['name' => 'Docente']);
        $teacherRole->givePermissionTo([
            'create-lab-session',
            'view-own-lab-sessions',
            'edit-lab-session', // Un docente podría editar sus propias sesiones (ej. para corregir un error)
            'download-lab-session-pdf',
        ]);
        // ROL 3: Estudiante
        $studentRole = Role::create(['name' => 'Estudiante']);
        $studentRole->givePermissionTo([
            'create-lab-session', // Asumimos que el estudiante es quien llena el formulario al final
            'view-own-lab-sessions',
            'download-lab-session-pdf',
        ]);
        // ROL 4: Control Interno
        $internalControlRole = Role::create(['name' => 'Control Interno']);
        $internalControlRole->givePermissionTo([
            'view-all-lab-sessions',
            'review-lab-session',
            'download-lab-session-pdf',
        ]);
        // -----------------------------------------------------------------
        // CREACIÓN DE USUARIOS DE PRUEBA CON ROLES
        // Es una buena práctica tener usuarios de prueba para cada rol.
        // -----------------------------------------------------------------
        $adminUser = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@ejemplo.com',
            // La contraseña 'password' se establece en el UserFactory
        ]);
        $adminUser->assignRole($adminRole);

        $teacherUser = \App\Models\User::factory()->create([
            'name' => 'Docente User',
            'email' => 'docente@ejemplo.com',
        ]);
        $teacherUser->assignRole($teacherRole);

        $studentUser = \App\Models\User::factory()->create([
            'name' => 'Estudiante User',
            'email' => 'estudiante@ejemplo.com',
        ]);
        $studentUser->assignRole($studentRole);

        $controlUser = \App\Models\User::factory()->create([
            'name' => 'Control Interno User',
            'email' => 'control@ejemplo.com',
        ]);
        $controlUser->assignRole($internalControlRole);
    }
}
