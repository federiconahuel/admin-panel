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
                    <b>Estado de publicación del artículo</b>: Publicado en <a href="{{ env('APP_URL') }}/novedades/{{ $slug }}">{{ env('APP_URL') }}/novedades/{{ $slug }}</a>
                </p>
                <p>
                    Última actualización de publicación: {{ $publication_last_update }}
                </p> 
            </div>
            <x-adminlte-button class="save-changes-btn" data-toggle="modal" data-target="#unpublishModal" label="Dar de baja publicación" theme="danger"/>
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
            <!--x-adminlte-button class="save-changes-btn" label="Guardar como borrador" theme="primary" onclick="save('{{ $articleID }}', false)"/-->
            <button type="button" class="btn btn-primary save-draft-btn" onclick="save('{{ $articleID }}', false)">Guardar como borrador</button>
            <button type="button" class="btn btn-success save-publish-btn" onclick="save('{{ $articleID }}', true)">Guardar y publicar</button>

            <!--x-adminlte-button  class="save-changes-btn" label="Guardar y publicar" theme="success" onclick="save('{{ $articleID }}', true)"/-->
        </div>
        <div class="loading-spinner-container">
            <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
        </div>
    </form> 
</div>

{{-- Minimal --}}
<x-adminlte-modal id="unpublishModal" static-backdrop title="Confirmar baja de publicación de artículo" theme="warning">
    <x-slot name="footerSlot">
        <x-adminlte-button type="submit" theme="success" label="Aceptar" onclick="unpublish('{{ $articleID }}')"/>
        <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal"/>
    </x-slot>
    
</x-adminlte-modal>

{{-- Custom --}}
<x-adminlte-modal id="errorModal" static-backdrop title="Error" theme="danger"
    icon="fa fa-exclamation" v-centered static-backdrop scrollable>
    <div style="height:auto;">Ha habido un error. Por favor, intente nuevamente</div>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-end">
            <x-adminlte-button class="mr-auto" theme="danger" label="Cerrar" data-dismiss="modal"/>
        </div>
        <!--x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/-->
    </x-slot>
</x-adminlte-modal>

{{-- Example button to open modal 
<x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#errorModal" class="bg-teal"/>
--}}

@stop

@section('css')
<style>
    .btn-container {
        display: flex;
        flex-direction: row;
        margin-top: 1rem;
        gap: 1rem;
        width: 100%;
        justify-content: flex-end; 
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

    .loading-spinner-container {
        display: none;
        justify-content: flex-end; 
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
            //document.querySelector('.save-draft-btn').disabled = true
            document.querySelector('[data-tiny-editor]').classList.add("disabled")
        }

        function enableEditor() {
            var form  = document.getElementById("articleForm");
            for (const element of form.elements) {
                element.disabled = false;
            }
            document.querySelector('.save-draft-btn').disabled = false
            document.querySelector('[data-tiny-editor]').classList.remove("disabled")
        }



        function disableUnpublishModal(){
            document.querySelector("#unpublishModal .close").disabled = true
            document.querySelector("#unpublishModal .btn-success").disabled = true
            document.querySelector("#unpublishModal .btn-danger").disabled = true
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
            document.querySelector('.btn-container').style.display = 'none'
            document.querySelector('.loading-spinner-container').style.display = 'flex'
            axios.post(url, {
                title: document.querySelector('.title-input').value,
                content: document.querySelector('[data-tiny-editor]').innerHTML,
            })
            .then(() => {
                window.location.replace('/admin-panel/articles/edit/' + id)
            })
            .catch(function (error) {
                $('#errorModal').modal('show')
                console.log(error);
                enableEditor();
                document.querySelector('.btn-container').style.display = 'flex'
                document.querySelector('.loading-spinner-container').style.display = 'none'
            });
        }
        
        function unpublish (id) {
            disableUnpublishModal()
            axios.post('/api/articles/unpublish/' + id , {}).then(()=> {
                window.location.replace('/admin-panel/articles/edit/' + id)
            })
            .catch(function (error) {
                $('#errorModal').modal('show')
                $('#unpublishModal').modal('hide')
                console.log(error);
                enableEditor();
            });
        }
    </script>
@stop