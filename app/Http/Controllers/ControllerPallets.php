<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\CBLabels;
use App\CBExtralabels;
use App\Pallets;
use DB;

use Session;

class ControllerPallets extends Controller {

	public function index()
	{
		//
		/*
		$pallets = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
	      [Pallet Number]
	      ,[Barcode]
		  FROM [Gordon_LIVE].[dbo].[GORDON\$Pallets]"));
		*/

		$pallets = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
		      t.palnum
		FROM 
		(
		    SELECT [Pallet Number] AS palnum
		    FROM [Gordon_LIVE].[dbo].[GORDON\$Pallets]
		) AS t
		WHERE t.palnum % 500 = 0 
		ORDER BY t.palnum asc"));

		// dd($pallets);

		$printer_name = Session::get('printer_name');
		if ($printer_name != NULL) {
			//continue
			if (($printer_name == 'Krojacnica') OR ($printer_name == 'Magacin')){
				//continue
			} else {
				$msg = 'Printer must be Krojacnica or Magacin, PRINTER MORA BITI KROJACNICA ILI MAGACIN';
        		return view('pallets.error', compact('msg'));
			}

		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('pallets.error', compact('msg'));
		}



		return view('pallets.index',compact('pallets','printer_name'));
	}

	public function printpallests_post(Request $request){

		$this->validate($request, ['od' => 'required','do' => 'required']);
		$input = $request->all();

		$od = $input['od'];
		$do = $input['do'];
		// dd($od);

		$printer_name = Session::get('printer_name');
		if ($printer_name != NULL) {
			//continue
			if (($printer_name == 'Krojacnica') OR ($printer_name == 'Magacin')){
				//continue
			} else {
				$msg = 'Printer must be Krojacnica or Magacin, PRINTER MORA BITI KROJACNICA ILI MAGACIN';
        		return view('pallets.error', compact('msg'));
			}

		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('pallets.error', compact('msg'));
		}

	    $pallets = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
	      [Pallet Number]
	      ,[Barcode]
		  FROM [Gordon_LIVE].[dbo].[GORDON\$Pallets]
		  WHERE [Pallet Number] BETWEEN '".$od."' AND '".$do."' "));

		// dd(count($pallets));

		if (count($pallets) == 1 ) {
			// dd($pallets);
			$msg = 'Wrong number range seleceted, POGRESNO SELEKTOVAN NIZ ZA STAMAPANJE!';
    		return view('pallets.error', compact('msg'));
		}

		if (count($pallets) == NULL ) {
			// dd($pallets);
			$msg = 'Wrong number range seleceted, POGRESNO SELEKTOVAN NIZ ZA STAMAPANJE!';
    		return view('pallets.error', compact('msg'));
		}

		
		function object_to_array($data)
			{
			    if (is_array($data) || is_object($data))
			    {
			        $result = array();
			        foreach ($data as $key => $value)
			        {
			            $result[$key] = object_to_array($value);
			        }
			        return $result;
			    }
			    return $data;
			}

		$inbound_array = object_to_array($pallets);
		// dd(count($inbound));

		for ($i=1; $i < count($pallets); $i++) { 
			
			$palet = $inbound_array[$i]['Pallet Number'];
			$barcode = $inbound_array[$i]['Barcode'];
			
			// Record Labels
			try {
				$table = new Pallets;

				$table->palet = $palet;
				$table->barcode = $barcode;
				$table->printer_name = $printer_name;
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save palet in table";
				return view('pallets.error',compact('msg'));
			}
		}


		return Redirect::to('/');


	}

}
