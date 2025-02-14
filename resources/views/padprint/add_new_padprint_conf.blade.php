@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading">Add new line</div>
				<br>
					{!! Form::open(['method'=>'POST', 'url'=>'/padprint_conf_insert']) !!}

						<div class="panel-body">
						<p>Style: <span style='color:red'>*</span></p>
							{!! Form::text('style', null, ['class' => 'form-control']) !!}
						</div>
						
						<div class="panel-body">
						<p>Color: <span style='color:red'>*</span></p>
							{!! Form::text('color', null, ['class' => 'form-control']) !!}
						</div>

						<div class="panel-body">
						<p>Cliche: <span style='color:red'>*</span></p>
							{!! Form::text('cliche', null, ['class' => 'form-control']) !!}
						</div>

						<div class="panel-body">
						<p>Cliche Color: <span style='color:red'>*</span></p>
							{!! Form::text('cliche_color', null, ['class' => 'form-control']) !!}
						</div>

						<div class="panel-body">
						<p>Size relevant: <span style='color:red'>*</span></p>
							{!! Form::select('size_relevant', array('' => '' ,'YES'=>'YES','NO'=>'NO'), '', array('class' => 'form-control')); !!} 
						</div>

						<br>
						
						{!! Form::submit('Add', ['class' => 'btn  btn-success center-block']) !!}
						<br>
						@include('errors.list')

					{!! Form::close() !!}
				
				
				<div class="panel-body">
					<div class="">
						<a href="{{url('/padprint_conf')}}" class="btn btn-default">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection