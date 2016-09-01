<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\CBLabels;
use DB;

class ControllerCBlabels extends Controller {

	/**
	 * Display a listing of the resource.
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

		// Live database
		// try {
			
			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv2')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [BdkCLZG].[dbo].[CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			// Inteos - box information
			$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
				bb.IntKeyPO, 
				bb.BlueBoxNum,
				st.StyCod,
				sku.Variant
				FROM            dbo.CNF_BlueBox AS bb LEFT OUTER JOIN
				                         dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY LEFT OUTER JOIN
				                         dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY LEFT OUTER JOIN
				                         dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY
				WHERE			bb.[INTKEY] = :somevariable

				GROUP BY		bb.IntKeyPO, 
								bb.BlueBoxNum,
								st.StyCod,
								sku.Variant"
				), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA ZA OVU PLAVU KUTIJU U INTEOSU !';
	        	return view('cblabels.error', compact('msg'));
	    	}

	    	if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Inteos, NE POSTOJI PLAVA KUTIJA U INTEOSU !';
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
	    	$bbcode = $inteos_array[0]['IntKeyPO'];
	    	$bb = $inteos_array[0]['BlueBoxNum'];
	    	$style = $inteos_array[0]['StyCod'];
	    	$variant = $inteos_array[0]['Variant'];

	    	list($color, $size) = explode('-', $variant);
	    	// dd($size);

	    	// Cartiglio

	    	$cartiglio = DB::connection('sqlsrv3')->select(DB::raw("SELECT [Cod_Bar]
		      ,[Cod_Art_CZ]
		      ,[Cod_Col_CZ]
		      ,[Tgl_ENG]
				FROM [finalaudit].[dbo].[cartiglio]
				WHERE [Cod_Art_CZ] = :style AND [Cod_Col_CZ] = :color AND [Tgl_ENG] = :size"), array( 'style' => $style, 'color' => $color, 'size' => $size
			));

			if ($cartiglio) {
				//continue
			} else {
	        	$msg = 'Cannot find SKU in Cartiglio DB, NE POSTOJI SKU IN CARTIGLIO DB !!!';
	        	return view('cblabels.error', compact('msg'));
	    	}

			$cartiglio_array = object_to_array($cartiglio);
	    	$barcode = $cartiglio_array[0]['Cod_Bar'];
	    	$barcode_3 = substr($cartiglio_array[0]['Cod_Bar'], -3, 3);
	    	// dd($barcode_3);
	    	$color_desc = 'test'; // FALI!!!!

	    	$printer_name = 'printer';
	    	$printed = 0;

	    	// Record Labels
			// try {
				$table = new CBLabels;

				$table->bbcode = $bbcode;
				$table->bb = $bb;

				$table->style = $style;
				$table->color = $color;
				$table->color_desc = $color_desc;
				$table->size = $size;

				$table->qty_to_print = $qty_to_print;
				$table->barcode = $barcode;
				$table->barcode_3 = $barcode_3;

				$table->printer_name = $printer_name;
				$table->printed = $printed;
				
				$table->save();
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to save cb label in table";
			// 	return view('cblabels.error',compact('msg'));
			// }



	 //    }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	//return Redirect::to('/searchinteos');
		// 	$msg = "Problem to save in cblabel table. try agan.";
		// 	return view('cblabels.error',compact('msg'));
		// }
		/*
		if ($msg1 != ''){
			return view('batch.sample', compact('msg1','batch_name'));
		}
		*/
			
		return Redirect::to('/cblabels');
		
	}
	

}
