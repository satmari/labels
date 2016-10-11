@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Fill this questions</div>
                
                {!! Form::open(['url' => 'typeqty_store']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                <br>
                
                {!!Form::hidden('po', $po) !!}
                {!!Form::hidden('bb_3', $bb_3) !!}
                {!!Form::hidden('bagno', $bagno) !!}
                {!!Form::hidden('style', $style) !!}
                {!!Form::hidden('color', $color) !!}
                {!!Form::hidden('color_desc', $color_desc) !!}
                {!!Form::hidden('size_ita', $size_ita) !!}
                {!!Form::hidden('size_eng', $size_eng) !!}
                {!!Form::hidden('size_spa', $size_spa) !!}
                {!!Form::hidden('size_eur', $size_eur) !!}
                {!!Form::hidden('size_usa', $size_usa) !!}
                {!!Form::hidden('bb_qty', $bb_qty) !!}
                {!!Form::hidden('printer_name', $printer_name) !!}
                {!!Form::hidden('no_of_box', $no_of_box) !!}
                {!!Form::hidden('extrabb', $extrabb) !!}
                {!!Form::hidden('readybb', $readybb) !!}
                

                @for ($i = 1; $i <= $no_of_box; $i++)
                    <div class="panel-body">
                        <p>Box qty {{$i}}:</p>
                        {!! Form::input('number', 'boxqty'.$i, $sugested_qty, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>
                @endfor

                <div class="panel-body">
                    {!! Form::submit('Print', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                
            </div>
        </div>
    </div>
</div>
@endsection