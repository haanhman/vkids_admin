@extends('layouts.admin')
@section('content')
    <div class="box box-primary">

        @if(isset($role))
            {!! Form::model($role, ['url' => route('role.update', ['id' => $role->id]), 'method' => 'PUT']) !!}
        @else
            {!! Form::open(['url' => route('role.store')]) !!}
        @endif


        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', trans('role.form.name'), ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => true]) !!}
            </div>
        </div>

        <div class="box-footer">
            {!! Form::submit(isset($user) ? trans('role.btn.update') : trans('role.btn.submit'), ['class' => 'btn btn-primary']) !!}
            <a href="{{route('role.index')}}" class="btn btn-default">
                <i class="fa fa-angle-left"></i> {{trans('role.btn.cancel')}}
            </a>
        </div>
        {!! Form::close() !!}
    </div>

@endsection