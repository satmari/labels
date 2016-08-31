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
		//1971107960

		$bbcode = $input['bb_code'];
		// dd($bbcode);
		
		$msg = '';
		$msg1 = '';
		//$msg2 = '';

		// Live database
		try {
			
			$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT COUNT([BoxNum]) as count FROM [BdkCLZG].[dbo].[CNF_CartonBox] WHERE [BBcreated] = :somevariable"), array(
					'somevariable' => $bbcode
			));

			// dd($inteos);
			
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
		
	    	$inteos_array = object_to_array($inteos);
	    	// dd($inteos_array);

	    	$count = $inteos_array[0]['count'];
	    	dd($count);



	    }
		catch (\Illuminate\Database\QueryException $e) {
			//return Redirect::to('/searchinteos');
			$msg = "Problem to save in cblabel table. try agan.";
			return view('cblabels.error',compact('msg'));
		}
		
		
	}
	

}
