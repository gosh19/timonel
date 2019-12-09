<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Request;
use App\Empresa;
use App\facturacompra;
use Illuminate\Support\Facades\DB;
use App\OrdenPago;
use App\Producto;
use App\Proveedore;
use App\ordenCompra;

class orden_de_Pago extends Controller
{
    public function index()
    {
        $Company = Empresa::all();
        
        $OPagos = OrdenPago::all();
        
        return view('ABMS.OrdenPago',compact('Company','OPagos'));
    }
    
    public function Search($data)
    {
        $ExpodeData = explode("_",$data);
        
        switch($ExpodeData[0])
        {
            case 'PorFecha':
                
                $filter = DB::table('facturacompras')
                ->where('created_at','>',$ExpodeData[1])
                ->get();
                
                return $filter;
                break;
                
            case 'PorID':
                 $filter = DB::table('facturacompras')
                ->where('id','=',$ExpodeData[1])
                ->get();
                
                return $filter;
                break;
                
            case 'TipoFactura':
                 $filter = DB::table('facturacompras')
                ->where('tipocomprobante','=',$ExpodeData[1])
                ->get();
                
                return $filter;
                break;
        }
        
        
    }
    
    public function Create(Request $request)
    {
        $all = Request::all();
        
        $Ids = json_decode($all['JsonIDFacturaCompra']);
        
        foreach($Ids as $Id)
        {
          $FacturaCompra = facturacompra::find($Id->ID);
          $FacturaCompra->estado = 'Saldado';
          $FacturaCompra->metodopago = $all['FormaPago']; 
          $FacturaCompra->save();
          
        }
        
        OrdenPago::create($all);
        
        return redirect('Ordenes Pago');
    }
    
    public function indexOrdenCompra()
    {
        $products = Producto::all();
        
        $provider = Proveedore::all();
        
        $OrderCompra = ordenCompra::all();
        
        return view('ABMS.OrdenCompra',compact('products','provider','OrderCompra'));
    }
    
    public function SearchProc($id)
    {
        $procSelected_ = DB::table('productos')
            ->where('id',$id)
            ->get();
        
        return $procSelected_;
    }
    
    public function CreateOrdenCompra(Request $request)
    {
        $all = Request::all();
        
        ordenCompra::create($all);
        
        return redirect('Ordenes de compra');
    }
}
