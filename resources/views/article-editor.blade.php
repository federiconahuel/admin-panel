@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{ $header }}</h1>
@stop

@section('content')

<div>
    <x-adminlte-callout class="d-flex justify-content-between">
        @if ($isPublished) 
            <div>
                <p>
                    <b>Estado de publicación del artículo</b>: Publicado en <a href="#">url.com/blog/{{ $slug }}</a>
                </p>
                <p>
                    Última actualización de publicación: {{ $publication_last_update }}
                </p> 
            </div>
            <x-adminlte-button class="save-changes-btn" data-toggle="modal" data-target="#modalMin" label="Dar de baja publicación" theme="danger"/>
        @else
            <div>
                <b>Estado de publicación del artículo</b>:  No publicado
            </div>
        @endif
    </x-adminlte-callout>
    
    <form id="articleForm" class="py-3">
        <div class="row">   
        @if ($isPublished) 
            <div class="w-100" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Para cambiar el título, antes debe dar de baja la publicación" data-placement="top">
                <x-adminlte-input class="title-input" style="pointer-events: none;" name="title" label="Título del artículo" placeholder="Ingrese título" fgroup-class="col-12" disabled disable-feedback value="{{ $title }}"/>
            </div>
        @else  
            <x-adminlte-input class="title-input" name="title" label="Título del artículo" placeholder="Ingrese título" fgroup-class="col-12" disable-feedback value="{{ $title }}"/>
        @endif
        </div>
        <input type="hidden" name="content" value=""/>
        <b>Contenido del artículo</b>
        <div class="editor-container">
            <div data-tiny-editor data-formatblock="no" data-fontname="no">
                {!! $content !!} 
            </div>
            @if ($draft_last_update) 
                <div class="pt-4">
                    Última modificación del borrador: {{ $draft_last_update }}
                </div>
            @endif
        </div>
        <div class="btn-container">
            <x-adminlte-button class="save-changes-btn" label="Guardar como borrador" theme="primary" onclick="save('{{ $articleID }}', false)"/>
            <x-adminlte-button  class="save-changes-btn" label="Guardar y publicar" theme="success" onclick="save('{{ $articleID }}', true)"/>
        </div>
    </form> 
</div>

{{-- Minimal --}}
<x-adminlte-modal id="modalMin" static-backdrop title="Confirmar baja de publicación de artículo">
    <x-slot name="footerSlot">
        <x-adminlte-button type="submit" theme="success" label="Aceptar" onclick="unpublish('{{ $articleID }}')"/>
        <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

@stop

@section('css')
<style>
    .btn-container {
        display: flex;
        flex-direction: row;
        margin-top: 1rem;
        gap: 1rem;
        width: 100%;
        justify-content:flex-end; 
    }

    .__editor {
        height: 40vh !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.4;
    }

</style>
@stop

@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/tiny-editor/dist/bundle.js"></script>
    <script>

        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        $('.popover-dismiss').popover({
            trigger: 'focus'
        })

        document.forms.articleForm.content.value = document.querySelector('[data-tiny-editor]').innerHTML;
        document.querySelector('[data-tiny-editor]').addEventListener('keypress', e => {
            document.forms.articleForm.content.value = document.querySelector('[data-tiny-editor]').innerHTML;
        });

        function disableEditor() {
            var form  = document.getElementById("articleForm");
            for (const element of form.elements) {
                element.disabled = true;
            }
            document.querySelector('.save-changes-btn').disabled = true
            document.querySelector('[data-tiny-editor]').classList.add("disabled")
        }

        function disableUnpublishModal(){
            document.querySelector("#modalMin .close").disabled = true
            document.querySelector("#modalMin .btn-success").disabled = true
            document.querySelector("#modalMin .btn-danger").disabled = true
        }

        function save(id, publish) {
            disableEditor()
            url = '/api/articles/save/' + id
            if(publish) {
                url = url + '?publish=true'
            }
            else{
                url = url + '?publish=false'
            }
            axios.post(url, {
                title: document.querySelector('.title-input').value,
                content: document.querySelector('[data-tiny-editor]').innerHTML,
            })
            .then(() => {
                window.location.replace('/admin-panel/articles/edit/' + id)
            })
            .catch(function (error) {
                console.log(error);
            });
        }
        
        function unpublish (id) {
            disableUnpublishModal()
            axios.post('/api/articles/unpublish/' + id , {}).then(()=> {
                window.location.replace('/admin-panel/articles/edit/' + id)
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    </script>
@stop