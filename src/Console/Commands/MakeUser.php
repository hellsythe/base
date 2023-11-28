<?php

namespace Sdkconsultoria\Core\Console\Commands;

use Illuminate\Console\Command;

class MakeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sdk:user {email=admin@sdkconsultoria.com} {--name=default} {--lastname=default} {--role=super-admin} {--token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario y/o obtiene el token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = $this->createUser();
        $this->asingRolesToUser($user);

        $token = $this->option('token');
        if ($token) {
            $this->info('token: '.$user->createToken('token')->plainTextToken);
        }

        return 0;
    }

    private function createUser()
    {
        $user_class = config('auth.providers.users.model');
        $email = $this->argument('email');
        $name = $this->option('name');
        $lastname = $this->option('lastname');

        $user = $user_class::where('email', $email)->first();

        if ($user) {
            $this->info("El usuario $email ya existe");

            return $user;
        }

        $user = new ($user_class);
        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = 'password';
        $user->status = $user_class::STATUS_ACTIVE;
        $user->email_verified_at = date('Y-m-d h:i:s');
        $user->save();

        $this->info("Se creo el usuario $email");

        return $user;
    }

    private function asingRolesToUser($user)
    {
        $role = $this->option('role');

        $user->assignRole([$role]);
    }
}
