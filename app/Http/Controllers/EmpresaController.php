<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Empresa;
use File;

class EmpresaController extends Controller
{

    public function delete(Request $request)
    {
        $id = $request->id;
        $empresa = Empresa::where("id", $id)->first();
        $name = $empresa->razon_social;
        $clave = $empresa->clave_p12;
        $certificado = $empresa->certificado;

        Storage::delete([$clave, $certificado]);
        $empresa->delete();
        return "Empresa Eliminada";


    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $empresa = Empresa::where("id", $id)->first();
        return response()->json($empresa);
    }

    public function add(Request $request)
    {
        $control = $request->control;
        //si esta en cero es por que no se ha seteado ningún id en la funcion load data que es donde se abre el modal con los datos
        //de la empresa seleccionada.

        if ($control == 0) {

            $test = $request->razon_social;
            $destino = app_path() . ('/Libs/Afip/Afip_res/CRT/' . $test . '/');
            $destino2 = public_path('/dist/empresas');

            $path = '';
            $path1 = '';
            $path3 = '';
            $profname = '';

            if ($request->hasFile('clave_en')) {

                $clave = $request->file('clave_en');
                $clavename = $clave->getClientOriginalName();
                $clave->move($destino, $clavename);
                $path = 'CRT/' . $test . '/' . $clavename;

            }
            if ($request->hasFile('cert')) {
                $cert = $request->file('cert');
                $certname = $cert->getClientOriginalName();
                $cert->move($destino, $certname);
                $path1 = 'CRT/' . $test . '/' . $certname;

            }
            if ($request->hasFile('clave')) {
                $claveprivada = $request->file('clave');
                $claveprivadaname = $claveprivada->getClientOriginalName();
                $claveprivada->move($destino, $claveprivadaname);
                $path3 = 'CRT/' . $test . '/' . $claveprivadaname;

            }
            if ($request->hasFile('profile_image')) {
                $prof = $request->file('profile_image');
                $profname = $prof->getClientOriginalName();
                $prof->move($destino2, $profname);

            }


            Empresa::insert([
                "razon_social" => $request->razon_social,
                "usuariosPermitidos" => $request->usuariosPermitidos,
                "cuit" => $request->cuit,
                "direccion" => $request->direccion,
                "telefono" => $request->telefono,
                "responsable" => $request->responsable,
                "telefono_responsable" => $request->telefono_responsable,
                "correo" => $request->correo,
                "clave_p12" => $path,
                "certificado" => $path1,
                "profile_image" => "dist/empresas/" . $profname,
                "clave" => $path3,
                "condicion_fiscal" => $request->condicion_fiscal,
                "inicio_actividades" => $request->inicio_actividades,
                "produccion" => $request->produccion,
                "punto_venta" => $request->punto_venta
            ]);

            $empresas = Empresa::all();


            return redirect()->to('empresa');


        } else {
            $razon_social = $request->razon_social;
            $usuariosPermitidos = $request->usuariosPermitidos;
            $cuit = $request->cuit;
            $direccion = $request->direccion;
            $telefono = $request->telefono;
            $responsable = $request->responsable;
            $telefono_responsable = $request->telefono_responsable;
            $correo = $request->correo;
            $condicion_fiscal = $request->condicion_fiscal;
            $inicio_actividades = $request->inicio_actividades;
            $produccion = $request->produccion;
            $punto_venta = $request->punto_venta;

            $id = $request->control;
            $empresa = Empresa::where("id", $id)->first();
            $test = $request->razon_social;

            $test = $request->razon_social;
            $destino = app_path() . ('/Libs/Afip/Afip_res/CRT/' . $test . '/');
            $destino2 = public_path('/dist/empresas');

            $path = '';
            $path1 = '';
            $path3 = '';
            $profname = '';

            if ($request->hasFile('clave_en')) {

                $clave = $request->file('clave_en');
                $clavename = $clave->getClientOriginalName();
                $clave->move($destino, $clavename);
                $path = 'CRT/' . $test . '/' . $clavename;

            }
            if ($request->hasFile('cert')) {
                $cert = $request->file('cert');
                $certname = $cert->getClientOriginalName();
                $cert->move($destino, $certname);
                $path1 = 'CRT/' . $test . '/' . $certname;

            }
            if ($request->hasFile('clave')) {
                $claveprivada = $request->file('clave');
                $claveprivadaname = $claveprivada->getClientOriginalName();
                $claveprivada->move($destino, $claveprivadaname);
                $path3 = 'CRT/' . $test . '/' . $claveprivadaname;

            }
            if ($request->hasFile('profile_image')) {
                $prof = $request->file('profile_image');
                $profname = $prof->getClientOriginalName();
                $prof->move($destino2, $profname);

            }


            $empresa->razon_social = $razon_social;
            $empresa->usuariosPermitidos = $usuariosPermitidos;
            $empresa->cuit = $cuit;
            $empresa->direccion = $direccion;
            $empresa->telefono = $telefono;
            $empresa->responsable = $responsable;
            $empresa->telefono_responsable = $telefono_responsable;
            $empresa->correo = $correo;
            $empresa->clave_p12 = $path;
            $empresa->certificado = $path1;
            $empresa->clave = $path3;
            $empresa->condicion_fiscal = $condicion_fiscal;
            $empresa->profile_image = "dist/empresas/" . $profname;
            $empresa->inicio_actividades = $inicio_actividades;
            $empresa->produccion = $produccion;
            $empresa->punto_venta = $punto_venta;
            $empresa->save();
            return redirect()->to('empresa');

        }
    }
}