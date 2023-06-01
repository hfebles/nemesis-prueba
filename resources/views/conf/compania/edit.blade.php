@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :backShow="$conf['back_show']" :group="$conf['group']" :delete="$conf['delete']" />
    
@endsection


@section('content')


{!! Form::model($user, ['method' => 'PATCH', 'route' => ['compania.update', $user->id]]) !!}
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Nombre de la compañia:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::text('name', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Nombre de la compañia','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Debe ingresar un nombre de la compañia
                        </div>
                    </div>
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">rut:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::text('rut', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Rut','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Rut
                        </div>
                    </div>
                </div>

                

                


            </div>
        </div>
    </div>
</div>  

<x-btns-save />
{!! Form::close() !!}

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea eliminar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>A seleccionado eliminar al usuario: {{$user->name}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['compania.destroy', $user->id],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Eliminar grupo</span>
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

<script>
  
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>
@endsection