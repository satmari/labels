<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\PLLabels;
use DB;

use Session;

class ControllerPackingList extends Controller {


	public function index()
	{
		//
	}

	public function selectinbound()
	{
		
		$list_of_inbound = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
  				DISTINCT [Document No_] as document
       
  				FROM [Gordon_test].[dbo].[GORDON\$Inbound Delivery Header]
  				WHERE [Status] = '0'"));

		$list_of_inbound = (object) $list_of_inbound;
		// dd($list_of_inbound);

		// $list_of_producers = Producer::orderBy('id')->where('producer_type','=',$input['type'])->pluck('producer_name','id');
		$printer_name = Session::get('printer_name');
		// dd($printer_name);

		if ($printer_name != NULL) {
			return view('packinglist.searchinbound',compact('list_of_inbound', 'printer_name'));
		} else {
			return view('packinglist.searchinbound',compact('list_of_inbound', 'printer_name'));
		}

	}

	public function selectinbound_post(Request $request)
	{
		// dd($request);
		$this->validate($request, ['document' => 'required']);
		$input = $request->all();

		$document = $input['document'];
		// dd($document);

		$printer_name = Session::get('printer_name');
    	if ($printer_name != NULL) {
			//continue
			if (($printer_name == 'Krojacnica') OR ($printer_name == 'Magacin')){
				//continue
			} else {
				$msg = 'Printer must be Krojacnica or Magacin, PRINTER MORA BITI KROJACNICA ILI MAGACIN';
        		return view('packinglist.error', compact('msg'));
			}

		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN!';
        	return view('packinglist.error', compact('msg'));
		}
		
		$inbound = DB::connection('sqlsrv4')->select(DB::raw("SELECT *
				FROM [Gordon_test].[dbo].[GORDON\$Packing List]
				WHERE [Document No_] = :somevariable ORDER BY [HU No_] asc"), array(
			'somevariable' => $document,
		));

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

		$inbound_array = object_to_array($inbound);
		// dd(count($inbound));

		for ($i=0; $i < count($inbound); $i++) { 
			
			$document = $inbound_array[$i]['Document No_'];
			$vendor = $inbound_array[$i]['Vendor No_'];
			$style = $inbound_array[$i]['Article'];
			$color = $inbound_array[$i]['Color'];
			$size = $inbound_array[$i]['Size'];
			$desc = $inbound_array[$i]['Description'];
			$variant = $inbound_array[$i]['Variant Code'];
			$hu = $inbound_array[$i]['HU No_'];
			$qty = floatval($inbound_array[$i]['Qty']);
			$uom = $inbound_array[$i]['UoM'];
			$batch = $inbound_array[$i]['Batch'];
			$file = $inbound_array[$i]['File Name'];

			$printer_name = $printer_name;
			$printed = 1;
			
			// dd($hu);

			// Record Labels
			try {
				$table = new PLLabels;

				$table->inbound = $document;
				$table->vendor = $vendor;

				$table->style = $style;
				$table->color = $color;
				$table->size = $size;
				$table->desc = $desc;
				$table->variant = $variant;

				$table->hu = $hu;
				$table->qty = $qty;

				$table->uom = $uom;
				
				$table->batch = $batch;
				$table->file = $file;

				$table->printer_name = $printer_name;
				$table->printed = $printed;
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save cb label in table";
				return view('packinglist.error',compact('msg'));
			}
		}

		$batch = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT batch,
				       									  (count(batch)) as numlines
				FROM pl_labels
				WHERE inbound = :somevariable
				GROUP BY batch
				"), array(
			'somevariable' => $document,
		));

		$batch = (object) $batch;
		// dd($batch);

		try {
			return view('packinglist.selectbatch',compact('batch','document'));
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('packinglist.selectbatch',compact('batch','document'));
		}
	}	

	public function selectbatch_post(Request $request, $inbound)
	{
		// dd($request);
		$this->validate($request, ['batch' => 'required']);
		$input = $request->all();

		$batch = $input['batch'];
		$document = $inbound;
		
		$update = DB::connection('sqlsrv')->select(DB::raw("SELECT id, printed
				FROM pl_labels
				WHERE inbound = :somevariable1 AND batch = :somevariable2 "), array(
			'somevariable1' => $inbound, 
			'somevariable2' => $batch,
		));
		// dd($update);

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

		$update_array = object_to_array($update);

		for ($i=0; $i < count($update); $i++) { 

			try {
				$table = PLLabels::findOrFail($update[$i]->id);
				$table->printed = 0;
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to edit PLLabels table";
				return view('packinglist.error',compact('msg'));		
			}
		}

		$batch = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT batch,
				       									  (count(batch)) as numlines
				FROM pl_labels
				WHERE inbound = :somevariable
				GROUP BY batch
				"), array(
			'somevariable' => $document,
		));

		// dd($batch->batch);
		$batch = (object) $batch;

		try {
			return view('packinglist.selectbatch',compact('batch','document'));
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('packinglist.selectbatch',compact('batch','document'));
		}
	}

	public function deleteinbound($inbound)
	{
		// dd($inbound);
		$delete = DB::connection('sqlsrv')->select(DB::raw("SELECT id
				FROM pl_labels
				WHERE inbound = :somevariable1"), array(
			'somevariable1' => $inbound
		));
		
		
		for ($i=0; $i < count($delete); $i++) { 

			try {
				$table = PLLabels::findOrFail($delete[$i]->id);
				$table->delete();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to delete PLLabels table";
				return view('packinglist.error',compact('msg'));		
			}
		}

		return Redirect::to('/selectinbound');
	}

	public function deleteall()
	{
		// dd($inbound);
		$delete = DB::connection('sqlsrv')->select(DB::raw("SELECT id
				FROM pl_labels"));
		
		
		for ($i=0; $i < count($delete); $i++) { 

			try {
				$table = PLLabels::findOrFail($delete[$i]->id);
				$table->delete();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to delete PLLabels table";
				return view('packinglist.error',compact('msg'));		
			}
		}
		return Redirect::to('/selectinbound');
	}

}