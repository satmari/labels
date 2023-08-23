<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
// use Request; // for import

use Illuminate\Support\Facades\Redirect;

use App\os_label;
use DB;

use Session;

class ControllerOS extends Controller {

	
	public function index()
	{
		//
		// dd("test");
		$os = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	    	m.[MachNum] as os
	    	--,m.[MaTyCod]
	    	,t.Brand as brand
	    	,t.MaCod as code
	    	--,t.MaTyp
	      
			FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
			INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
		  	order by m.[MachNum] asc"));
		// dd($os);

		return view('os.index', compact('os'));

	}

	public function os_print(Request $request) {
		//
		// $this->validate($request, ['document' => 'required']);
		$input = $request->all();
		// dd($input);

		$os = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	    	m.[MachNum] as os
	    	--,m.[MaTyCod]
	    	,t.Brand as brand
	    	,t.MaCod as code
	    	--,t.MaTyp
	      
			FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
			INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
		  	order by m.[MachNum] asc"));
		// dd($os);

		$printer_name1 = $input['printer_name1'];
		$os1 = trim($input['os1']);
		// dd($os1);

		if ($printer_name1 == '') {

			$msg1 = 'Stampac nije izabran';
			return view('os.index', compact('os','msg1'));
		}

		if ($os1 == '') {
			
			$msg2 = 'OS nije izabran';
			return view('os.index', compact('os','msg2','printer_name1'));
		}

		$os_details = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	    	m.[MachNum] as os
	    	--,m.[MaTyCod]
	    	,t.Brand as brand
	    	,t.MaCod as code
	    	--,t.MaTyp
	      
			FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
			INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
			WHERE m.[MachNum] = '".$os1."'
		  	order by m.[MachNum] asc"));
		
		// dd($os_details);

		if (isset($os_details[0]->os)) {
				
				$table = new os_label;

				$table->os = $os_details[0]->os;
				$table->code = $os_details[0]->brand . " / " . $os_details[0]->code;
				$table->printer = $printer_name1;
				
				$table->save();

				$msgs1 = 'Uspesno odstampano. Proverite nalepnicu';
				
				$os = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			    	m.[MachNum] as os
			    	--,m.[MaTyCod]
			    	,t.Brand as brand
			    	,t.MaCod as code
			    	--,t.MaTyp
			      
					FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
					INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
				  	order by m.[MachNum] asc"));

				return view('os.index', compact('os','msgs1','printer_name1'));


		} else {
			dd('Error');
		}

	}
	



}
