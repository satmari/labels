@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Print pallet barcodes</div>
                
                {!! Form::open(['url' => 'printpallests_post']) !!}
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

                <div class="panel-body">
                <p><b>Izaberi sa liste od kojeg do kojeg broja palete hoces da stamapas</b></p>
                <p><i>Ukoliko nema novih paleta prvo treba da ih generises u Navisionu!!!</i></p>
                OD BROJA PALETE:
                
                <select name="od" class="form-control">
                    @foreach ($pallets as $pallet)
                    <option value="{{ $pallet->palnum }}"> {{ $pallet->palnum }} </option>
                    @endforeach
                </select>
                DO BROJA PALETE:
                <select name="do" class="form-control">
                    @foreach ($pallets as $pallet)
                    <option value="{{ $pallet->palnum }}"> {{ $pallet->palnum }} </option>
                    @endforeach
                </select>
                </div>

                <div class="panel-body">
                    {!! Form::submit('Select', ['class' => 'btn btn-success btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}

                {{-- 
                <div class="panel-body">
                    <div class="">
                            <a href="{{url('/deleteall')}}" class="btn btn-danger center-block">Delete all Inbound labels</a>
                    </div>
                </div>
                --}}

                <div class="panel-body">
                    <div class="">
                            <a href="{{url('/')}}" class="btn btn-default center-block">Back to Main menu</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection