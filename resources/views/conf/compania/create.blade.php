@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')

{!! Form::open(array('route' => 'compania.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation')) !!}
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Nombre de la Compañia:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::text('name', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Nombre de la compañia','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Debe ingresar un nombre de la Compañia
                        </div>
                    </div>
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Correo electrónico:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::text('rut', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'RUT','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            rut
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>  


        <x-btns-save />



{!! Form::close() !!}

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