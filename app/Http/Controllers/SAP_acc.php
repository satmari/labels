<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\SAP_acc_table;
use App\SAP_acc_print;
use DB;

use Session;

class SAP_acc extends Controller {

	public function index()
	{
		//
		$printer_name = Session::get('printer_name');
		if ($printer_name != NULL) {
			//continue
			
		} else {
			$msg = 'Printer must be selected, PRINTER MORA BITI SLEKTOVAN! err';
        	return view('sap.error', compact('msg'));
		}

		$sku_data = DB::connection('sqlsrv4')->select(DB::raw("SELECT [Sap SKU code] as sku FROM [Gordon_LIVE].[dbo].[GORDON\$Item Variant] WHERE [Sap SKU code] != '' "));
		// dd($sap_data);

		return view('sap.index', compact('printer_name', 'sku_data'));
	}

	public function take_sap_code(Request $request) {

		// dd($request);
		$this->validate($request, ['qty' => 'required', 'location' => 'required', 'sku' => 'required']);
		$input = $request->all();
		// dd($input);

		$sku = $input['sku'];
		$location = $input['location'];
		$qty = (float)$input['qty'];
		// dd($qty);
		$printer_name = $input['printer_name'];
		// dd($printer_name);


		// dd("SKU: ".$sku." , location: ".$location." , qty: ".$qty);

		$data = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
		[Item No_] as nav_item
      ,[Code] as nav_variant
      ,[Description] as nav_desc1
      ,[Description 2] as nav_desc2
      ,[PfsVertical Component] as nav_color
      ,[PfsHorizontal Component] as nav_size
   
      ,[Sap SKU code] as sap_sku
      ,[SAP Color] as sap_color
      ,[SAP Size] as sap_size
      ,[SAP Item code] as sap_item
       ,(SELECT TOP 1 [Barcode No_] as not_hu FROM [Gordon_LIVE].[dbo].[GORDON\$Barcode] WHERE [No_] = [Item No_] AND [Variant Code] = [Code]) as not_hu

		  FROM [Gordon_LIVE].[dbo].[GORDON\$Item Variant]
		  WHERE [Sap SKU code] = '".$sku."'

		"));

		// dd($data);

		if (isset($data[0]->sap_sku)) {
			// dd($data[0]->sap_sku);

			$sap_sku = $data[0]->sap_sku;
			$sap_color = $data[0]->sap_color;
			$sap_size = $data[0]->sap_size;
			$sap_item = $data[0]->sap_item;

			$nav_item = $data[0]->nav_item;
			$nav_variant = $data[0]->nav_variant;
			$nav_desc1 = $data[0]->nav_desc1;
			$nav_desc2 = $data[0]->nav_desc2;
			$nav_color = $data[0]->nav_color;
			$nav_size = $data[0]->nav_size;

			$not_hu = $data[0]->not_hu;
			// dd($not_hu);

		} else {
			dd("data not found");
		}


		$uom_data = DB::connection('sqlsrv')->select(DB::raw("SELECT sap_uom FROM [labels].[dbo].[sap_uoms] WHERE nav_item = '".$nav_item."' "));

		if (isset($uom_data[0]->sap_uom)) {

			$sap_uom = $uom_data[0]->sap_uom;
			// dd($sap_uom);

		} else {
			// dd("SAP Uom not exist");
			$sap_uom = '[]';
		}


		$starting = '10000040011';
		$additional = 900000000;


		$last_barcode = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 barcode FROM [labels].[dbo].[sap_acc_table] ORDER BY  barcode desc"));
		// dd($last_barcode);

		if (isset($last_barcode[0]->barcode)) {

			$barcode = $last_barcode[0]->barcode + 1;

		} else {

			$barcode = $additional;
		}

		$printed = '1';

		// dd($barcode);

		// Record Labels
		try {
			$table = new SAP_acc_table;

			$table->sap_sku = $sap_sku;
			$table->sap_color = $sap_color;
			$table->sap_size = $sap_size;
			$table->sap_item = $sap_item;
			$table->sap_uom = $sap_uom;

			$table->nav_item = $nav_item;
			$table->nav_variant = $nav_variant;
			$table->nav_desc1 = $nav_desc1;
			$table->nav_desc2 = $nav_desc2;
			$table->nav_color = $nav_color;
			$table->nav_size = $nav_size;

			$table->not_hu = $not_hu;

			// $table->barcode = $barcode;
			$table->barcode = $barcode;
			$table->full_barcode = $starting.$barcode;

			$table->location = $location;
			$table->qty = $qty;

			$table->printed = $printed;
			$table->printer = $printer_name;
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save SAP_acc_table";
			return view('sap.error',compact('msg'));
		}

		return Redirect::to('/sap_acc');

	}

	

}
