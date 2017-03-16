@extends('layouts.admin')
@section('content')
    Hello <strong>{{Auth::user()->name}}</strong>,
@endsection