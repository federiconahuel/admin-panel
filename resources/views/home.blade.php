@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <h1>Accesos directos</h1>
@stop

@section('content')
    <div class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-2">
            <x-adminlte-card theme="primary" >
                <div class="d-flex justify-content-between">
                    <h5>
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Crear artículo
                    </h5>
                    <a href="{{  route('create-article')  }}">
                        Abrir editor de artículos
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>                        
                </div>
                </x-adminlte-card>
            </div>
            <div class="col-md-6 mb-2">
            <x-adminlte-card theme="primary" >
                <div class="d-flex justify-content-between">
                    <h5>
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Buscar artículo
                    </h5>
                    <a href="{{  route('search-articles')  }}">
                        Ir a buscador 
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
            </x-adminlte-card>
            </div>
        </div>
    </div>


@stop

@section('css')
@stop

@section('js')
@stop