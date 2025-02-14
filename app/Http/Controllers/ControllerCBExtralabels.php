<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\CBLabels;
use App\CBExtralabels;
use DB;

use Session;

class ControllerCBExtralabels extends Controller {

	/**
	 * Print labels by scanning BB
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('cbextralabels.index');
	}

	public function searchininteos() 
	{
		//
		$inteosdb = Session::get('inteosdb');
        // dd($inteosdb);

        if (is_null($inteosdb)) {
        	$inteosdb = '1';	
        }

		return view('cbextralabels.searchinteos',compact('inteosdb'));
	}

	public function searchinteos_store_extra(Request $request) 
	{
		//
		$this->validate($request, ['bb_code' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		//1717281

		$bbcode = $input['bb_code'];
		$inteosdb = $input['inteosdb_new'];
		Session::set('inteosdb', $inteosdb );
		// dd($bbcode);
		
		$msg = '';
		$msg1 = '';
		//$msg2 = '';

		$printer_name = Session::get('printer_name');
    	if ($printer_name != NULL) {
			//continue
		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('cbextralabels.error', compact('msg'));
		}


		if ($inteosdb == '1') {

			// Live database
			$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
				bb.INTKEY, 
				bb.BlueBoxNum,
				bb.Bagno,
				bb.IDMarker,
				bb.BoxQuant,
				st.StyCod,
				sku.Variant,
				sku.ClrDesc
				FROM            CNF_BlueBox AS bb 
				LEFT OUTER JOIN     dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY

				WHERE			bb.[INTKEY] = :somevariable

				GROUP BY		bb.INTKEY, 
								bb.BlueBoxNum,
								bb.Bagno,
								bb.IDMarker,
								bb.BoxQuant,
								st.StyCod,
								sku.Variant,
								sku.ClrDesc"
				), array(
					'somevariable' => $bbcode
			));
			// dd($inteos);

			if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Gordon Inteos, NE POSTOJI PLAVA KUTIJA U GORDON INTEOSU !';
	        	return view('cbextralabels.error', compact('msg'));
	    	}

			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv2')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count != 0) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Gordon Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U GORDON INTEOSU !';
	        	return view('cbextralabels.error', compact('msg'));
	    	}

	    } elseif ($inteosdb == '2') {

	    	// Live database
			$inteos = DB::connection('sqlsrv5')->select(DB::raw("SELECT 
				bb.INTKEY, 
				bb.BlueBoxNum,
				bb.Bagno,
				bb.IDMarker,
				bb.BoxQuant,
				st.StyCod,
				sku.Variant,
				sku.ClrDesc
				FROM            CNF_BlueBox AS bb 
				LEFT OUTER JOIN     dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY 
				LEFT OUTER JOIN     dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY

				WHERE			bb.[INTKEY] = :somevariable

				GROUP BY		bb.INTKEY, 
								bb.BlueBoxNum,
								bb.Bagno,
								bb.IDMarker,
								bb.BoxQuant,
								st.StyCod,
								sku.Variant,
								sku.ClrDesc"
				), array(
					'somevariable' => $bbcode
			));
			// dd($inteos);

			if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Kikinda Inteos, NE POSTOJI PLAVA KUTIJA U KIKINDA INTEOSU !';
	        	return view('cbextralabels.error', compact('msg'));
	    	}

			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv5')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count != 0) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Kikinda Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U KIKINDA INTEOSU !';
	        	return view('cbextralabels.error', compact('msg'));
	        }

	    } else {

	    	$msg = 'Cannot find CB in any Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U INTEOSU !';
        	return view('cbextralabels.error', compact('msg'));

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

		// dd($inteos);
	
    	$inteos_array_count = object_to_array($inteos_count);
    	$qty_to_print = $inteos_array_count[0]['count'];

    	$inteos_array = object_to_array($inteos);
    	$bbcode = $inteos_array[0]['INTKEY'];
    	$bb = $inteos_array[0]['BlueBoxNum'];
    	$style = $inteos_array[0]['StyCod'];
    	$variant = $inteos_array[0]['Variant'];
    	$color_desc = $inteos_array[0]['ClrDesc'];
    	

    	// list($color, $size) = explode('-', $variant);

		$brlinija = substr_count($variant,"-");
		// echo $brlinija." ";

		if ($brlinija == 2)
		{
			list($color, $size1, $size2) = explode('-', $variant);
			$size = $size1."-".$size2;
			// echo $color." ".$size;	
		} else {
			list($color, $size) = explode('-', $variant);
			// echo $color." ".$size;
		}

    	// dd($style);
    	// dd($color);
    	// dd($size);

    	$size1 = str_replace("/","-",$size);
		/*
		$size2 = str_replace("ANNI","",$size1);
		$size3 = str_replace("Y","",$size2);
		$size_to_search = str_replace("A","",$size3);
		*/
		$size_to_search = trim($size1);
		// dd($size_to_search);

