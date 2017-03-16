@extends('layouts.admin')
@section('content')
    <div class="box box-primary">
        {!! Form::model($role, ['url' => route('role.set_permission', ['id' => $role->id]), 'method' => 'POST']) !!}


        <div class="box-body">

            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">Check</th>
                    <th>Domain</th>
                    <th>Method</th>
                    <th>URI</th>
                    <th>Name</th>
                    <th>Action</th>
                    <th>Middleware</th>
                </tr>
                @if(!empty($routes))
                    @foreach($routes as $key => $item)
                        <tr>
                            <td class="icheck text-center">
                                {!! Form::checkbox('route[]', $key, in_array($key, $currentPermission)) !!}

                            </td>
                            <td>{{$item['host']}}</td>
                            <td>{{$item['method']}}</td>
                            <td>{{$item['uri']}}</td>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['action']}}</td>
                            <td>{{$item['middleware']}}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>

        <div class="box-footer">
            {!! Form::submit(trans('role.btn.submit'), ['class' => 'btn btn-primary']) !!}
            <a href="{{route('role.index')}}" class="btn btn-default">
                <i class="fa fa-angle-left"></i> {{trans('role.btn.cancel')}}
            </a>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(function () {
            $('.icheck input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection