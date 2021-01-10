<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\SAP_acc_table;
use App\SAP_hu_table;
use DB;

use Session;


class SAP_hu extends Controller {


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

		// $sku_data = DB::connection('sqlsrv4')->select(DB::raw("SELECT [Sap SKU code] as sku FROM [Gordon_LIVE].[dbo].[GORDON\$Item Variant] WHERE [Sap SKU code] != '' "));
		// dd($sap_data);

		return view('sap.index_hu', compact('printer_name'));
	}

	public function take_sap_code_su(Request $request) {

		// dd($request);
		$this->validate($request, ['nav_hu' => 'required']);
		$input = $request->all();
		// dd($input);

		$nav_hu = $input['nav_hu'];
		$printer_name = $input['printer_name'];
		// dd($nav_hu);

		/*
		$nav_data = DB::connection('sqlsrv4')->select(DB::raw("SELECT DISTINCT 
                         hu.[HU No_] as nav_hu,
                         hu.[Item No_] as nav_item, 
                         hu.[Variant Code] as nav_variant, 
                         hu.[Balance] as nav_qty, 
                         (CASE WHEN Status = '0' THEN 'Open' WHEN Status = '1' THEN 'Picked' WHEN Status = '2' THEN 'Issued' WHEN Status = '3' THEN 'Consumed' END) AS StatusS, 
			             hu.[Bin Code] as nav_bin,
			             hu.[Location Barcode] as nav_location_barcode,
			             sl.[Cell Code] as nav_location,
			             hu.[Batch_Dye lot] as nav_bagno

			FROM            dbo.[GORDON\$Handling Unit] AS hu LEFT OUTER JOIN
			                         dbo.[GORDON\$WMS Storage Location] AS sl ON hu.[Location Barcode] = sl.[Barcode No_]
			WHERE        (hu.Balance <> 0) AND (hu.Status = 0) AND (hu.[HU No_] NOT LIKE 'NOT%') AND hu.[HU No_] = '".$nav_hu."'
			GROUP BY hu.[Item No_], hu.Balance, hu.[Variant Code], hu.[HU No_], hu.Status, hu.[Bin Code], hu.[Location Barcode], sl.[Cell Code], hu.[Batch_Dye lot]
			ORDER BY hu.[Item No_], hu.[Variant Code]"));

		// dd($nav_data);
		*/
		
		$nav_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_hu_table WHERE nav_hu = '".$nav_hu."' "));


			$nav_hu = $nav_hu;
			$nav_item = $nav_data[0]->nav_item;
			$nav_variant = $nav_data[0]->nav_variant;
			$nav_qty = (float)$nav_data[0]->nav_qty;     /// check float
			$nav_bin = $nav_data[0]->nav_bin;
			$nav_location_barcode = $nav_data[0]->nav_location_barcode;
			$nav_location = $nav_data[0]->nav_location;
			$nav_bagno = $nav_data[0]->nav_bagno;
			// dd($nav_bagno);


			$sap_data = DB::connection('sqlsrv4')->select(DB::raw("SELECT 
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
		       
			  FROM [Gordon_LIVE].[dbo].[GORDON\$Item Variant]
			  WHERE [Item No_] = '".$nav_item."' AND [Code] = '".$nav_variant."'


			"));

			// dd($sap_data);

			$nav_desc1 =  $sap_data[0]->nav_desc1;
			$nav_desc2 =  $sap_data[0]->nav_desc2;
			$sap_sku =  $sap_data[0]->sap_sku;
			$sap_color =  $sap_data[0]->sap_color;
			$sap_size =  $sap_data[0]->sap_size;
			$sap_item =  $sap_data[0]->sap_item;

			$sap_batch = '';
			$sap_uom = 'MTR';
			
			$sap_bagno = $nav_bagno;

			$sap_data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT scaned_count FROM sap_hu_table WHERE nav_hu = '".$nav_hu."' "));
			if (isset($sap_data1[0])) {
				$count = (int)$sap_data1[0]->scaned_count;
			} else {
				$count = 0;
			}
			$scaned_count = $count + 1;

			$starting = '10000040011';
			$additional = 900001700;
			$last_barcode = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 barcode FROM [labels].[dbo].[sap_hu_table] ORDER BY  barcode desc"));
			if (isset($last_barcode[0]->barcode)) {

				$barcode = $last_barcode[0]->barcode + 1;

			} else {

				$barcode = $additional;
			}
			
			$printer = $printer_name;
			$printed = '1';

			

		$table = SAP_hu_table::findOrFail($nav_data[0]->id);

		try {

			// $table->nav_hu = $nav_hu;
			// $table->nav_item = $nav_item;
			// $table->nav_variant = $nav_variant;
			// $table->nav_bagno = $nav_bagno;
			// $table->nav_qty = $nav_qty;
			// $table->nav_bin = $nav_bin;
			// $table->nav_location_barcode = $nav_location_barcode;
			// $table->nav_location = $nav_location;
			$table->nav_desc1 = $nav_desc1;
			$table->nav_desc2 = $nav_desc2;
			

			$table->sap_sku = $sap_sku;
			$table->sap_color = $sap_color;
			$table->sap_item = $sap_item;
			$table->sap_size = $sap_size;

			$table->sap_uom = $sap_uom;
			$table->sap_batch = $sap_batch;

			$table->sap_bagno = $sap_bagno;

			$table->barcode = $barcode;
			$table->sap_su = $starting.$barcode;

			$table->scaned_count = $scaned_count;
			
			$table->printed = $printed;
			$table->printer = $printer_name;
		
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save SAP_hu_table";
			return view('sap.error',compact('msg'));
		}


			return Redirect::to('/sap_hu');



	}



}
