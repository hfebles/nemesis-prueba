<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Conf\Compania;
use App\Models\Conf\Country\Ciudades;
use Illuminate\Http\Request;

use App\Models\Sales\Client;
use App\Models\Conf\Country\Estados;
use App\Models\Conf\Country\Municipios;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class ClientController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sales-clients-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|sales-clients-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|sales-clients-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|sales-clients-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        
        $conf = [
            'title-section' => 'Gestion de clientes',
            'group' => 'sales-clients',
            'create' => ['route' => 'clients.create', 'name' => 'Nuevo cliente'],
            'url' => '/sales/clients/create'
        ];

        $data = Client::select('co.name', 'clients.*')
            ->join('companias as co', 'co.id', '=', 'clients.id_company')
            ->where('enabled_client', '=', '1')
            ->paginate(10);

        $compania = Compania::all();
        $regiones = Estados::all();

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'DNI / RIF', 'Nombre o Razón social', 'Telefono'],
            'w_ts' => ['3', '10', '50', '10',],
            'c_ths' =>
            [
                'text-center align-middle p-1',
                'text-center align-middle p-1',
                'align-middle p-1',
                'text-center align-middle p-1',
            ],

            'tds' => ['idcard_client', 'name_client', 'name'],
            'td_number' => [false, false, false],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/sales/clients",
            'id' => 'id_client',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('sales.clients.index', compact('conf', 'table', 'compania', 'regiones'));
    }


    public function dataEmpresa(Request $request)
    {

        $data = $request;
        //Consulta para obtener los datos de la compania a la cual le queremos consultar los clientes


        $empresa = Client::select('data.*',  'clients.*', 'e.estado', 'm.municipio', 'c.ciudad')
        ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
        ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
        ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
        ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
        ->whereIdCompany($data->id_cliente)
        ->get();

        //retorno una coleccion de bases de datos con los datos requeridos. 
        return $empresa;
    }

    public function filtroRegiones(Request $request)
    {
        //filtro para las regiones.
        //consultamos si existe el valor del monto minimo
        if ($request->min == null) { // si es null, no tomo en cuenta para el filtro los datos minimo
            $filtroR = Client::select('e.estado', 'clients.*', 'data.*', 'm.municipio', 'c.ciudad')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)->get();
        } else {
            //si tiene un valor en el campo min proceso a tomar el valor y a realizar la segracion segun minimo y maximo introducido
            $filtroR = Client::select('data.*',  'e.estado', 'clients.*', 'm.municipio', 'c.ciudad')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)
                ->whereBetween('data.monto', [$request->min, $request->max])
                ->get();
        }

        //preparo los datos que voy a recibir en el como de provincia segun la region seleccionada
        $provinciasRegiones = Municipios::whereIdEstado($request->id_region)->get();

        // retorno el un arreglo con las 2 colecciones de objetos
        // F para filtro, R para provincias segun las regiones
        return ['f' => $filtroR, 'r' => $provinciasRegiones];
    }

    public function filtroProvincia(Request $request)
    {
        //filtro para las provincias.
        //consultamos si existe el valor del monto minimo
        if ($request->min == null) { // si es null, no tomo en cuenta para el filtro los datos minimo
            $filtroP = Client::select('e.estado', 'data.*', 'm.municipio', 'clients.*', 'c.ciudad')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->whereRegion($request->id_provincia)
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)
                ->get();
        } else {
            //si tiene un valor en el campo min proceso a tomar el valor y a realizar la segracion segun minimo y maximo introducido
            $filtroP = Client::select('data.*', 'e.estado', 'm.municipio', 'clients.*', 'c.ciudad')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->whereRegion($request->id_provincia)
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)
                ->whereBetween('data.monto', [$request->min, $request->max])
                ->get();
        }

        //preparo los datos que voy a recibir en el como de comunas segun la provincia seleccionada
        $comunasProvincias = Ciudades::whereIdMunicipio($request->id_provincia)->get();
        
        // retorno el un arreglo con las 2 colecciones de objetos
        // F para filtro, p para comunas segun las provincias
        return ['f' => $filtroP, 'p' => $comunasProvincias];
    }

    public function filtroComuna(Request $request)
    {

        //filtro para las provincias.
        //consultamos si existe el valor del monto minimo
        if ($request->min == null) { // si es null, no tomo en cuenta para el filtro los datos minimo
            $filtroC = Client::select('e.estado', 'm.municipio', 'c.ciudad', 'clients.*', 'data.*')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->whereComuna($request->id_comuna)
                ->whereRegion($request->id_provincia)
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)
                ->get();
        } else {
            //si tiene un valor en el campo min proceso a tomar el valor y a realizar la segracion segun minimo y maximo introducido
            $filtroC = Client::select('data.*', 'e.estado', 'm.municipio', 'c.ciudad', 'clients.*')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->whereComuna($request->id_comuna)
                ->whereRegion($request->id_provincia)
                ->whereIdState($request->id_region)
                ->whereIdCompany($request->id_cliente)
                ->whereBetween('data.monto', [$request->min, $request->max])
                ->get();
        }

        //retorno una coleccion con el objeto resultado
        return $filtroC;
    }

    public function filtroMontos(Request $request)
    {

        /**
         * Filtro para los montos
         * 
         * Al ser conjugable con los filtros anteriores vamos a encontrar distintos tipos de verificaciones
         * 
         * 1. Regiones 
         *  1.1 Si en la $request el valor de id_region viene nulo, solo filtro por Compania y montos.
         *  1.2 en caso contario filtro con el valor de la region
         * 
         * 2. Provincias
         *  2.1 Si la $request el valor de id_provincia viene nulo, solo filtro por compania y montos
         *  2.2 en caso contario filtro con los datos anteriores y el valor del id_provincia
         * 
         * 3. Comuna
         *  3.1 Si la $request el valor de id_comuna viene nulo, solo filtro por compania y montos
         *  3.2 en caso contario filtro con los datos anteriores y el valor del id_comuna
         * 
         */
        if ($request->id_region == null) { // 1
            // 1.1
            $filtroMontos = Client::select('data.*',  'clients.*', 'e.estado', 'm.municipio', 'c.ciudad')
                ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                ->whereIdCompany($request->id_cliente)
                ->whereBetween('data.monto', [$request->min, $request->max])
                ->get();
        } else {
            // 1.2
            if ($request->id_provincia == null) {// 2
                // 2.1
                $filtroMontos = Client::select('data.*', 'e.estado', 'm.municipio', 'c.ciudad', 'clients.*')
                    ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                    ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                    ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                    ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                    ->whereIdCompany($request->id_cliente)
                    ->whereIdState($request->id_region)
                    ->whereBetween('data.monto', [$request->min, $request->max])
                    ->get();
            } else {
                // 2.2
                if ($request->id_comuna == null) { // 3
                    // 3.1
                    $filtroMontos = Client::select('data.*', 'm.municipio', 'e.estado', 'clients.*', 'c.ciudad')
                    ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                    ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                    ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                    ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                    ->whereIdCompany($request->id_cliente)
                    ->whereRegion($request->id_provincia)
                    ->whereIdState($request->id_region)
                    ->whereBetween('data.monto', [$request->min, $request->max])
                    ->get();
                }else{
                    // 3.2
                    $filtroMontos = Client::select('data.*', 'e.estado', 'm.municipio', 'c.ciudad', 'clients.*')
                    ->join('estados as e', 'clients.id_state', '=', 'e.id_estado')
                    ->join('municipios as m', 'clients.region', '=', 'm.id_municipio')
                    ->join('ciudades as c', 'clients.comuna', '=', 'c.id_ciudad')
                    ->join('datos as data', 'clients.id_client', '=', 'data.id_cliente')
                    ->whereComuna($request->id_comuna)
                    ->whereRegion($request->id_provincia)
                    ->whereIdState($request->id_region)
                    ->whereIdCompany($request->id_cliente)
                    ->whereBetween('data.monto', [$request->min, $request->max])
                    ->get();
                }
                
            }
        }

        // Retorno la colleccion de objetos
        return $filtroMontos;
    }



    public function create()
    {
        $conf = [
            'title-section' => 'Crear un nuevo cliente',
            'group' => 'sales-clients',
            'back' => 'clients.index',
            'url' => '/sales/clients'
        ];

        $estados = Estados::pluck('estado', 'id_estado');
        return view('sales.clients.create', compact('conf', 'estados'));
    }

    public function store(Request $request)
    {

        $data = $request->except('_token');

        $save = new Client();
        $save->name_client = strtoupper($data['name_client']);
        $save->idcard_client = strtoupper($data['letra']) . $data['idcard_client'];
        $save->address_client = strtoupper($data['address_client']);
        $save->id_state = $data['id_state'];

        if (isset($data['phone_client'])) {
            $save->phone_client = $data['phone_client'];
        }
        if (isset($data['email_client'])) {
            $save->email_client = strtoupper($data['email_client']);
        }
        if (isset($data['zip_client'])) {
            $save->zip_client = $data['zip_client'];
        }
        if (isset($data['taxpayer_client'])) {
            $save->taxpayer_client = $data['taxpayer_client'];
        }

        $save->save();

        $message = [
            'type' => 'success',
            'message' => 'El cliente, se registro con éxito',
        ];

        return redirect()->route('clients.index')->with('message', $message);
    }

    public function show($id)
    {

        $getClient = Client::whereIdClient($id)->whereEnabledClient(1)->get()[0];
        $getState = Estados::whereIdEstado($getClient->id_state)->get()[0]->estado;

        $conf = [
            'title-section' => 'Datos del cliente: ' . $getClient->name_client,
            'group' => 'sales-clients',
            'back' => 'clients.index',
            'edit' => ['route' => 'clients.edit', 'id' => $getClient->id_client],
            'url' => '/sales/clients',
            'delete' => ['name' => 'Eliminar cliente']
        ];

        return view('sales.clients.show', compact('conf', 'getClient', 'getState'));
    }

    public function edit($id)
    {
        $client = Client::whereIdClient($id)->whereEnabledClient(1)->get()[0];
        $estados = Estados::pluck('estado', 'id_estado');
        $letra = substr($client->idcard_client, 0, 1);
        $numero = substr($client->idcard_client, 1);
        $client->idcard_client = $numero;

        $conf = [
            'title-section' => 'Editar cliente: ' . $client->name_client,
            'group' => 'sales-clients',
            'back' => ['route' => "./", 'show' => true],
            'url' => '/sales/clients',
        ];

        return view('sales.clients.edit', compact('conf', 'letra', 'client', 'estados'));
    }

    public function update(Request $request, $id)
    {

        $data = $request->except('_token', '_method', 'letra');
        $data['name_client'] = strtoupper($data['name_client']);
        $data['idcard_client'] = strtoupper($request->letra) . $data['idcard_client'];
        $data['address_client'] = strtoupper($data['address_client']);
        $data['id_state'] = $data['id_state'];

        if (isset($data['phone_client'])) {
            $data['phone_client'] = $data['phone_client'];
        }
        if (isset($data['email_client'])) {
            $data['email_client'] = strtoupper($data['email_client']);
        }
        if (isset($data['zip_client'])) {
            $data['zip_client'] = $data['zip_client'];
        }
        if (isset($data['taxpayer_client'])) {
            $data['taxpayer_client'] = $data['taxpayer_client'];
        }
        if (isset($data['porcentual_amount_tax_client'])) {
            $data['porcentual_amount_tax_client'] = $data['porcentual_amount_tax_client'];
        }

        Client::whereIdClient($id)->update($data);
        //return isset($data['porcentual_amount_tax_client']);



        $message = [
            'type' => 'warning',
            'message' => 'El cliente, se actualizo con éxito',
        ];




        return redirect()->route('clients.index')->with('message', $message);
    }

    public function destroy($id)
    {

        $data = Client::whereIdClient($id)->update(
            ['enabled_client' => 0]
        );

        return redirect()->route('clients.index')->with('success', 'Usuario eliminado con exito');
    }






    function searchCliente(Request $request)
    {
        $data = Client::whereIdcardClient($request->text)->get();
        if (count($data) > 0) {
            return response()->json(['res' => false, 'msg' => 'El DNI ó RIF ya fueregistrado']);
        } else {
            return response()->json(['res' => true, 'msg' => 'El DNI ó RIF es valido']);
        }
        return $data;
    }


    public function search(Request $request)
    {
        $data = DB::select('SELECT id_client, phone_client, name_client, idcard_client, address_client 
                            FROM clients 
                            WHERE name_client LIKE "%' . $request->text . '%" 
                            OR idcard_client LIKE "%' . $request->text . '%"
                            AND enabled_client = 1');
        return response()->json(['lista' => $data]);
    }
}
