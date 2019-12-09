<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Afip_res\Afip;
//use App\Http\Controllers\Afip_res\AfipWebService;
//use App\Http\Controllers\Afip_res\ClassT\ElectronicBilling;

class CFacturaController extends Afip
{
  public function facturar(Request $request){
  //  include (app_path (). '/Http/Controllers/Afip_res/Afip.php');
    $afip = new Afip(array('CUIT' => 20955290454,'production'=> TRUE,'cert' => 'cert.crt', 'key' => '2487Elba', 'passphrase' =>'2487Elba'));
    $last_voucher = $afip->ElectronicBilling->GetLastVoucher(2,11);
    $comprobante=$last_voucher+1;

   /**/ $data = array(
      'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
      'PtoVta' 	=> 2,  // Punto de venta
      'CbteTipo' 	=> 11,  // Tipo de comprobante (ver tipos disponibles)
      'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
      'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
      'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
      'CbteDesde' 	=> $comprobante,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
      'CbteHasta' 	=> $comprobante,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
      'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
      'ImpTotal' 	=> 50.00, // Importe total del comprobante
      'ImpTotConc' 	=> 0,   // Importe neto no gravado
      'ImpNeto' 	=> 50.00, // Importe neto gravado
      'ImpOpEx' 	=> 0,   // Importe exento de IVA
      'ImpIVA' 	=> 0,  //Importe total de IVA
      'ImpTrib' 	=> 0,   //Importe total de tributos
      'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
      'MonCotiz' 	=> 1     // Cotización de la moneda usada (1 para pesos argentinos)

    );

    $res = $afip->ElectronicBilling->CreateNextVoucher($data);

   //echo $res['CAE']; //CAE asignado el comprobante
   //echo $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
   //echo $res['voucher_number'];
   $mensaje="CAE: ".$res['CAE']." Vencimiento: ".$res['CAEFchVto']." Numero: ".$res['voucher_number'];
   return $mensaje;

  }

}
?>
