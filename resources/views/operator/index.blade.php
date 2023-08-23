@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			
			<div class="panel panel-default">
				<div class="panel-heading" style='background-color:pink'>Stampanje nalepnica za operatere - <b>POJEDINACNO</b></div>
								

				<div class="panel-body">
					<div class="">
							
						{!! Form::open(['method'=>'POST', 'url'=>'/operator_print']) !!}
		
							<div class="panel-body">
								Izaberi stampac:
								@if (isset($printer_name1))
									{!! Form::select('printer_name1', array(''=>'','Subotica Preprodukcija'=>'Subotica Preprodukcija','Preparacija Zebra'=>'Subotica Preparacija','Kikinda Zebra Workstudy'=>'Kikinda Zebra Workstudy','Senta'=>'Senta'), $printer_name1 , array('class' => 'form-control')); !!} 
								@else 
									{!! Form::select('printer_name1', array(''=>'','Subotica Preprodukcija'=>'Subotica Preprodukcija','Preparacija Zebra'=>'Subotica Preparacija','Kikinda Zebra Workstudy'=>'Kikinda Zebra Workstudy','Senta'=>'Senta'), null, array('class' => 'form-control')); !!} 
								@endif
								
							</div>
							
							<p>Izaberi operatera: </p>
			                <select name="operater1" class="chosen">
			                	
			                	@if (isset($operater1))
			                    	<option value="{{ $operater1 }}" selected>{{ $operater1 }} - {{ $operater1_name }}</option>
			                    @else
			                    	<option value="" selected></option>
			                    @endif    
			                    
				                @foreach ($operators as $line)
				                    <option value="{{ $line->rnumber }}">
				                        {{ $line->rnumber }} - {{ $line->name }}
				                    </option>
				                @endforeach
			               	</select>
							<br><br>

							{!! Form::submit('Print', ['class' => 'btn  btn-success center-block']) !!}

							@include('errors.list')

						{!! Form::close() !!}

						@if (isset($msg1))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msg1 }}</b></span></i></small>
							
						@endif
						@if (isset($msg2))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msg2 }}</b></span></i></small>
						@endif
						@if (isset($msgs1))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msgs1 }}</b></span></i></small>
						@endif
						

						<!-- <hr> -->
						<!-- <a href="{{url('/')}}" class="btn btn-default center-block">Back</a> -->
					</div>
				</div>
			</div>
			<hr>
			<div class="panel panel-default">
				<div class="panel-heading" style='background-color:aqua'>Stampanje nalepnica za operatere - <b>VISE OPERATERA OD JEDNOM iz Excel fajla</b>
					<ul class="nav navbar-nav"></div>
								

				<div class="panel-body">
					<div class="">
							
						{!! Form::open(['files'=>'True', 'method'=>'POST', 'url'=>'/operator_print_multiple']) !!}
		
							<div class="panel-body">
								Izaberi stampac:
								@if (isset($printer_name2))
									{!! Form::select('printer_name2', array(''=>'','Subotica Preprodukcija'=>'Subotica Preprodukcija','Preparacija Zebra'=>'Subotica Preparacija','Kikinda Zebra Workstudy'=>'Kikinda Zebra Workstudy','Senta'=>'Senta'), $printer_name2 , array('class' => 'form-control')); !!} 
								@else 
									{!! Form::select('printer_name2', array(''=>'','Subotica Preprodukcija'=>'Subotica Preprodukcija','Preparacija Zebra'=>'Subotica Preparacija','Kikinda Zebra Workstudy'=>'Kikinda Zebra Workstudy','Senta'=>'Senta'), null, array('class' => 'form-control')); !!} 
								@endif
							</div>
							
							<div class="panel-body">
								Izaberi Excel file:
								{!! Form::file('file', ['class' => 'center-block']) !!}
							</div>
							<br>
							
							{!! Form::submit('Print', ['class' => 'btn  btn-success center-block']) !!}

							@include('errors.list')

						{!! Form::close() !!}

						@if (isset($msg3))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msg3 }}</b></span></i></small>
							
						@endif
						@if (isset($msg4))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msg4 }}</b></span></i></small>
						@endif

						@if (isset($msgs2))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msgs2 }}</b></span></i></small>
						@endif

						<!-- <hr> -->
						<!-- <a href="{{url('/')}}" class="btn btn-default center-block">Back</a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection