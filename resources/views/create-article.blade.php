@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Crear artículo</h1>
@stop

@section('content')
<div class="row">
    <x-adminlte-input class="title-input"  name="iLabel" label="Título del artículo" placeholder="Ingrese título" fgroup-class="col-12" disable-feedback onchange="inputHasChanged()"/>
</div>

<b>Contenido del artículo</b>
<div class="editor-container">
    <div data-tiny-editor id="editor" data-formatblock="no" data-fontname="no"></div>
</div>
<div class="btn-container">
    <x-adminlte-button class="save-changes-btn" label="Guardar cambios" theme="primary" onclick="saveChanges()"/>
    <x-adminlte-button class="publish-btn" label="Publicar artículo" theme="success"/>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/create-article.css') }}">
@stop

@section('js')
    <script src="https://unpkg.com/tiny-editor/dist/bundle.js"></script>
    <script src="{{ asset('js/create-article.js') }}"></script>
@stop