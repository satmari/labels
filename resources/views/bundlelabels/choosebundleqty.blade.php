@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Choose bundle quantity?</div>
                
                {!! Form::open(['url' => 'bundle_qty']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                 <div class="panel-body">
                    <p>Bundle quantity:</p>
                    {!! Form::input('number', 'bundleqty', $bundleqty, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="panel-body">
                    <p>Labels per bundle:</p>
                    {!! Form::input('number', 'labels_per_bundle', $labels_per_bundle, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>
                
                <div class="panel-body">
                    {!! Form::submit('Confirm', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                
            </div>
        </div>
    </div>
</div>
@endsection