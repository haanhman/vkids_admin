@extends('layouts.admin')
@section('content')
    <div class="box box-primary">

        {!! Form::open(['url' => route('json.create')]) !!}

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('letter', 'Letter', ['class' => 'control-label']) !!}
                {!! Form::text('letter', null, ['class' => 'form-control', 'required' => true]) !!}
            </div>

                <?php
                    for($i = 0; $i < 4; $i++) {
                        ?>
            <div class="form-group form-inline">
                    {!! Form::label('cards['.$i.']', 'Cards', ['class' => 'control-label']) !!}
                    {!! Form::text('cards['.$i.']', null, ['class' => 'form-control', 'required' => true]) !!}

                    {!! Form::label('amount['.$i.']', 'Amount', ['class' => 'control-label']) !!}
                    {!! Form::text('amount['.$i.']', null, ['class' => 'form-control', 'required' => true]) !!}

                    {!! Form::label('sound['.$i.']', 'Sound', ['class' => 'control-label']) !!}
                    {!! Form::checkbox('sound['.$i.']', 1, null) !!}
            </div>
                        <?php
                    }
                ?>

        </div>

        <div class="box-footer">
            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>

@endsection