@extends('layouts.admin')
@section('content')
    <div style="padding-bottom: 10px;">
        <a href="{{route('role.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> {{trans('role.btn.add')}}
        </a>
    </div>



    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>{{trans('role.col.name')}}</th>
                    <th>{{trans('role.col.permission')}}</th>
                    <th>{{trans('role.col.created_at')}}</th>
                    <th>{{trans('role.col.updated_at')}}</th>
                </tr>
                @foreach($data['listRole'] as $index => $item)
                    <tr>
                        <td>{{$index+1}}</td>
                        <td>
                            {{$item->name}}
                            <a href="{{route('role.edit', ['id' => $item->id])}}"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-center">
                            <a href="{{route('role.permission', ['id' => $item->id])}}"><i class="fa fa-group text-green"></i></a>
                        </td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection