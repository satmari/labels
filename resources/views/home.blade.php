@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Print labels</div>

				@if (isset($printer_name))
				<div class="panel-body">
					Selected Printer is: <b>{{$printer_name}}</b>
				</div>
				@else 
				<div class="panel-body">
					<p style="color:red;"><b>Printer is not selected, STAMPAC nije izabran!!!</b></p>
				</div>
				@endif
				

				<br><br>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/cbextralabels')}}" class="btn btn-primary  center-block"><br>Skeniraj BlueBox i stampaj CartonBox nalepnice<br><br></a>
					</div>
				</div>
				<br><br><br>
				<!-- <div class="panel-body">
					<div class="">
						<a href="{{url('/cblabel')}}" class="btn btn-success  center-block"><br>Skeniraj CartonBox i stampaj JEDNU CartonBox nalepnicu<br><br></a>
					</div>
				</div>
				<br><br><br> -->
			</div>
		</div>
	</div>
</div>
@endsection
