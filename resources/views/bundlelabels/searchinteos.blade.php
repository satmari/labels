@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Find BB and print <b>BUNDLE</b> labels</div>
                
                {!! Form::open(['url' => 'searchinteos_bundle']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                {!!Form::hidden('bundleqty', $bundleqty) !!}
                {!!Form::hidden('labels_per_bundle', $labels_per_bundle) !!}
                
                 <div class="panel-body">
                    <p>Location of Inteos:</p>
                    {!! Form::select('inteosdb_new', array('1'=>'Subotica i Senta','2'=>'Kikinda'), $inteosdb, array('class' => 'form-control')); !!} 
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