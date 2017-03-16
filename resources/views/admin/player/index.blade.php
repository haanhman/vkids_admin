@extends('layouts.admin')
@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('player.search.header')}}</h3>
        </div>
        {!! Form::open(['url' => route('player.index'), 'method' => 'GET']) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::text('q', isset($params['q']) ? $params['q'] : null, ['placeholder' => trans('player.search.txt_input'), 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label style="font-weight: normal">{!! Form::checkbox('block', 1, isset($params['block'])) !!} {{trans('player.search.block_status')}}</label>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group">
                {!! Form::submit(trans('player.search.btn_search'), ['class' => 'btn btn-default']) !!}
            </div>
            {{trans('player.search.result')}}: <strong>{{$listPlayer->total()}}</strong>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th>{{trans('player.col.id')}}</th>
                    <th>{{trans('player.col.nickname')}}</th>
                    <th>{{trans('player.col.age_range')}}</th>
                    <th>{{trans('player.col.info')}}</th>
                    <th>{{trans('player.col.devices')}}</th>
                    <th>{{trans('player.col.created_at')}}</th>
                    <th>{{trans('player.col.blocked_at')}}</th>
                </tr>
                @foreach($listPlayer as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->nickname}}</td>
                        <td>{{$item->age_range}}</td>
                        <td>
                            <ul class="list-unstyled">
                                <li>Coin: {{$item->coin}}</li>
                                <li>Diamond: {{$item->diamond}}</li>
                                <li>Score: {{$item->score}}</li>
                                <li>
                                    Social: {{isset($socialType[$item->social_type]) ? $socialType[$item->social_type] : ''}}
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ol style="padding-left: 10px;">
                                @foreach($item->devices as $device)
                                    <li>
                                        {{$device->name}}<br />
                                        {{$deviceName[$device->os]}} - {{$device->version}}
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a style="cursor: pointer; {{$item->trashed() ? 'color: red;' : ''}}"
                               dir="delete-form-{{$item->id}}" class="remove-item"><i
                                        class="fa {{$item->trashed() ? 'fa-lock' : 'fa-unlock'}}"></i></a>
                            {{$item->deleted_at}}

                            {!! Form::open(['method' => 'DELETE','id' => 'delete-form-' . $item->id,'url' => route('player.lock', ['id' => $item->id])]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </table>
            {{$listPlayer->links('vendor.pagination.default')}}
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('.remove-item').click(function () {
                if (confirm('{{trans('player.confirm')}}')) {
                    $('#' + $(this).attr('dir')).submit();
                }
            });
        });
    </script>
@endsection
