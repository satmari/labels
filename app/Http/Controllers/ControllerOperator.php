<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
// use Request; // for import

use Illuminate\Support\Facades\Redirect;

use App\operator_label;
use DB;

use Session;

class ControllerOperator extends Controller {

	public function index()
	{
		//
		$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
		  FROM [BdkCLZG].[dbo].[WEA_PersData]
		  WHERE [FlgAct] = '1'
		  ORDER BY BadgeNum asc "));
		// dd($operators);

		return view('operator.index', compact('operators'));

	}

	public function operator_print(Request $request) {
		//
		// $this->validate($request, ['document' => 'required']);
		$input = $request->all();
		// dd($input);

		$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
		  FROM [BdkCLZG].[dbo].[WEA_PersData]
		  WHERE [FlgAct] = '1'
		  ORDER BY BadgeNum asc "));

		$printer_name1 = $input['printer_name1'];
		$operater1 = trim($input['operater1']);

		if ($printer_name1 == '') {

			$operater1_name_all = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
		  	FROM [BdkCLZG].[dbo].[WEA_PersData]
		  	WHERE [FlgAct] = '1' AND [BadgeNum] = '".$operater1."'
		  	ORDER BY BadgeNum asc "));
			$operater1_name = $operater1_name_all[0]->name;

			$msg1 = 'Stampac nije izabran';
			return view('operator.index', compact('operators','msg1','operater1','operater1_name'));
		}

		if ($operater1 == '') {
			
			$msg2 = 'Operater nije izabran';
			return view('operator.index', compact('operators','msg2','printer_name1'));
		}

		$operater_details = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
		  FROM [BdkCLZG].[dbo].[WEA_PersData]
		  WHERE [FlgAct] = '1' AND [BadgeNum] = '".$operater1."'
		  ORDER BY BadgeNum asc "));

		// dd($operater_details);

		if (isset($operater_details[0]->rnumber)) {
				
				$table = new operator_label;

				$table->rnumber = $operater_details[0]->rnumber;
				$table->name = $operater_details[0]->name;
				$table->printer = $printer_name1;
				
				$table->save();

				$msgs1 = 'Uspesno odstampano. Proverite nalepnicu';
				// return redirect('/print_oprators');
				$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
					FROM [BdkCLZG].[dbo].[WEA_PersData]
				  	WHERE [FlgAct] = '1'
				  	ORDER BY BadgeNum asc "));
				// dd($operators);

				return view('operator.index', compact('operators','msgs1','printer_name1'));


		} else {
			dd('Error');
		}



	}


	

}
