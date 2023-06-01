<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use App\Models\Conf\Compania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompaniaController extends Controller
{
    

    function __construct()
    {
        $this->middleware('permission:user-list|adm-list|user-profile-list', ['only' => ['index', 'profile']]);
        $this->middleware('permission:adm-create|user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|user-edit|user-profile-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $conf = [
            'title-section' => 'Compañias',
            'group' => 'user',
            'create' => ['route' => 'compania.create', 'name' => 'Nueva Compañia',],
        ];
        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre', 'Rut',],
            'w_ts' => ['3', '', ''],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'tds' => ['name', 'rut'],
            'td_number' => [false, false, false],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,
            'show' => true,
            'url' => "/mantenice/compania",
            'id' => 'id',
            'group' => 'adm',
            'data' => Compania::select()

                ->where('name', '<>', 'Admin')->orderBy('id', 'ASC')->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('conf.compania.index', compact('table', 'conf'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $conf = [
            'title-section' => 'Crear nueva empresa',
            'group' => 'adm',
            'back' => 'compania.index',
            'url' => '#'
        ];
        return view('conf.compania.create', compact('conf'));
    }

    public function store(Request $request)
    {

        $input = $request->except('_token');

        //return $input;
        
        $compania = new Compania();


        $compania->name = $input['name'];
        $compania->rut = $input['rut'];
        $compania->save();
        return redirect()->route('compania.index')->with('message', 'Compania creada con éxito');
    }

    

    public function show($id)
    {
        $user = Compania::find($id);
        $conf = [
            'title-section' => 'Compañia: ' . $user->name,
            'group' => 'adm',
            'back' => 'compania.index',
            'url' => '#',
            'edit' => ['route' => 'compania.edit', 'id' => $user->id],
            'delete' => ['name' => 'Eliminar usuario'],
        ];
        return view('conf.compania.show', compact('user', 'conf'));
    }

    public function edit($id)
    {
        $user = Compania::find($id);
        $conf = [
            'title-section' => 'Usuario: ' . $user->name,
            'group' => 'adm',
            'back_show' => ['route' => 'compania.show', 'id' => $user->id],
            'url' => '#',
            'delete' => ['name' => 'Eliminar compañia'],
        ];
        return view('conf.compania.edit', compact('user', 'conf'));
    }

    public function update(Request $request, $id)
    {
        
        $input = $request->except('_token', '_method');
        
        
        

        Compania::whereId($id)->update($input);


        return redirect()->route('compania.index')->with('warning', 'Usuario actualizado con éxito');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')->with('error', 'Usuario eliminado con éxito');
    }
}
