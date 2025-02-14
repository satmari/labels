@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Insert required information for <b>PADPRINT</b> labels</div>
                
                {!! Form::open(['url' => 'padprint_print']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                {!!Form::hidden('marker', $marker) !!}
                {!!Form::hidden('cliche', $cliche) !!}
                {!!Form::hidden('color', $cliche_color) !!}
                {!!Form::hidden('size_relevant', $size_relevant) !!}
                
                {{-- 
                <div class="panel-body">
                    <p><b>CLICHE:</b></p>
                    {!! Form::input('text', 'cliche', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>
                <div class="panel-body">
                    <p><b>Color:</b></p>
                    {!! Form::input('text', 'color', null, ['class' => 'form-control']) !!}
                </div>
                --}}

                <div class="panel-body">
                    <p><b>Number of lebels</b></p>
                    {!! Form::input('number', 'labelqty', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="panel-body">
                    {!! Form::submit('Print BB', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                
            </div>
        </div>
    </div>
</div>
@endsection