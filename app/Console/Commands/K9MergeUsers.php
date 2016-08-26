<?php

namespace App\Console\Commands;

use App\Legacy\Staff\User as Staff;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class K9MergeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:mergeusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge k9users into laravel users table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Delete all staff
        DB::connection('mysql')->delete('delete from users where users.roles="staff" ');

        $staff = Staff::all();

        foreach ($staff as $emp) {

            // Create a User
            $user = new User;
            $user->name = $emp->name;
            $user->email = $emp->name . '@k9homes.com.au';
            $user->password = bcrypt($emp->name);
            $user->roles = 'staff';

            $user->save();

            // Update Staff entry with their new user.id
            $emp->user_id = $user->id;
            $emp->save();

        }
        $this->info('Tables merged / synced');
    }
}
