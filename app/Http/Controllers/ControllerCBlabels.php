<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\CBLabels;
use DB;

use Session;

class ControllerCBlabels extends Controller {

	/**
	 * Print labels by scanning BB
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('cblabels.index');
	}

	public function searchininteos() 
	{
		//
		try {
			return view('cblabels.searchinteos');
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('cblabels.searchinteos');
		}
	}

	public function searchinteos_store(Request $request) 
	{
		//
		//
		$this->validate($request, ['bb_code' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )

		//1717281
		$bbcode = $input['bb_code'];
		// dd($bbcode);
		
		$msg = '';
		$msg1 = '';
		//$msg2 = '';

		$printer_name = Session::get('printer_name');
    	if ($printer_name != NULL) {
			//continue
		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('cblabels.error', compact('msg'));
		}
			
		// Live database
		try {
			
			// Inteos - box information
			$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
				bb.INTKEY, 
				bb.BlueBoxNum,
				st.StyCod,
				sku.Variant
				FROM            dbo.CNF_BlueBox AS bb 
				LEFT OUTER JOIN     dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY

				WHERE			bb.[INTKEY] = :somevariable

				GROUP BY		bb.INTKEY, 
								bb.BlueBoxNum,
								st.StyCod,
								sku.Variant"
				), array(
					'somevariable' => $bbcode
			));
			// dd($inteos);

			if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Inteos, NE POSTOJI PLAVA KUTIJA U INTEOSU !';
	        	return view('cblabels.error', compact('msg'));
	    	}

			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv2')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [BdkCLZG].[dbo].[CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count != 0) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U INTEOSU !';
	        	return view('cblabels.error', compact('msg'));
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
		
	    	$inteos_array_count = object_to_array($inteos_count);
	    	$qty_to_print = $inteos_array_count[0]['count'];

	    	$inteos_array = object_to_array($inteos);
	    	$bbcode = $inteos_array[0]['INTKEY'];
	    	$bb = $inteos_array[0]['BlueBoxNum'];
	    	$style = $inteos_array[0]['StyCod'];
	    	$variant = $inteos_array[0]['Variant'];

	    	list($color, $size) = explode('-', $variant);
	    	// dd($size);

	    	$size_to_search = str_replace("/","-",$size);
	    	// Cartiglio

	    	$cartiglio = DB::connection('sqlsrv3')->select(DB::raw("SELECT [Cod_Bar]
		      ,[Cod_Art_CZ]
		      ,[Cod_Col_CZ]
		      ,[Tgl_ITA]
		      ,[Tgl_ENG]
		      ,[Tgl_SPA]
		      ,[Tgl_EUR]
		      ,[Tgl_USA]
		      ,[Descr_Col_CZ]
				FROM [finalaudit].[dbo].[cartiglio]
				WHERE [Cod_Art_CZ] = :style AND [Cod_Col_CZ] = :color AND [Tgl_ITA] = :size"), array( 'style' => $style, 'color' => $color, 'size' => $size_to_search
			));

			if ($cartiglio) {
				//continue
			} else {
	        	$msg = 'Cannot find SKU in Cartiglio DB, NE POSTOJI SKU U CARTIGLIO DB !!! ZOVI ODMAH IT SEKTOR!';
	        	return view('cblabels.error', compact('msg'));
	    	}

			$cartiglio_array = object_to_array($cartiglio);
	    	$barcode = $cartiglio_array[0]['Cod_Bar'];
	    	$barcode_3 = substr($cartiglio_array[0]['Cod_Bar'], -3, 3);
	    	// dd($barcode_3);
	    	$color_desc = $cartiglio_array[0]['Descr_Col_CZ'];

	    	$size_ita = $cartiglio_array[0]['Tgl_ITA'];
	    	$size_eng = $cartiglio_array[0]['Tgl_ENG'];
	    	$size_spa = $cartiglio_array[0]['Tgl_SPA'];
	    	$size_eur = $cartiglio_array[0]['Tgl_EUR'];
	    	$size_usa = $cartiglio_array[0]['Tgl_USA'];

	    	// $printer_name = 'SBT-WP01';
	    	$printed = 0;

	    	// Record Labels
			try {
				$table = new CBLabels;

				$table->bbcode = $bbcode;
				$table->bb = $bb;

				$table->style = $style;
				$table->color = $color;
				$table->color_desc = $color_desc;
				
				$table->size_ita = $size_ita;
				$table->size_eng = $size_eng;
				$table->size_spa = $size_spa;
				$table->size_eur = $size_eur;
				$table->size_usa = $size_usa;

				$table->qty_to_print = $qty_to_print;
				$table->barcode = $barcode;
				$table->barcode_3 = $barcode_3;

				$table->printer_name = $printer_name;
				$table->printed = $printed;
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save cb label in table";
				return view('cblabels.error',compact('msg'));
			}
	    }
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Cannot find BB in Inteos, NE POSTOJI PLAVA KUTIJA U INTEOSU !";
			return view('cblabels.error',compact('msg'));
		}
		/*
		if ($msg1 != ''){
			return view('batch.sample', compact('msg1','batch_name'));
		}
		*/
			
		return Redirect::to('/');
	}
	

}
