<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Producto;
//use Request;
use Illuminate\Support\Facades\DB;
use App\Empresa;
use App\Proveedore;
use App\facturacompra;
use App\Libs\Afip\Afip;
use App\Factura;
use App\Servicio;
use Barryvdh\DomPDF\Facade as PDF;
use App\Libs\Afip\Classe\ElectronicBilling;

class facturacion extends Controller
{
    public function index()
    {
        $Clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)->get();

        $Productos = Producto::where('empresa_id', auth()->user()->empresa_id)->get();

        $Facturas = Factura::where('empresa_id', auth()->user()->empresa_id)->get();

        $Servicios = Servicio::where('empresa_id', auth()->user()->empresa_id)->get();

        return view('ABMS.Facturacion', compact('Clientes', 'Productos', 'Facturas', 'Servicios'));
    }

    public function Create(Request $request)
    {

        //$Data = explode('_',$details);

        $idCompany = auth()->user()->empresa_id;
        $Company = DB::table('empresas')
            ->where('id', $idCompany)
            ->first();

        $dataT = array(
            "Cliente" => $request->SelectCliente,
            "Items" => $request->items,
            "total" => $request->TotalFactura,
            "Usuario" => "1",
            "Iva21" => $request->iva21,
            "Iva105" => $request->iva105,
            "Iva27" => $request->iva27
        );
        $items = json_decode($request->items);
        $cont = 0;
        while ($cont < count($items)) {
            if ($items[$cont][1] != 9999 && $items[$cont][0] == 'Producto') {
                $producto = Producto::where('id', $items[$cont][1])->first();
                $stock = $producto->stock;
                $stock = $stock - $items[$cont][9];
                $producto->stock = $stock;
                $producto->save();

            }
            if ($items[$cont][1] == 9999) {

            }

            if ($items[$cont][1] != 9999 && $items[$cont][0] == 'Servicio') {
                $Servicio = Servicio::where('id', $items[$cont][1])->first();

                foreach ($Servicio->productos as $producto) {
                    $stock = $producto->stock;
                    $stock = $stock - $items[$cont][9];
                    $producto->stock = $stock;
                    $producto->save();
                }


            }

            $cont++;
        }

        $items = json_encode($request->items);

        $cliente = Cliente::where('id', $dataT["Cliente"])->first();


        $doc = $cliente->cuit;
        if (strlen($doc) == 11) {
            $codigodocumento = 80;
            $codigoComprobante = "1";
        } else {
            $codigoComprobante = "6";
            $codigodocumento = 99;
            $doc = 0;
        }
        if ($Company->condicion_fiscal == 'Monotributista') {
            $codigoComprobante = "13";
        }

            $afip = new Afip(array('CUIT' => $Company->cuit, 'production' => $Company->produccion, 'cert' => $Company->certificado, 'key' => $Company->clave));
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher(2, $codigoComprobante);
        $comprobante = $last_voucher + 1;
        $totaliva = $dataT["total"] - $request->neto;
        $ImpIVA = (float)$request->iva21 + (float)$request->iva27 + (float)$request->iva105;
        $ImpIVA = round($ImpIVA, 2);

        $IVAS = array();
        $Netoiva21 = (float)$request->Netoiva21 - (float)$request->iva21;
        $Netoiva105 = (float)$request->Netoiva105 - (float)$request->iva105;
        $Netoiva27 = (float)$request->Netoiva27 - (float)$request->iva27;

        $cont = 0;
        if ($request->iva21 != 0.00) {
            $arr21 = array(
                'Id' => 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                'BaseImp' => round($Netoiva21, 2), // Base imponible
                'Importe' => round($request->iva21, 2) // Importe
            );
            $IVAS[$cont] = $arr21;
            $cont++;
        }

        if ($request->iva105 != 0.00) {
            $arr105 = array(
                'Id' => 4, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                'BaseImp' => round($Netoiva105, 2), // Base imponible
                'Importe' => round($request->iva105, 2) // Importe
            );
            $IVAS[$cont] = $arr105;
            $cont++;

        }

        if ($request->iva27 != 0.00) {
            $arr27 = array(
                'Id' => 6, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                'BaseImp' => round($Netoiva27, 2), // Base imponible
                'Importe' => round($request->iva27, 2) // Importe
            );
            $IVAS[$cont] = $arr27;
            $cont++;

        }
        //dd($IVAS);
        date_default_timezone_set("America/Araguaina");
        if ($Company->condicion_fiscal != 'Monotributista') {
            $data = array(
                'CantReg' => 1,  // Cantidad de comprobantes a registrar
                'PtoVta' => (int)$Company->punto_venta,  // Punto de venta
                'CbteTipo' => (int)$codigoComprobante,  // Tipo de comprobante (ver tipos disponibles)
                'Concepto' => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo' => $codigodocumento, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro' => $doc,  // Número de documento del comprador (0 consumidor final)
                'CbteDesde' => $comprobante,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta' => $comprobante,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch' => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' => round($dataT["total"], 2), // Importe total del comprobante
                'ImpTotConc' => 0,   // Importe neto no gravado
                'ImpNeto' => round($request->neto, 2), // Importe neto gravado
                'ImpOpEx' => 0,   // Importe exento de IVA
                'ImpIVA' => $ImpIVA,  //Importe total de IVA
                'ImpTrib' => 0,   //Importe total de tributos
                'MonId' => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                'MonCotiz' => 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                'Iva' => $IVAS,
            );
            $res = $afip->ElectronicBilling->CreateNextVoucher($data);
            $CAE = $res['CAE']; //CAE asignado el comprobante
            $CAEFchVto = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
            $voucher_number = $res['voucher_number'];
        }else{
            $data = array(
                'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
                'PtoVta' 	=> (int)$Company->punto_venta,  // Punto de venta
                'CbteTipo' 	=> 13,  // Tipo de comprobante (ver tipos disponibles)
                'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo' 	=> $codigodocumento, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro' 	=> $doc,  // Número de documento del comprador (0 consumidor final)
                'CbteDesde' 	=> $comprobante,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta' 	=> $comprobante,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' 	=> round($dataT["total"], 2), // Importe total del comprobante
                'ImpTotConc' 	=> 0,   // Importe neto no gravado
                'ImpNeto' 	=> round($dataT["total"], 2), // Importe neto gravado
                'ImpOpEx' 	=> 0,   // Importe exento de IVA
                'ImpIVA' 	=> 0,  //Importe total de IVA
                'ImpTrib' 	=> 0,   //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                'MonCotiz' 	=> 1     // Cotización de la moneda usada (1 para pesos argentinos)
            );
            $res = $afip->ElectronicBilling->CreateNextVoucher($data);
            $CAE = $res['CAE']; //CAE asignado el comprobante
            $CAEFchVto = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
            $voucher_number = $res['voucher_number'];

        }
        $factura = new Factura;
        $factura->CantReg = 1;
        $factura->PtoVta = $Company->punto_venta;
        $factura->CbteTipo = $codigoComprobante;
        $factura->Concepto = 3;
        $factura->DocTipo = $codigodocumento;
        $factura->DocNro = $doc;
        $factura->CbteDesde = $comprobante;
        $factura->CbteHasta = $comprobante;
        $factura->CbteFch = intval(date('Ymd'));
        $factura->ImpTotal = $dataT["total"];
        $factura->ImpTotConc = 0;
        $factura->ImpNeto = round($request->neto, 2);
        $factura->ImpOpEx = 0;
        $factura->ImpIVA = $ImpIVA;
        $factura->ImpTrib = 0;
        $factura->MonId = 'PES';
        $factura->MonCotiz = 1;
        $factura->CAE = $CAE;
        $factura->CAEFchVto = $CAEFchVto;
        $factura->voucher_number = $voucher_number;
        $factura->cliente_id = $dataT["Cliente"];
        $factura->empresa_id = auth()->user()->empresa_id;
        $factura->items = $request->items;
        $factura->debe = round($dataT["total"], 2);
        $factura->pagado = 0.00;
        $factura->estado = 'Impaga';
        $factura->iva21 = round($request->iva21, 2);
        $factura->iva105 = round($request->iva105, 2);
        $factura->iva27 = round($request->iva27, 2);
        $factura->save();

        $pdf = PDF::loadView('Factura', compact('dataT', 'Company', 'factura'));
        return $pdf->stream('testfile')
            ->header('Content-Type', 'application/pdf');
        exit;


    }


    public function IndexFacturaCompra()
    {
        $Providers = Proveedore::all();

        $FacturaCompra = facturacompra::all();

        return view('ABMS.FacturaCompra', compact('Providers', 'FacturaCompra'));
    }

    public function QueryProvider($id)
    {

        $Provider = Proveedore::where('id', $id)->first();

        return $Provider;
    }

    public function CreateFacturaCompra(Request $request)
    {
        $Todo = Request::all();

        facturacompra::create($Todo);

        return redirect('Factura Compra');
    }

    public function DeleteFacturaCompra($id)
    {
        $DeleteFact = facturacompra::find($id);
        $DeleteFact->delete();

        return redirect('Factura Compra');
    }

    public function getFactura(Request $request)
    {
        $factura = Factura::where('id', $request->id)->first();
        $idCompany = auth()->user()->empresa_id;
        $Company = DB::table('empresas')
            ->where('id', $idCompany)
            ->first();
        //dd($factura);
        $dataT = array(
            "Cliente" => $factura->cliente->nombre,
            "Items" => $factura->items,
            "total" => $factura->ImpTotal,
            "Usuario" => "1",
            "Iva21" => $factura->iva21,
            "Iva105" => $factura->iva105,
            "Iva27" => $factura->iva27
        );

        $pdf = PDF::loadView('Factura', compact('dataT', 'Company', 'factura'));
        return $pdf->stream('testfile')
            ->header('Content-Type', 'application/pdf');
        exit;

    }

}
