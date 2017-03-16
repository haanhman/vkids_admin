@extends('layouts.admin')
@section('content')
    <div style="padding-bottom: 10px;">
        <a href="{{route('users.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> {{trans('users.btn.add')}}
        </a>
    </div>

    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>{{trans('users.col.name')}}</th>
                    <th>{{trans('users.col.email')}}</th>
                    <th>{{trans('users.col.Role')}}</th>
                    <th>{{trans('users.col.created_at')}}</th>
                    <th>{{trans('users.col.updated_at')}}</th>
                    <th>{{trans('users.col.deleted_at')}}</th>
                </tr>
                @if(!empty($listUser))
                    @foreach($listUser as $index => $item)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>
                                {{$item->name}}
                                <a href="{{route('users.edit', ['id' => $item->id])}}"><i class="fa fa-edit"></i></a>
                            </td>
                            <td>{{$item->email}}</td>
                            <td>
                                @if($item->system_admin == 1)
                                    Full access
                                @else
                                    @if(!empty($item->roles))
                                        <ul class="list-unstyled">
                                            @foreach($item->roles as $role)
                                                <li>{{isset($listRole[$role->role_id]) ? $listRole[$role->role_id] : ''}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endif
                            </td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td>
                                <a style="cursor: pointer; {{!$item->trashed() ? 'color: red;' : ''}}"
                                   dir="delete-form-{{$item->id}}" class="remove-item"><i
                                            class="fa {{$item->trashed() ? 'fa-refresh' : 'fa-trash'}}"></i></a>
                                {{$item->deleted_at}}

                                {!! Form::open(['method' => 'DELETE','id' => 'delete-form-' . $item->id,'url' => route('users.destroy', ['id' => $item->id])]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('.remove-item').click(function () {
                if (confirm('{{trans('users.confirm')}}')) {
                    $('#' + $(this).attr('dir')).submit();
                }
            });
        });
    </script>
@endsection