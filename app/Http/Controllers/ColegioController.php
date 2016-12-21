<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Colegio;
use App\Comentario;


class ColegioController extends Controller
{
    
    /*
    public function __construct()
    {
        $this->middleware('oauth', ['only' => ['store', 'show']]);
    }
    */

    public function show($id)
    {

        $result = Comentario::join('colegio', 'comentario.colegio_id', '=', 'colegio.id')
                ->join('users', 'users.id', '=', 'comentario.user_id')
                ->select('colegio.nombre as nombre_colegio', 'colegio.latitud', 'colegio.longitud', 'comentario.calificacion', 'comentario.mensaje', 'users.nombre as nombre_usuario')
                ->getQuery()
                ->get();

        if(!$result)
        {
            return response()->json(['mensaje' => 'No se encuentra comentarios', 'codigo' => 404],404);
        }

         return response()->json(['datos' => $result ],200);

    }

    public function store(Request $request)
    {
            if(!$request->input('codigo') || ! $request->input('nombre'))
            {
                return response()->json(['mensaje' => 'No se pudieron procesar los valores', 'codigo' => 422],422);
            }

            Colegio::create
            ([
                'codigo' => $request->input('codigo'),
                'nombre' => $request->input('nombre'),
                'latitud' => $request->input('latitud'),
                'longitud' => $request->input('longitud'),
            ]);

            $colegio = Colegio::where('codigo', '=' , $request->input('codigo'))->first();
            
            Comentario::create
            ([
                'colegio_id' => $colegio->id,
                'user_id' => $request->input('user_id'),
                'calificacion' => $request->input('calificacion'),
                'mensaje' => $request->input('comentario'),
            ]);
            
            return response()->json(['mensaje' => 'Registro de comentario exitoso'],201);
    }




}
