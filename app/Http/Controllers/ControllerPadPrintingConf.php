<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\PadPrintLabels;
use App\PadPrintConf;
use DB;

use Session;

class ControllerPadPrintingConf extends Controller {

	public function index()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM pad_print_confs ORDER BY id asc"));
		return view('padprint.index_conf', compact('data'));
	}

	public function add_new_padprint_conf()
	{

		return view('padprint.add_new_padprint_conf');
	}

	public function padprint_conf_insert(Request $request)
	{
		//validation
		$this->validate($request, ['style'=>'required', 'color'=>'required' , 'cliche'=>'required' , 'cliche_color'=>'required', 'size_relevant'=>'required']);

		$input = $request->all(); 
		//var_dump($input);
	
		$style = strtoupper($input['style']);
		$color = strtoupper($input['color']);
		$cliche = strtoupper($input['cliche']);
		$cliche_color = strtoupper($input['cliche_color']);
		$size_relevant = $input['size_relevant'];

		// Record 
		try {
			$table = new PadPrintConf;

			$table->style = $style;
			$table->color = $color;
			$table->cliche = $cliche;
			$table->cliche_color = $cliche_color;
			$table->size_relevant = $size_relevant;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in table";
			return view('padprint.error',compact('msg'));
		}

		return Redirect::to('/padprint_conf');
	}

	public function padprint_conf_edit($id)
	{
		// dd($id);
		$data = PadPrintConf::findOrFail($id);		
		// dd($data);
		return view('padprint.edit', compact('data'));

	}

	public function padprint_conf_update($id, Request $request) {
		//
		$this->validate($request, ['style'=>'required', 'color'=>'required' , 'cliche'=>'required' , 'cliche_color'=>'required', 'size_relevant'=>'required']);

		$table = PadPrintConf::findOrFail($id);		
		
		$input = $request->all(); 
		// dd($input);

		try {		
			
			$table->style = strtoupper($input['style']);
			$table->color = strtoupper($input['color']);
			$table->cliche = strtoupper($input['cliche']);
			$table->cliche_color = strtoupper($input['cliche_color']);
			$table->size_relevant = $input['size_relevant'];
						
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('padprint.error');			
		}
		
		return Redirect::to('/padprint_conf');
	}

}
