@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Select Batch to print</div>
                
                {!! Form::open(['url' => 'selectbatch_post/'.$document]) !!}
                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">

                {{-- {!!Form::hidden('inbound', $document) !!} --}}

                <p>Batch:</p>
                <select name="batch" class="form-control">
                    @foreach ($batch as $b)
                    <option value="{{ $b->batch }}"> Batch: {{ $b->batch }}  ; Labels: {{ $b->numlines }} </option>
                    @endforeach
                </select>

                <div class="panel-body">
                    {!! Form::submit('Print', ['class' => 'btn btn-success btn-lg center-block']) !!}
                </div>

                @include('errors.list')

                {!! Form::close() !!}
                <div class="panel-body">
                    <div class="">
                            <a href="{{url('/deleteinbound/'.$document)}}" class="btn btn-danger center-block">Delete this Inbound labels</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection