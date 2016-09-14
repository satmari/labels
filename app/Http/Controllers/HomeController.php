<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Session;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{	
		$printer_name = Session::get('printer_name');
		
		// dd($printer_name);

		if ($printer_name != NULL) {
			return view('home', compact('printer_name'));	
		} else {
			return view('home');
		}

	}
	public function printer()
	{	
		return view('printer');
	}

	public function printer_set(Request $request)
	{	
		$this->validate($request, ['printer_name'=>'required']);

		$p = $request->all(); 
		$printer_name = $p['printer_name'];

		Session::set('printer_name', $printer_name );

		return redirect('/');
	}

}
