@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Select Inbound Delivery</div>
                
                {!! Form::open(['url' => 'selectinbound_post']) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                <div class="panel-body">
                    {{-- $list_of_inbound --}}
                </div>

                @if (isset($printer_name))
                <div class="panel-body">
                    Selected Printer is: <b>{{$printer_name}}</b>
                </div>
                @else 
                <div class="panel-body">
                    <p style="color:red;"><b>Printer is not selected, STAMPAC nije izabran!!!</b></p>
                </div>
                @endif

                {{-- 
                @for ($i = 0; $i < 5; $i++)
                    Test
                @endfor
                
                @foreach ($list_of_inbound as $user)
                    <p>This is {{ $user->document }}</p>
                @endforeach
                
                <div class="panel-body">
                <p>list_of_inbound:</p>
                    {!! Form::select('document', $list_of_inbound, null,['class' => 'form-control']) !!}
                </div>
                --}}

                <p>Inbound Delivery:</p>
                <select name="document" class="form-control">
                    @foreach ($list_of_inbound as $inbound)
                    <option value="{{ $inbound->document }}"> {{ $inbound->document }} </option>
                    @endforeach
                </select>

                <div class="panel-body">
                    {!! Form::submit('Select', ['class' => 'btn btn-success btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}

                <div class="panel-body">
                    <div class="">
                            <a href="{{url('/deleteall')}}" class="btn btn-danger center-block">Delete all Inbound labels</a>
                    </div>
                </div>

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