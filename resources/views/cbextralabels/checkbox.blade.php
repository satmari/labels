@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Fill this questions</div>
                
                {!! Form::open(['url' => 'checkbox_store']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                <br>
                <br>
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
                
                <table class="table">
                    <tr>
                        <td><p class="vertical-align: middle;">Extra BB: </p></td>
                        <td>{!! Form::checkbox('extrabb', 1 , null, ['id' => 'check', 'class' => 'form-control']); !!}</td>
                    </tr>
                    <tr>
                        <td><p class="vertical-align: middle;">Ready BB  </p></td>
                        <td>{!! Form::checkbox('readybb', 1 ,null , ['id' => 'check', 'class' => 'form-control']); !!}</td>
                    </tr>
                 </table>
                    
                <br>
                <br>
                <br>

                <div class="panel-body">
                    <p>Number of Box:</p>
                    {!! Form::select('no_of_box', array(1=>1,2=>2,3=>3,4=>4,5=>5), null, array('class' => 'form-control')); !!} 
                </div>

                <div class="panel-body">
                    {!! Form::submit('Next', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                
            </div>
        </div>
    </div>
</div>
@endsection