<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use Request; // for import
// use Illuminate\Http\Request; // for image

use App\os_label;
use DB;


class ControllerOS_import extends Controller {

	
	public function index()
	{
		//
	}

	public function os_print_multiple(Request $request) {
	   
	   	// dd('test');
		// dd($request);
		if (Request::input('printer_name2') != '') {
			$printer_name2 = Request::input('printer_name2');

		} else {

			$os = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	    	m.[MachNum] as os
	    	--,m.[MaTyCod]
	    	,t.Brand as brand
	    	,t.MaCod as code
	    	--,t.MaTyp
	      
			FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
			INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
		  	order by m.[MachNum] asc"));

			$msg3 = 'Stampac nije izabran';
			return view('os.index', compact('os','msg3'));
			
		}
		// dd($printer_name2);
		// dd(Request::file('file'));
		
	    if (!is_null(Request::file('file'))) {
	    	$getSheetName = Excel::load(Request::file('file'))->getSheetNames();	

	    } else {
	    	$os = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	    	m.[MachNum] as os
	    	--,m.[MaTyCod]
	    	,t.Brand as brand
	    	,t.MaCod as code
	    	--,t.MaTyp
	      
			FROM [BdkCLZG].[dbo].[CNF_MachPool] as m 
			INNER JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as t ON t.IntKey = m.MaTyCod
		  	order by m.[MachNum] asc"));

			$msg4 = 'Fajl nije izabran';
			return view('os.index', compact('so','printer_name2','msg4'));

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

					// dd($printer_name2);

	                foreach ($readerarray as $value) {
					    // dd($value['os']);
					    // print_r($value['os']);

					    $os1 = trim(strtoupper($value['os']));

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

					    // dd($printer_name2);

					    if (isset($os_details[0]->os)) {
					    	
					    	$table = new os_label;
							$table->os = $os_details[0]->os;
							$table->code = $os_details[0]->brand . " / " . $os_details[0]->code;
							$table->printer = Request::input('printer_name2');
							$table->save();

							print_r($value['os'].'OS uspesno odstampan <br>');

					    } else {
					    	print_r($value['os'].':  OS nije pronadjen u Inteosu <br>');
					    }
					}
	            });
	    }
	    
	   print_r('Zavrseno stampanje <br>');
	}


}
