<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Staff Roles

        $role = new \App\Role;
        $role->name = 'staff';
        $role->label = 'K9 Staff';
        $role->save();

        $permission = new \App\Permission;
        $permission->name = 'access.backend';
        $permission->label = 'Access the Site Backend Manager';
        $permission->save();

        $role->permissions()->save($permission);

        $staff = \App\Legacy\Staff\User::all();

        \DB::connection('k9homes')->update('Update users set user_id= NULL');

        foreach ($staff as $person) {
            if (substr($person->name, 0, 1) == '_' || $person->id > 30) {
                continue;
            }

            $user = new \App\User;
            $user->name = $person->name;
            $user->email = $person->name . '@' . 'k9homes.com.au';
            $user->password = bcrypt($person->name);
            $user->save();
            $user->roles()->save($role);

            $person->user_id = $user->id;
            $person->save();

        }

        // $this->call(UsersTableSeeder::class);
    }
}
