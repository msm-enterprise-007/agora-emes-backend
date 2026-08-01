<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
           $roles = [
            [
                'name' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Accès complet au système',
            ],
            [
                'name' => 'Superviseur',
                'slug' => 'supervisor',
                'description' => 'Supervision des stagiaires et des formations',
            ],
            [
                'name' => 'Stagiaire',
                'slug' => 'intern',
                'description' => 'Utilisateur en stage',
            ],
            [
                'name' => 'Apprenant',
                'slug' => 'learner',
                'description' => 'Utilisateur inscrit à une formation',
            ],
        ];
    
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}