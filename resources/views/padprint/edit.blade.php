@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit:</div>
				<br>
					{!! Form::model($data , ['method' => 'POST', 'url' => 'padprint_conf_update/'.$data->id /*, 'class' => 'form-inline'*/]) !!}

					{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
					<p>Style:</p>
						{!! Form::input('string', 'style', null, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Color:</p>
						{!! Form::input('string', 'color', null, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Cliche:</p>
						{!! Form::input('string', 'cliche', null, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Cliche Color:</p>
						{!! Form::input('string', 'cliche_color', null, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Size relevant:</p>
						{!! Form::select('size_relevant', array('' => '' ,'YES'=>'YES','NO'=>'NO'), $data->size_relevant , array('class' => 'form-control')); !!} 
					</div>
						
					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
					</div>
					<br>
					@include('errors.list')

					{!! Form::close() !!}
					
					
					
				<hr>
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