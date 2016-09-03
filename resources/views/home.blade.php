@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Print labels</div>

				<br><br><br>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/cblabels')}}" class="btn btn-success btn-lg center-block"><br>Skeniraj BlueBox i stampaj CartonBox nalepnice<br><br></a>
					</div>
				</div>
				<br><br><br>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/cblabel')}}" class="btn btn-primary btn-lg center-block"><br>Skeniraj CartonBox i stampaj JEDNU CartonBox nalepnicu<br><br></a>
					</div>
				</div>
				<br><br><br>
			</div>
		</div>
	</div>
</div>
@endsection