		/*
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
			WHERE [Cod_Art_CZ] = '".$style."' AND [Cod_Col_CZ] = '".$color."' AND [Tgl_ITA] like '".$size_to_search."' "));
    	// '.$size_to_search."%'


		if ($cartiglio) {
			//continue
		} else {
        	$msg = 'Cannot find SKU in Cartiglio DB, NE POSTOJI SKU U CARTIGLIO DB !!! ZOVI ODMAH IT SEKTOR!';
        	return view('cbextralabels.error', compact('msg'));
    	}

		$cartiglio_array = object_to_array($cartiglio);
    	$barcode = $cartiglio_array[0]['Cod_Bar'];
    	$barcode_3 = substr($cartiglio_array[0]['Cod_Bar'], -3, 3);
    	// dd($barcode_3);
    	$color_desc = $cartiglio_array[0]['Descr_Col_CZ'];

    	$size_ita = $cartiglio_array[0]['Tgl_ITA'];
    	$size_ita = str_replace("ANNI","",$size_ita);
		$size_ita = str_replace("Y","",$size_ita);
		$size_ita = str_replace("A","",$size_ita);
		$size_ita = trim($size_ita);

    	$size_eng = $cartiglio_array[0]['Tgl_ENG'];
    	$size_eng = str_replace("ANNI","",$size_eng);
		$size_eng = str_replace("Y","",$size_eng);
		$size_eng = str_replace("A","",$size_eng);
		$size_eng = trim($size_eng);

    	$size_spa = $cartiglio_array[0]['Tgl_SPA'];
    	$size_spa = str_replace("ANNI","",$size_spa);
		$size_spa = str_replace("Y","",$size_spa);
		$size_spa = str_replace("A","",$size_spa);
		$size_spa = trim($size_spa);

    	$size_eur = $cartiglio_array[0]['Tgl_EUR'];
    	$size_eur = str_replace("ANNI","",$size_eur);
		$size_eur = str_replace("Y","",$size_eur);
		$size_eur = str_replace("A","",$size_eur);
		$size_eur = str_replace("cm","",$size_eur);
		$size_eur = trim($size_eur);

    	$size_usa = $cartiglio_array[0]['Tgl_USA'];
    	$size_usa = str_replace("ANNI","",$size_usa);
		$size_usa = str_replace("Y","",$size_usa);
		$size_usa = str_replace("A","",$size_usa);
		$size_usa = trim($size_usa);

    	// $printer_name = 'SBT-WP01';
    	$printed = 0;

    	// Record Labels
		try {
			$table = new CBlabels;

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
			
			// $table->save();

		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save cb label in table";
			return view('cbextralabels.error',compact('msg'));
		}
		*/
		

		$brcrtica = substr_count($inteos_array[0]['BlueBoxNum'],"-");
		// echo $brcrtica." ";
		// dd($brcrtica);
		// dd($inteos_array[0]['BlueBoxNum']);

		if ($brcrtica == 1)
		{
			list($one, $two) = explode('-', $inteos_array[0]['BlueBoxNum']);
			$po = $one;
			// dd($po);
			// $po = substr($inteos_array[0]['BlueBoxNum'], -9, 6);


		} else {
			$po = substr($inteos_array[0]['BlueBoxNum'], -9, 6);			
			
		}

		// $po = substr($inteos_array[0]['BlueBoxNum'], -9, 6);
		// dd($po);

		$bb_3 = substr($inteos_array[0]['BlueBoxNum'], -3, 3);
		// dd($bb_3);

		/*			
		$bagno_temp = $inteos_array[0]['Bagno'];
		if (strpos($bagno_temp, '*') !== false) {
			list($bagno, $marker) = explode('*', $bagno_temp);
		} else {
			$bagno = $bagno_temp;
		}
		// dd($bagno);
		*/

		$bagno = $inteos_array[0]['Bagno'];
		$marker = $inteos_array[0]['IDMarker'];
		
		$style;
		$color;
		$color_desc = $color_desc;

		$size_ita = $size_to_search;
		$size_usa = '';
		$size_eur = '';
		$size_spa = '';
		$size_eng = '';

		$bb_qty = $inteos_array[0]['BoxQuant'];

		return view('cbextralabels.checkbox',compact('po','bb_3','bagno','marker','style','color','color_desc','size_ita','size_eng','size_spa',
			'size_eur','size_usa','bb_qty','printer_name'));
		// return Redirect::to('/');


		/*
		if ($msg1 != ''){
			return view('batch.sample', compact('msg1','batch_name'));
		}
		*/
			
