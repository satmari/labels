@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				
				<a href="{{ url('/add_new_padprint_conf') }}" class="btn btn-info btn-s">Add new line</a>
				
				<br>
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort"
                >
                <!--
                data-show-export="true"
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
				    <thead>
				        <tr>
				           {{-- <th>id</th> --}}
				           <th>Stlye</th>
				           <th>Color</th>
				           <th>Cliche</th>
				           <th>Cliche Color</th>
				           <th>Size Relevant</th>

				           <th></th>
				           <!-- <th></th> -->
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}
				        	<td>{{ $d->style }}</td>
				        	<td>{{ $d->color }}</td>
				        	<td>{{ $d->cliche }}</td>
				        	<td>{{ $d->cliche_color }}</td>
				        	<td>{{ $d->size_relevant }}</td>

				        	
				        	
				        	<td><a href="{{ url('/padprint_conf/'.$d->id) }}" class="btn btn-info btn-xs center-block">Edit</a></td>
				        	
				        	
				        	
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection