@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Buscar artículos</h1>
@stop

@section('content')
    <div class="mt-5"> 
        @foreach($articles as $article)
                <div class="card mt-2">
                    <div class="card-body">
                        <p><b>Título</b>: <i> {{$article->title}} </i></p>
                        <p><b>Fecha de creación </b>: {{$article->created_at}}</p>
                        <p><b>Última modificación en la versión borrador</b>: {{$article->draft_last_update}}</p>                        
                        <p><b>Estado de publicación</b>: 
                            @if ($article->is_published) 
                                Publicado en <a href="#">url.com/blog/{{ $article->slug }}</a>
                            @else
                                No publicado
                            @endif
                        </p>
                        @if ($article->is_published)
                            <p><b>Última modificación en la versión publicada</b>: {{$article->publication_last_update}}</p>
                        @endif
                        <div class="d-flex justify-content-end">
                        <a type="button" class="btn btn-primary" href="/admin-panel/articles/edit/{{$article->id}}">Editar artículo</a>
                        <!--x-adminlte-button class="" label="" theme="danger"/-->
                        </div>
                    </div>
                </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {!! $articles->links() !!}
    </div>
@stop

@section('css')
    
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop