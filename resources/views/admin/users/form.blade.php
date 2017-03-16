@extends('layouts.admin')
@section('content')
    <div class="box box-primary">

        @if(isset($user))
            {!! Form::model($user, ['url' => route('users.update', ['id' => $user->id]), 'method' => 'PUT']) !!}
        @else
            {!! Form::open(['url' => route('users.store')]) !!}
        @endif


        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', trans('users.form.name'), ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => true]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email', trans('users.form.email'), ['class' => 'control-label']) !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'required' => true]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('lang', trans('users.form.language'), ['class' => 'control-label']) !!}
                {!! Form::select('lang', config('app.languages') ,null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                <label class="control-label">{{trans('users.form.role')}}</label><br/>
                <label>{!! Form::checkbox('role[]', $fullAccess, isset($user) && $user->system_admin == 1, ['id' => 'full-access']) !!}
                    Full access</label><br/>
                @foreach($role as $item)
                    <label>{!! Form::checkbox('role[]', $item->id, isset($userRole) && in_array($item->id, $userRole), ['class' => 'role-option']) !!} {{$item->name}}</label>
                    <br/>
                @endforeach
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('password', trans('users.form.password'), ['class' => 'control-label']) !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('re_password', trans('users.form.re-password'), ['class' => 'control-label']) !!}
                        {!! Form::password('re_password', ['class' => 'form-control']) !!}
                    </div>
                </div>
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
    <script type="text/javascript">
        $(function () {
            $('.role-option').attr('disabled', $('#full-access').is(':checked'));
            $('#full-access').click(function () {
                $('.role-option').attr('disabled', $(this).is(':checked'));
            });
        });
    </script>
@endsection