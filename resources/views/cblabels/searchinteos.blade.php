@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Find BB and print CB labels</div>
                
                {!! Form::open(['url' => 'searchinteos_store']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                <div class="panel-body">
                    <p>Location of Inteos: <span style="color:red;">(Ovo jos nije u funkciji, ali ostalo radi normalno)</span></p>
                    {!! Form::select('inteosdb_new', array('1'=>'Gordon Subotica','2'=>'Gordon Kikinda'), $inteosdb, array('class' => 'form-control')); !!} 
                </div>
                
                <div class="panel-body">
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