		// return Redirect::to('/');
	}

	public function checkbox_store(Request $request) 
	{
		//
		$this->validate($request, ['no_of_box' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )

		$po = $input['po'];
		$bb_3 = $input['bb_3'];
		$bagno = $input['bagno'];
		$marker = $input['marker'];
		$style = $input['style'];
		$color = $input['color'];
		$color_desc = $input['color_desc'];
		$size_ita = $input['size_ita'];
		$size_eng = $input['size_eng'];
		$size_spa = $input['size_spa'];
		$size_eur = $input['size_eur'];
		$size_usa = $input['size_usa'];
		$bb_qty = $input['bb_qty'];
		$printer_name = $input['printer_name'];
		
		//
		$no_of_box = intval($input['no_of_box']);

		if (isset($input['extrabb'])) {
			$extrabb = intval($input['extrabb']);	
		} else {
			$extrabb = 0;
		}

		if (isset($input['readybb'])) {
			$readybb = intval($input['readybb']);
		} else {
			$readybb = 0;
		}

		$sugested_qty = intval($input['bb_qty']) / $no_of_box;
		$sugested_qty = round($sugested_qty);
		
		return view('cbextralabels.typeqty',compact('po','bb_3','bagno','marker','style','color','color_desc','size_ita','size_eng','size_spa',
				'size_eur','size_usa','bb_qty','printer_name','no_of_box','extrabb','readybb','sugested_qty'));

	}
	
	public function typeqty_store(Request $request) 
	{
		//
		$this->validate($request, []);
		$input = $request->all(); // change use (delete or comment user Requestl; )

		$po = $input['po'];
		$bb_3 = $input['bb_3'];
		$bagno = $input['bagno'];
		$marker = $input['marker'];
		$style = $input['style'];
		$color = $input['color'];
		$color_desc = $input['color_desc'];
		$size_ita = $input['size_ita'];
		$size_eng = $input['size_eng'];
		$size_spa = $input['size_spa'];
		$size_eur = $input['size_eur'];
		$size_usa = $input['size_usa'];
		$bb_qty = $input['bb_qty'];
		$printer_name = $input['printer_name'];
		$no_of_box = $input['no_of_box'];
		$extrabb = $input['extrabb'];
		$readybb = $input['readybb'];
		$date = date("d.m.Y");

		for ($i=1; $i <= $no_of_box ; $i++) { 
			$boxqty[] = $input['boxqty'.$i];
		}

		$sum = 0;
		foreach ($boxqty as $box=>$qty) {
		    $sum += $qty;
		}
		// dd($sum);

		if ($readybb == 0) {
			$boxqty[] = $sum;
		}

		if ($extrabb == 1) {
			$groupextrabb = 1;
		} else {
			$groupextrabb = 0;
		}

		foreach ($boxqty as $box => $qty) {
			
			try {
				
				$table = new CBExtralabels;

				$table->po = $po;
				$table->bb_3 = $bb_3;

				$table->bagno = $bagno;
				$table->marker = $marker;

				$table->style = $style;
				$table->color = $color;
				$table->color_desc = $color_desc;
				
				$table->size_ita = $size_ita;
				$table->size_eng = $size_eng;
				$table->size_spa = $size_spa;
				$table->size_eur = $size_eur;
				$table->size_usa = $size_usa;

				$table->qty_to_print = 1;
				$table->bb_qty = $bb_qty;
				$table->physical_qty = $qty;

				$table->no_of_box = $no_of_box;

				$table->extrabb = 0;
				$table->groupextrabb = $groupextrabb;
				$table->readybb = $readybb;
				
				$table->date = $date;
				$table->printer_name = $printer_name;
				$table->printed = 0;
				
				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save cb extra label in table";
				return view('cbextralabels.error',compact('msg'));
			}
		}

		if ($extrabb == 1) {

			try {
				
				$table = new CBExtralabels;

				$table->po = $po;
				$table->bb_3 = $bb_3;

				$table->bagno;
				$table->marker = $marker;

				$table->style = $style;
				$table->color = $color;
				$table->color_desc = $color_desc;
				
				$table->size_ita = $size_ita;
				$table->size_eng = $size_eng;
				$table->size_spa = $size_spa;
				$table->size_eur = $size_eur;
				$table->size_usa = $size_usa;

				$table->qty_to_print = 1;
				$table->bb_qty = $bb_qty;
				$table->physical_qty;

				$table->no_of_box;

				$table->extrabb = $extrabb;
				$table->groupextrabb = $groupextrabb;
				$table->readybb;
				
				$table->date = $date;
				$table->printer_name = $printer_name;
				$table->printed = 0;
				
				$table->save();
				
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save cb extra label in table";
				return view('cbextralabels.error',compact('msg'));
			}

		}

		return Redirect::to('/');

	}


}