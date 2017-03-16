@extends('layouts.admin')
@section('content')
    <div class="box box-primary">

        {!! Form::model(Auth::user(), ['url' => route('users.store_profile.ignore'), 'method' => 'PUT']) !!}


        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', trans('users.form.name'), ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => true]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('lang', trans('users.form.language'), ['class' => 'control-label']) !!}
                {!! Form::select('lang', config('app.languages') ,null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('old_password', trans('users.form.old_password'), ['class' => 'control-label']) !!}
                {!! Form::password('old_password', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password', trans('users.form.password'), ['class' => 'control-label']) !!}
                {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('re_password', trans('users.form.re-password'), ['class' => 'control-label']) !!}
                {!! Form::password('re_password', ['class' => 'form-control']) !!}
            </div>

        </div>
        <div class="box-footer">
            {!! Form::submit(isset($user) ? trans('users.btn.update') : trans('users.btn.submit'), ['class' => 'btn btn-primary']) !!}
            <a href="{{route('users.index')}}" class="btn btn-default">
                <i class="fa fa-angle-left"></i> {{trans('users.btn.cancel')}}
            </a>
        </div>
        {!! Form::close() !!}
    </div>
@endsection