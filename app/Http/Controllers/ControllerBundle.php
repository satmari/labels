<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Bundlelabels;
use DB;

use Session;

class ControllerBundle extends Controller {

	public function index()
	{
		//
		return view('bundlelabels.index');
	}

	public function choosebundleqty () {

		$bundleqty = 15;
		$labels_per_bundle = 1;
		return view('bundlelabels.choosebundleqty', compact('bundleqty','labels_per_bundle'));
	}

	public function bundle_qty(Request $request) {

		$this->validate($request, ['bundleqty' => 'required']);
		$input = $request->all();
		
		$bundleqty = $input['bundleqty'];
		$labels_per_bundle = $input['labels_per_bundle'];
		// dd($bundleqty);

		//
		$inteosdb = Session::get('inteosdb');
        // dd($inteosdb);

        if (is_null($inteosdb)) {
        	$inteosdb = '1';	
        }

		return view('bundlelabels.searchinteos',compact('inteosdb','bundleqty','labels_per_bundle'));

	}
	/*
	public function searchininteos() 
	{
		//
		$inteosdb = Session::get('inteosdb');
        // dd($inteosdb);

        if (is_null($inteosdb)) {
        	$inteosdb = '1';	
        }

		return view('bundlelabels.searchinteos',compact('inteosdb'));
	}
	*/
	public function searchinteos_bundle(Request $request) 
	{
		//
		$this->validate($request, ['bb_code' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		//1717281

		$bbcode = $input['bb_code'];
		$bundleqty = $input['bundleqty'];
		$labels_per_bundle = $input['labels_per_bundle'];
		// dd($bundleqty);

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
        	return view('bundlelabels.error', compact('msg'));
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
				sku.Variant
				FROM            	CNF_BlueBox AS bb 
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
								sku.Variant"
				), array(
					'somevariable' => $bbcode
			));
			// dd($inteos);

			if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Gordon Inteos, NE POSTOJI PLAVA KUTIJA U GORDON INTEOSU !';
	        	return view('bundlelabels.error', compact('msg'));
	    	}

			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv2')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count != 0) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Gordon Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U GORDON INTEOSU !';
	        	return view('bundlelabels.error', compact('msg'));
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
				sku.Variant
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
								sku.Variant"
				), array(
					'somevariable' => $bbcode
			));
			// dd($inteos);

			if ($inteos) {
				//continue
			} else {
	        	$msg = 'Cannot find BB in Kikinda Inteos, NE POSTOJI PLAVA KUTIJA U KIKINDA INTEOSU !';
	        	return view('bundlelabels.error', compact('msg'));
	    	}

			// Inteos - number of labels
			$inteos_count = DB::connection('sqlsrv5')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			if ($inteos_count != 0) {
				//continue
			} else {
	        	$msg = 'Cannot find CB in Kikinda Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U KIKINDA INTEOSU !';
	        	return view('bundlelabels.error', compact('msg'));
	        }
			
	    } else {

	    	$msg = 'Cannot find CB in any Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U INTEOSU !';
        	return view('bundlelabels.error', compact('msg'));

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
    	$bb_qty = $inteos_array[0]['BoxQuant'];

    	$po = substr($inteos_array[0]['BlueBoxNum'], -9, 6);
		$bb_3 = substr($inteos_array[0]['BlueBoxNum'], -3, 3);
    	
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

    	if ($bb_qty == 0){
    		$msg = 'BB quantity is 0 !';
        	return view('bundlelabels.error', compact('msg'));
    	}

		$labels = ceil($bb_qty / $bundleqty);
		// dd("bb_qty: ".$bb_qty." , bundleqty: ".$bundleqty." ,  labels: ".$labels);

		for ($i=0; $i < $labels; $i++) { 
			
			// var_dump($labels);

			// $printer_name = 'SBT-WP01';
	    	$printed = 0;

	    	for ($l=0; $l < $labels_per_bundle ; $l++) { 
				
				// Record Labels
				try {
					$table = new Bundlelabels;

					$table->bbcode = $bbcode;
					$table->po = $po;
					$table->bb = $bb;
					$table->bb_3 = $bb_3;

					$table->style = $style;
					$table->color = $color;
					$table->size_ita = $size_to_search;
					
					$table->bundle = $i + 1;
					
					$table->printer_name = $printer_name;
					$table->printed = $printed;
					$table->labels_per_bundle = $labels_per_bundle;
					
					$table->save();

				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save bundle label in table";
					return view('bundlelabels.error',compact('msg'));
				}	    		
	    	}

		}
		
		// dd("Stop");
		return Redirect::to('/bundle');

		/*
		return view('cbextralabels.checkbox',compact('po','bb_3','bagno','marker','style','color','color_desc','size_ita','size_eng','size_spa',
			'size_eur','size_usa','bb_qty','printer_name'));
		*/

		/*
		if ($msg1 != ''){
			return view('batch.sample', compact('msg1','batch_name'));
		}
		*/
			
		// return Redirect::to('/');
	}


}
