<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\PadPrintLabels;
use DB;

use Session;

class ControllerPadPrinting extends Controller {

	public function index()
	{
		//

		$inteosdb = Session::get('inteosdb');
		// dd($inteosdb);
    	if ($inteosdb != NULL) {
			//continue
			$inteosdb = $inteosdb;
		} else {
			$inteosdb = '1';
		}

		
		return view('padprint.index', compact('inteosdb'));
	}

	public function padprint_post(Request $request)
	{
		//
		// dd($request);
		$this->validate($request, ['bb_code' => 'required']);
		$input = $request->all();

		$bbcode = $input['bb_code'];

		$inteosdb = $input['inteosdb_new'];
		Session::set('inteosdb', $inteosdb );

		$printer_name = Session::get('printer_name');
    	if ($printer_name != NULL) {
			//continue
		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('padprint.error', compact('msg'));
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
	        	$msg = 'Cannot find BB in Gordon Inteos, NE POSTOJI PLAVA KUTIJA U GORDON INTEOSU !';
	        	return view('padprint.error', compact('msg'));
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
	        	return view('padprint.error', compact('msg'));
	    	}

			
			
	    } else {

	    	$msg = 'Cannot find CB in any Inteos for this BB, NE POSTOJI KARTONSKA KUTIJA VEZANA ZA OVU PLAVU KUTIJU U INTEOSU !';
        	return view('padprint.error', compact('msg'));

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
		

		// dd($inteos[0]->IDMarker);
		$marker = $inteos[0]->IDMarker;
		$style = $inteos[0]->StyCod;
		$variant = $inteos[0]->Variant;

		// dd($variant);
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

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM pad_print_confs WHERE style = '".$style."' AND color = '".$color."' "));
		// dd($data);

		if (isset($data[0]->id)) {
			// dd($data);
			$cliche = $data[0]->cliche;
			$cliche_color = $data[0]->cliche_color;
			$size_relevant = $data[0]->size_relevant;

		} else {

			$cliche = "";
			$cliche_color =  "";
			$size_relevant =  "";

		}

		return view('padprint.padprint', compact('marker','cliche','cliche_color','size_relevant'));
	}

	public function padprint_print(Request $request)
	{
		//
		// dd($request);
		$this->validate($request, ['marker' => 'required'/*,'cliche'=>'required', 'color'=>'required'*/]);
		$input = $request->all();
		// dd($input);

		$marker = $input['marker'];
		$cliche = $input['cliche'];
		$color = $input['color'];
		$size_relevant = $input['size_relevant'];
		// dd($input['labelqty']);


		if ($input['labelqty'] == "") {
			$msg = 'Number of labels must be set, BROJ NALEPNICA SE MORA UNETI!';
        	return view('padprint.error', compact('msg'));
		} else {
			if ($input['labelqty'] > 40) {
				$msg = 'Number of labels can not be bigger of 40, BROJ NALEPNICA NE SME BITI VECI OD 40!';
	        	return view('padprint.error', compact('msg'));
			}

			$labelqty = $input['labelqty'];

		}
		
		$printed = 1;

		$printer_name = Session::get('printer_name');
    	if ($printer_name != NULL) {
			//continue
		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('padprint.error', compact('msg'));
		}



		for ($l=0; $l < $labelqty ; $l++) { 
				
			// Record Labels
			try {
				$table = new PadPrintLabels;

				$table->marker = $marker;
				$table->cliche = $cliche;
				$table->color = $color;
				
				$table->printer_name = $printer_name;
				$table->printed = $printed;
				$table->size_relevant = $size_relevant;
				
				$table->save();

			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save  in table";
				return view('padprint.error',compact('msg'));
			}	    		
    	}

		// dd($marker);

		$inteosdb = Session::get('inteosdb');
		// dd($inteosdb);
    	if ($inteosdb != NULL) {
			//continue
			$inteosdb = $inteosdb;
		} else {
			$inteosdb = '1';
		}
		
		return view('padprint.index', compact('inteosdb'));
	}
	

}
