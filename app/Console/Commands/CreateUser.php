<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;

use Illuminate\Support\Facades\Hash;


class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //Aquí se ejecutara la creación del usuario
		 $user = $this->ask('¿Nombre de usuario?');
		 $this->verificarUser($user);
		 $email = $this->ask('¿Cuál es el email?');
		 $this->verificarEmail($email);
		 $password = $this->secret('¿Cuál es la contraseña?');
		 $password=$this->verificarContraseña($password);
		 $newUser=new user();
		 $newUser->username=$user;
		 $newUser->email=$email;
		 $newUser->password=$password;
		 $newUser->save();
    }
	protected function verificarEmail($email) { //verifica que el formato del email sea valido usando funciones incorporadas ya en php
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	}
	else {
		echo 'Formato de email no valido';
		$this->handle();
	}
	}
	protected function verificarUser($user) { //Verifica que el usuario cumpla las condiciones
	if (strlen($user)>=20) {
	echo 'Excedio el limite maximo de 20 caracteres para el usuario';
	$this->handle();	
	}
	}
	protected function verificarContraseña($pass) { //Verifica que la contraseña cumpla las condiciones de seguridad
	if (strlen($pass)<8) {
	echo 'La contraseña como minimo debe tener 8 caracteres';
	$this->handle();	
	}
	if (strlen($pass)>16) {
	echo 'El maximo de caracteres es 16';
	$this->handle();		
	}
	return Hash::make($pass); 
	}
}
