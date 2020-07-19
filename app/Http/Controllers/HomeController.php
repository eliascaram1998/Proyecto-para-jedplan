<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\user;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home')->with('user',auth::user());
    }
	public function cargar(request $request) { //Se cargan los datos que se almacenan en el usuario
		$user=user::findOrFail(auth::id());
		$user->height=$request['height'];
		$user->weight=$request['weight'];
		$user->sex=$request['sex'];
		$user->birthday=$request['birthday'];
		$validate=$this->verificar($user->height,$user->weight,$user->sex); //Se validan en el servidor que esten correcto
		if ($validate==0) {
		return response()->json('Error');	
		}
		else {
		$user->save();
		return response()->json('Correcto');
		}
	}
	public function cargarUbicacion(request $request) { //Se carga la ubicacion que se obtuvo a traves de google maps
		$user=user::findOrFail(auth::id());
		$user->lat=$request['lat'];
		$user->lng=$request['lng'];
		$user->adress=$request['addr'];
		$user->save();
	}
	protected function verificar($height,$weight,$sex) {
		if ($height>250 or $weight>350 or $sex!='Masculino' and $sex!='Femenino') {
		return 0;
		}
		else {
		return 1;
		}
	}
}
