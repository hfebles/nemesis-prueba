@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns  :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        <x-cards>
        <form>
            <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="text-white bg-gray-900">
                    <tr>
                        <th width="40%" class="mb-0 text-uppercase">Empresa</th>
                    </tr>
                </thead>

                <tr>
                    
                    <td class="align-middle mb-0">
                        <select onchange="listaClientes(this.value)" class="form-select" name="opt" id="opt">
                            <option value="">Seleccione</option>
                            @foreach ($compania as $key => $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="align-middle mb-0">
                        <button class="btn btn-danger" type="button" onclick="location.reload()" name="reset" id="reset">Limpiar</button>
                    </td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>Provincia</td>
                    <td>Comuna</td>
                </tr>
                <tr>
                    <td>
                        <select disabled name="region" class="form-select" onchange="filtroRegion(this.value)" id="region">
                            <option value="">Seleccione</option>
                            @foreach ($regiones as $region)
                                <option value='{{ $region->id_estado }}'>{{ $region->estado }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select disabled name="provincia" class="form-select" onchange="filtroProvincia(this.value)" id="provincia">
                            <option value="">Seleccione</option>
                        </select>

                    </td>
                    <td>
                        <select disabled name="comuna" class="form-select" onchange="filtroComuna(this.value)" id="comuna">
                            <option value="">Seleccione</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Monto</td>
                </tr>
                <tr>
                    <td>Minimo</td>
                    <td>Maximo</td>
                </tr>
                <tr>
                    <td><input disabled class="form-control" onkeypress="validoNumeros(event)" min="0" max="9999999" type="number" name="min" id="min" /></td>
                    <td><input disabled class="form-control" onkeypress="validoNumeros(event)" min="0" max="9999999" type="number" name="max" id="max" /></td>
                    <td><button disabled id='btnConsulta' class="btn btn-success" onclick='consultaMontos();'>Ejecutar</button></td>
                </tr>
                
            </table>
        </form>
        </x-cards>

        <div class="col-12 mt-3">
            <div class="card" style="display:none;" id="devseto">
                <div class="card-body" id="body-tabla"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        var div = document.getElementById('devseto');
        var tabla = document.getElementById('body-tabla');

        // consultamos por montos
        function consultaMontos() {
            //Declaramos las variables
            var id_cliente = document.getElementById('opt');
            var id_region = document.getElementById('region');
            var id_provincia = document.getElementById('provincia');
            var id_comuna = document.getElementById('comuna');
            var min = document.getElementById('min');
            var max = document.getElementById('max');

            //verifico que se encuentre seleccionado una compania para ver sus clientes
            if (id_cliente.value == '') {
                //notifico
                alert('Debe seleccionar una compania')
                //le hago focus al select
                id_cliente.focus()
                //deshabilito el boton para que el usuario no siga consultado
                document.getElementById('btnConsulta').disabled = true
            } else {
                //en caso contrario dejo el boton habilitado
                document.getElementById('btnConsulta').disabled = false

                //verifico si los montos para ver que no se encuentren vacios
                if (min.value == '' || max.value == '') {
                    alert('Verifique los montos, no puede haber campos vacios')

                } else {
                    //verifico que el monto maximo no sea menor al minimo, en ese caso le notifico al usuario
                    if (min.value > max.value) {

                        //le notificamos al usuario para que lo arregle
                        alert('El monto maximo no debe ser inferior al minimo')
                        //dejo vacio el campo del maximo
                        max.value = ""
                        max.focus()
                    } else {

                        //continuamos enviando la peticion por ajax de los montos a verificar
                        const csrfToken = "{{ csrf_token() }}";

                        //creamos la peticion de ajax para realizar la consulta.
                        fetch('/sales/clients/datamontos', {
                            method: 'POST',
                            body: JSON.stringify({
                                id_cliente: id_cliente.value,
                                id_region: id_region.value,
                                id_provincia: id_provincia.value,
                                id_comuna: id_comuna.value,
                                min: min.value,
                                max: max.value,

                            }),
                            headers: {
                                'content-type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        }).then(response => {
                            return response.json();
                        }).then(data => {

                            //recibimos los valores
                            var linea = "";
                            linea += `<span>Conteo: ${data.length}</span>`
                            linea += `<table class="table table-bordered table-hover mb-0 text-uppercase overflow-scroll">
                                        <tr>
                                            <th class="bg-dark text-white">name_client</th>
                                            <th class="bg-dark text-white">idcard_client</th>
                                            <th class="bg-dark text-white">estado</th>
                                            <th class="bg-dark text-white">municipio</th>
                                            <th class="bg-dark text-white">ciudad</th>
                                            <th class="bg-dark text-white">monto</th>
                                            <th class="bg-dark text-white">id_tipo_doc</th>
                                            <th class="bg-dark text-white">id_contrato</th>
                                            <th class="bg-dark text-white">fec_emi</th>
                                            <th class="bg-dark text-white">fec_ven</th>
                                            <th class="bg-dark text-white">mora</th>
                                            <th class="bg-dark text-white">interes</th>
                                        </tr>`
                            //Recorremos el objeto con la respuesta
                            for (let d in data) {
                                linea += `<tr>
                                            <td>${data[d].name_client}</td>
                                            <td>${data[d].idcard_client}</td>
                                            <td>${data[d].estado}</td>
                                            <td>${data[d].municipio}</td>
                                            <td>${data[d].ciudad}</td>
                                            <td>${data[d].monto}</td>
                                            <td>${data[d].id_tipo_doc}</td>
                                            <td>${data[d].id_contrato}</td>
                                            <td>${data[d].fec_emi}</td>
                                            <td>${data[d].fec_ven}</td>
                                            <td>${data[d].mora}</td>
                                            <td>${data[d].interes}</td>
                                        </tr>`;
                            }
                            linea += `</table>`;
                            tabla.innerHTML = linea;
                        });
                    }
                }

            }
        }


        function filtroRegion(id_region) {
            //Declaramos las variables
            var id_cliente = document.getElementById('opt');
            var min = document.getElementById('min');
            var max = document.getElementById('max');
            const csrfToken = "{{ csrf_token() }}";
            //creamos la peticion de ajax para realizar la consulta.
            fetch('/sales/clients/dataregiones', {
                method: 'POST',
                body: JSON.stringify({
                    id_cliente: id_cliente.value,
                    id_region: id_region,
                    min: min.value,
                    max: max.value,
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                //recibimos los valores
                var linea = "";
                linea += `<span>Conteo: ${data.f.length}</span>`
                linea += `<table class="table table-bordered table-hover mb-0 text-uppercase overflow-scroll">
                            <tr>
                                <th class="bg-dark text-white">name_client</th>
                                <th class="bg-dark text-white">idcard_client</th>
                                <th class="bg-dark text-white">estado</th>
                                <th class="bg-dark text-white">municipio</th>
                                <th class="bg-dark text-white">ciudad</th>
                                <th class="bg-dark text-white">monto</th>
                                <th class="bg-dark text-white">id_tipo_doc</th>
                                <th class="bg-dark text-white">id_contrato</th>
                                <th class="bg-dark text-white">fec_emi</th>
                                <th class="bg-dark text-white">fec_ven</th>
                                <th class="bg-dark text-white">mora</th>
                                <th class="bg-dark text-white">interes</th>
                            </tr>`
                for (let d in data.f) {
                    linea += `<tr>
                                <td>${data.f[d].name_client}</td>
                                <td>${data.f[d].idcard_client}</td>
                                <td>${data.f[d].estado}</td>
                                <td>${data.f[d].municipio}</td>
                                <td>${data.f[d].ciudad}</td>
                                <td>${data.f[d].monto}</td>
                                <td>${data.f[d].id_tipo_doc}</td>
                                <td>${data.f[d].id_contrato}</td>
                                <td>${data.f[d].fec_emi}</td>
                                <td>${data.f[d].fec_ven}</td>
                                <td>${data.f[d].mora}</td>
                                <td>${data.f[d].interes}</td>
                            </tr>`;
                }
                linea += `</table>`;
                //cargo la tabla con datos
                tabla.innerHTML = linea;

                //cargo el combo de las provincias segun la region
                var comboProvincia = document.getElementById('provincia');
                var lineaProvincias = "";

                lineaProvincias += `<option value="">Seleccione</option>`;
                for (let c in data.r) {
                    lineaProvincias += `<option value="${data.r[c].id_municipio}">${data.r[c].municipio}</option>`;
                }
                comboProvincia.innerHTML = lineaProvincias;

                //habilito el boton de consulta de montos
                if (document.getElementById('btnConsulta').disabled) {
                    document.getElementById('btnConsulta').disabled = false
                }

                //habilito el select de provincias
                if (document.getElementById('provincia').disabled) {
                    document.getElementById('provincia').disabled = false
                }
            });
        }

        

        function filtroProvincia(id_provincia) {
            //Declaro variables
            var id_cliente = document.getElementById('opt').value;
            var id_region = document.getElementById('region').value;
            var min = document.getElementById('min');
            var max = document.getElementById('max');

            const csrfToken = "{{ csrf_token() }}";
            fetch('/sales/clients/dataprovincia', {
                method: 'POST',
                body: JSON.stringify({
                    id_cliente: id_cliente,
                    id_region: id_region,
                    id_provincia: id_provincia,
                    min: min.value,
                    max: max.value,
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                var linea = "";
                linea += `<span>Conteo: ${data.f.length}</span>`
                linea += `<table class="table table-bordered table-hover mb-0 text-uppercase overflow-scroll">
                            <tr>
                                <th class="bg-dark text-white">name_client</th>
                                <th class="bg-dark text-white">idcard_client</th>
                                <th class="bg-dark text-white">estado</th>
                                <th class="bg-dark text-white">municipio</th>
                                <th class="bg-dark text-white">ciudad</th>
                                <th class="bg-dark text-white">monto</th>
                                <th class="bg-dark text-white">id_tipo_doc</th>
                                <th class="bg-dark text-white">id_contrato</th>
                                <th class="bg-dark text-white">fec_emi</th>
                                <th class="bg-dark text-white">fec_ven</th>
                                <th class="bg-dark text-white">mora</th>
                                <th class="bg-dark text-white">interes</th>
                            </tr>`
                for (let d in data.f) {
                    linea += `<tr>
                                <td>${data.f[d].name_client}</td>
                                <td>${data.f[d].idcard_client}</td>
                                <td>${data.f[d].estado}</td>
                                <td>${data.f[d].municipio}</td>
                                <td>${data.f[d].ciudad}</td>
                                <td>${data.f[d].monto}</td>
                                <td>${data.f[d].id_tipo_doc}</td>
                                <td>${data.f[d].id_contrato}</td>
                                <td>${data.f[d].fec_emi}</td>
                                <td>${data.f[d].fec_ven}</td>
                                <td>${data.f[d].mora}</td>
                                <td>${data.f[d].interes}</td>
                            </tr>`;
                }
                linea += `</table>`;
                //cargo la tabla con datos
                tabla.innerHTML = linea;
                //cargo el combo de las comunas segun la provincia
                var comboComuna = document.getElementById('comuna');
                var lineaComuna = "";

                lineaComuna += `<option value="">Seleccione</option>`;
                for (let c in data.p) {
                    lineaComuna += `<option value="${data.p[c].id_ciudad}">${data.p[c].ciudad}</option>`;
                }
                comboComuna.innerHTML = lineaComuna;

                if (document.getElementById('comuna').disabled) {
                    document.getElementById('comuna').disabled = false
                }
                
            });

        }

        function filtroComuna(id_comuna) {

            var id_cliente = document.getElementById('opt').value;
            var id_region = document.getElementById('region').value;
            var id_provincia = document.getElementById('provincia').value;
            var min = document.getElementById('min');
            var max = document.getElementById('max');

            const csrfToken = "{{ csrf_token() }}";
            fetch('/sales/clients/datacomuna', {
                method: 'POST',
                body: JSON.stringify({
                    id_cliente: id_cliente,
                    id_region: id_region,
                    id_provincia: id_provincia,
                    id_comuna: id_comuna,
                    min: min.value,
                    max: max.value,
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                var linea = "";
                linea += `<span>Conteo: ${data.length}</span>`
                linea += `<table class="table table-bordered table-hover mb-0 text-uppercase overflow-scroll">
                            <tr>
                                <th class="bg-dark text-white">name_client</th>
                                <th class="bg-dark text-white">idcard_client</th>
                                <th class="bg-dark text-white">estado</th>
                                <th class="bg-dark text-white">municipio</th>
                                <th class="bg-dark text-white">ciudad</th>
                                <th class="bg-dark text-white">monto</th>
                                <th class="bg-dark text-white">id_tipo_doc</th>
                                <th class="bg-dark text-white">id_contrato</th>
                                <th class="bg-dark text-white">fec_emi</th>
                                <th class="bg-dark text-white">fec_ven</th>
                                <th class="bg-dark text-white">mora</th>
                                <th class="bg-dark text-white">interes</th>
                            </tr>`
                for (let d in data) {
                    linea += `<tr>
                                <td>${data[d].name_client}</td>
                                <td>${data[d].idcard_client}</td>
                                <td>${data[d].estado}</td>
                                <td>${data[d].municipio}</td>
                                <td>${data[d].ciudad}</td>
                                <td>${data[d].monto}</td>
                                <td>${data[d].id_tipo_doc}</td>
                                <td>${data[d].id_contrato}</td>
                                <td>${data[d].fec_emi}</td>
                                <td>${data[d].fec_ven}</td>
                                <td>${data[d].mora}</td>
                                <td>${data[d].interes}</td>
                            </tr>`;
                }
                linea += `</table>`;
                //cargo la tabla con datos
                tabla.innerHTML = linea;
            });
        }

        function listaClientes(id_cliente) {
            const csrfToken = "{{ csrf_token() }}";
            fetch('/sales/clients/dataempresa', {
                method: 'POST',
                body: JSON.stringify({
                    id_cliente: id_cliente,
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                div.style.display = 'block'
                console.log(data.e)
                var linea = "";
                linea += `<span>Conteo: ${data.length}</span>`
                linea += `<table class="table table-bordered table-hover mb-0 text-uppercase overflow-scroll">
                            <tr>
                                <th class="bg-dark text-white">name_client</th>
                                <th class="bg-dark text-white">idcard_client</th>
                                <th class="bg-dark text-white">estado</th>
                                <th class="bg-dark text-white">municipio</th>
                                <th class="bg-dark text-white">ciudad</th>
                                <th class="bg-dark text-white">monto</th>
                                <th class="bg-dark text-white">id_tipo_doc</th>
                                <th class="bg-dark text-white">id_contrato</th>
                                <th class="bg-dark text-white">fec_emi</th>
                                <th class="bg-dark text-white">fec_ven</th>
                                <th class="bg-dark text-white">mora</th>
                                <th class="bg-dark text-white">interes</th>
                            </tr>`
                for (let d in data) {
                    linea += `<tr>
                                <td>${data[d].name_client}</td>
                                <td>${data[d].idcard_client}</td>
                                <td>${data[d].estado}</td>
                                <td>${data[d].municipio}</td>
                                <td>${data[d].ciudad}</td>
                                <td>${data[d].monto}</td>
                                <td>${data[d].id_tipo_doc}</td>
                                <td>${data[d].id_contrato}</td>
                                <td>${data[d].fec_emi}</td>
                                <td>${data[d].fec_ven}</td>
                                <td>${data[d].mora}</td>
                                <td>${data[d].interes}</td>
                            </tr>`;
                }
                linea += `</table>`;
                //cargo la tabla con datos
                tabla.innerHTML = linea;
                //console.log(linea);

                //habilito la de consulta de montos, y la region
                if (document.getElementById('btnConsulta').disabled) {
                    document.getElementById('btnConsulta').disabled = false
                    document.getElementById('min').disabled = false
                    document.getElementById('max').disabled = false
                    document.getElementById('region').disabled = false
                }
            });

        }

        function validoNumeros(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toLowerCase();
            letras = "0123456789";
            especiales = [];
        
            tecla_especial = false
            for(var i in especiales) {
                if(key == especiales[i]) {
                    tecla_especial = true;
                    break;
                }
            }
  
            if(letras.indexOf(tecla) == -1 && !tecla_especial)
                return false;       
  }
    </script>

@endsection
