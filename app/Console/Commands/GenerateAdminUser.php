<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\{User, Role, Permission};
use App\Enum\{Table, UserRole, PermissionRole};

class GenerateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user.';

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
        $arguments = [];

        $userName = $this->ask('What is your name?');
        $email    = $this->ask('What is your email?');
        $gender   = $this->ask('Your gender');
        $password = $this->secret('What is the password?');

        $rules = [
            'name'     => 'required|min:2',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'gender'   => [
                'required',
                Rule::in('M', 'F', 'O', 'B')
            ]
        ];


        $arguments['name']     = $userName;
        $arguments['email']    = $email;
        $arguments['password'] = $password;
        $arguments['gender']   = $gender;

        $validator = \Validator::make($arguments, $rules);

        if ($validator->fails()) {
            $message = '';

            foreach (json_decode($validator->messages()) as $fieldName => $errors) {
                $message .= $fieldName . ': ';

                foreach ($errors as $error) {
                    $message .= $error . ' ';
                }

                $message .= "\n";
            }

            $this->error($message . "\n");
        } else {
            $role      = Role::firstOrCreate(['name' => UserRole::ADMINISTRATOR, 'label' => UserRole::ADMINISTRATOR]);
            $permisson = Permission::firstOrCreate(['name' => PermissionRole::ADMINISTRATOR, 'label' => PermissionRole::ADMINISTRATOR]);
            $user      = User::create([
                'name'     => $userName,
                'email'    => $email,
                'password' => bcrypt($password),
                'gender'   => $gender,
            ]);

            $userId       = $user->id;
            $roleId       = $role->id;
            $permissionId = $permisson->id;

            \DB::table(Table::ROLE_USER)->insert([
                'role_id' => $roleId,
                'user_id' => $userId
            ]);

            $checkPermissionRole = \DB::table(Table::PERMISSION_ROLE)->where([
                'permission_id' => $permissionId,
                'role_id'       => $roleId
            ])->first();

            if (is_null($checkPermissionRole)) {
                \DB::table(Table::PERMISSION_ROLE)->insert([
                    'permission_id' => $permissionId,
                    'role_id'       => $roleId
                ]);
            }

            $this->info('Created successfully.');
        }

    }
}
