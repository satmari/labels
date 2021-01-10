@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Print SAP SU label</div>
                

                {!! Form::open(['url' => 'take_sap_code_su']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

               
                @if (isset($printer_name))
                <div class="panel-body">
                    Selected Printer is: <b>{{$printer_name}}</b>
                </div>
                @else 
                <div class="panel-body">
                    <p style="color:red;"><b>Printer is not selected, STAMPAC nije izabran!!!</b></p>
                </div>
                @endif

                {!! Form::hidden('printer_name', $printer_name) !!}
                
                <div class="panel-body">
                    <p>Scan barcode:  <span style="color:red;"></span></p>
                        {!! Form::text('nav_hu', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>

               
                <div class="panel-body">
                    {!! Form::submit('Print', ['class' => 'btn btn-success btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}

               
                {{-- 
                <div class="panel-body">
                    <div class="">
                            <a href="{{url('/sap_hu')}}" class="btn btn-default center-block">Back to Main menu</a>
                    </div>
                </div>
                --}}
                
            </div>
        </div>
    </div>
</div>
@endsection