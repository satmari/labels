@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Scan BB and print <b>PADPRINT</b> labels</div>
                
                {!! Form::open(['url' => 'padprint_post']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                <div class="panel-body">
                    <p><b>Location of Inteos:</b></p>
                    {!! Form::select('inteosdb_new', array('1'=>'Subotica','2'=>'Kikinda'), $inteosdb, array('class' => 'form-control')); !!} 
                </div>
                
                <div class="panel-body">
                    <p>BlueBox:</p>
                    {!! Form::input('number', 'bb_code', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="panel-body">
                    {!! Form::submit('Find BB', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                
            </div>
        </div>
    </div>
</div>
@endsection