<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use Request; // for import
// use Illuminate\Http\Request; // for image

use App\operator_label;
use DB;


class ControllerOperator_import extends Controller {

	
	public function index()
	{
		//
	}

	public function operator_print_multiple(Request $request) {
	   
	   	// dd('test');

	    // $input = $request->all();
	    // dd($input);

		// $printer_name2 = $input['printer_name2'];
		// dd(Request::input('printer_name2'));
				
		if (Request::input('printer_name2') != '') {
			$printer_name2 = Request::input('printer_name2');

		} else {

			$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
			  FROM [BdkCLZG].[dbo].[WEA_PersData]
			  WHERE [FlgAct] = '1'
			  ORDER BY BadgeNum asc "));

			$msg3 = 'Stampac nije izabran';
			return view('operator.index', compact('operators','msg3'));
			
		}
		// dd($printer_name2);
		// dd(Request::file('file'));
		
	    if (!is_null(Request::file('file'))) {
	    	$getSheetName = Excel::load(Request::file('file'))->getSheetNames();	

	    } else {
	    	$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
			  FROM [BdkCLZG].[dbo].[WEA_PersData]
			  WHERE [FlgAct] = '1'
			  ORDER BY BadgeNum asc "));

			$msg4 = 'Fajl nije izabran';
			return view('operator.index', compact('operators','printer_name2','msg4'));

	    }
	    
		// dd('stop');
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            // DB::table('')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file'))->chunk(5000, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                // dd($readerarray);

	                foreach ($readerarray as $value) {
					    // dd($value['operater']);
					    // print_r($value['operater']);

	                	if ((substr($value['op'],0,2) == 'S-') OR (substr($value['op'],0,2) == 'K-') OR (substr($value['op'],0,2) == 'Z-')) {

	                			$table = new operator_label;
								$table->rnumber = $value['op'];
								$table->name = $value['op'];
								$table->printer = Request::input('printer_name2');
								$table->save();	
	                	
	                	} else {

	                		$operater_details = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
							  FROM [BdkCLZG].[dbo].[WEA_PersData]
							  WHERE [FlgAct] = '1' AND [BadgeNum] = '".$value['op']."'
							  ORDER BY BadgeNum asc "));	

	                		if (isset($operater_details[0]->rnumber)) {
								
								$table = new operator_label;
								$table->rnumber = $operater_details[0]->rnumber;
								$table->name = $operater_details[0]->name;
								$table->printer = Request::input('printer_name2');
								$table->save();

									// var_dump($operater_details[0]->name);
									// print_r('Snimi');

							} else {
								// dd('Error');
								print_r('Operater nije pronadjen ili nije vise aktivan: '. $value['op'] . ' <br>');
							}

	                	}

					}
	            });
	    }
	    
	    $msgs2 = 'Uspesno odstampano. Proverite nalepnice.';
		// return redirect('/print_oprators');
		$operators = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BadgeNum] as rnumber,[Name] as name
			FROM [BdkCLZG].[dbo].[WEA_PersData]
		  	WHERE [FlgAct] = '1'
		  	ORDER BY BadgeNum asc "));
		// dd($operators);

		$printer_name2 = Request::input('printer_name2');
		return view('operator.index', compact('operators','msgs2','printer_name2'));
		// return redirect('/');
	}


}
