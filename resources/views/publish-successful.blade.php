@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Su artículo fue publicado con éxito</h1>
@stop

@section('content')
Puede visualizarlo en <a href="{{ $slug }}">{{ $slug }}</a>
Si desea editar





@stop

@section('css')
   
@stop

@section('js')
   
@stop