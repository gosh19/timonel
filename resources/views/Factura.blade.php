<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<style>
    @font-face {
        font-family: barcode;
        src: url({{storage_path("fonts/LibreBarcode128-Regular.ttf")}});
    }
</style>
<body>

<table style="width:722px;border-collapse: collapse;border-top:2px solid black;border-left:2px solid black;border-right:2px solid black;">
    <tr>

        <th style="border:1px solid black;width:361px;">


            <img src="{{$Company->profile_image}}" style="width:180px;display:block;margin:10px 0px 10px 80px;">
            <p style="font-size:0.8rem;margin:10px 50px;text-align:center;">
                {{$Company->razon_social}}
                /
                {{$Company->direccion}}
            </p>


        </th>
        <th style="border:1px solid black;width:361px;">
            @if($factura -> CbteTipo == 1)
                <p style="margin:10px 0px 0px 30px;font-size:1.4rem;">Factura : A</p>
            @endif
            @if($factura->CbteTipo == 6)
                <p style="margin:10px 0px 0px 30px;font-size:1.4rem;">Factura : B</p>
            @endif
            @if($factura->CbteTipo == 13)
                <p style="margin:10px 0px 0px 30px;font-size:1.4rem;">Factura : C</p>
            @endif

            <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">
                Nª <?php echo str_pad($factura->PtoVta, 4, "0", STR_PAD_LEFT); ?>
                -<?php echo str_pad($factura->CbteDesde, 8, "0", STR_PAD_LEFT);?></p>
            <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">C.U.I.T : {{$Company->cuit}}  </p>
            <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">{{$Company->condicion_fiscal}}</p>
            <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">INICIO DE ACTIV. {{$Company->inicio_actividades}}</p>

        </th>

    </tr>
    <tr>


        <td style="border:1px solid black;">
            <p style="text-align:center;font-size:0.7rem;">Tel: {{$Company->telefono_responsable}} -
                Mail: {{$Company->correo}}</p>
        </td>


        <td style="border:1px solid black;">
            <p style="text-align:center;font-size:0.7rem;">Fecha :
                @php
                    $date = explode(' ',now());
                    echo $date[0];
                @endphp
            </p>
        </td>
    </tr>
</table>

<table style="border-right:2px solid black;border-left:2px solid black;width:732px;border-collapse: collapse;">
    <tr style="text-align:center;font-size:0.7rem;">
        <th style="border:1px solid black;width:25px;">
            Nombre del cliente.
        </th>
        <th style="border:1px solid black;width:25px;">
            Direccion.
        </th>

        <th style="border:1px solid black;width:65px;">
            CUIT / DNI
        </th>
    </tr>
    <tr style="text-align:center;font-size:0.7rem;">
        <td style="border:1px solid black;width:25px;">

            {{$factura -> cliente -> nombre}}

        </td>
        <td style="border:1px solid black;width:25px;">

            {{$factura -> cliente -> direccion1}}

        </td>

        <td style="border:1px solid black;width:65px;">

            {{$factura -> cliente -> documento}}

        </td>
    </tr>

</table>

<table style="border-right:2px solid black;border-left:2px solid black;width:722px;border-collapse: collapse;">
    <tr style="text-align:center;font-size:0.7rem;">
        <th style="border:1px solid black;width:40px;">
            Cant
        </th>
        <th style="border:1px solid black;width:251.5px;">
            Detalle de producto / servicio
        </th>
        <th style="border:1px solid black;width:63.5px;">
            U.med
        </th>
        <th style="border:1px solid black;width:63.5px;">
            Precio Unit.
        </th>
        <th style="border:1px solid black;width:63.5px;">
            % Bonif
        </th>
        <th style="border:1px solid black;width:63.5px;">
            Subtotal
        </th>
        <th style="border:1px solid black;width:63.5px;">
            Alicuota IVA
        </th>
        <th style="border:1px solid black;width:95px;">
            Subtotal c/IVA
        </th>
    </tr>

    @php

        $Items = json_decode($dataT['Items']);

    @endphp

    @foreach($Items as $Item)

        @php
            $cantidad = $Item[5] * $Item[9];

            $SubtotalConIva = $cantidad + $Item[7];

        @endphp
        <tr class="Tr" style="text-align:center;font-size:0.7rem;height:350px;">
            <td style="padding:10px 0px;">{{$Item[9]}}</td>
            <td style="padding:10px 0px;">{{$Item[3]}}</td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;">$ {{$Item[5]}}</td>
            <td style="padding:10px 0px;">% 0</td>
            <td style="padding:10px 0px;">$ {{$Item[7]}}</td>
            <td style="padding:10px 0px;">% {{$Item[4]}}</td>
            <td style="padding:10px 0px;">$ {{$Item[7]}}</td>
        </tr>

        @php $iterator = $loop->count; @endphp
    @endforeach()
    @if($iterator < 6)

        @php

            for($i = 0; $i < 10 ; $i++){

        @endphp

        <tr class="Tr" style="text-align:center;font-size:0.7rem;height:350px;">
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
        </tr>

        @php
            }
        @endphp

    @else
        @php
            for($i = 0; $i < 5 ; $i++){
        @endphp
        <tr class="Tr" style="text-align:center;font-size:0.7rem;height:350px;">
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
            <td style="padding:10px 0px;"></td>
        </tr>
        @php
            }
        @endphp
    @endif

</table>


<table style="border-left:2px solid black;border-right:2px solid black;width:326px;border-collapse: collapse;margin:-2px 0px;">
    <tr style="text-align:center;font-size:0.7rem;">
        <th style="border:1px solid black;width:190px;">
            otros tributos descripcion
        </th>
        <th style="border:1px solid black; width:60px;">
            Detalle
        </th>
        <th style="border:1px solid black;width:50px;">
            % Alic.
        </th>
        <th style="border:1px solid black;width:53px;">
            Importe
        </th>
        <th style="border:1px solid black;width:360px;">
            SUMATORIA TOTAL
        </th>
    </tr>
    <tr style="text-align:center;font-size:0.7rem;">
        <td style="padding:5px 0px;">
            <p>Per./Ret. de Impusto a las Ganacias</p>

            <p>Per./Ret. de Impusto a las Ganacias</p>

            <p>Per./Ret. de Impusto a las Ganacias</p>
        </td>

        <td style="padding:5px 0px;"></td>

        <td style="padding:5px 0px;"></td>

        <td style="padding:5px 0px;">
            <p>0.00</p>
            <p>0.00</p>
            <p>0.00</p>
        </td>

        <td style="border-left:1px solid black;">
            <table style="margin:0px;border-collapse: collapse;">
                <tr>
                    <th style="width:170px;">
                        <p style="text-align:center;">Importe Neto Gravado</p>
                        <p style="text-align:center;">IVA 27%:</p>
                        <p style="text-align:center;">IVA 21%:</p>
                        <p style="text-align:center;">IVA 10.5%:</p>

                    </th>
                    <th style="width:170px;">

                        <p style="text-align:center;">$ {{round($dataT['total'],2)}}</p>
                        <p style="text-align:center;">$ {{round($dataT['Iva27'],2)}}</p>
                        <p style="text-align:center;">$ {{round($dataT['Iva21'],2)}}</p>
                        <p style="text-align:center;">$ {{round($dataT['Iva105'],2)}}</p>


                    </th>
                </tr>
            </table>
        </td>
    </tr>
</table>


<table style="border-bottom:2px solid black;border-left:2px solid black;border-right:2px solid black;width:732px;border-collapse: collapse;">
    <tr style="font-size:.7rem;">
        <th style="width:362px;border:1px solid black;">
            <p style="text-align:center;margin:10px 0px;">Otro importe tributos $ 0.00</p>
        </th>
        <th style="border:1px solid black;">
            <p style="text-align:center;margin:10px 0px;">Importe Total $</p>
        </th>
        <th style="border:1px solid black;">
            <p style="text-align:center;margin:10px 0px;">

                @php

                    echo "$ ".$dataT['total'];

                @endphp

            </p>

        </th>
    </tr>
    <tr>
        <td>
            <table style="margin:-2px 0px 0px -1.3px;border:1px solid black;width:365.5px;border-collapse:collapse;">
                <tr>
                    <td style="border:1px solid black;width:200px;">
                        <img src="http://vgb.gov.ar/municipalidad/wp-content/uploads/sites/2/2017/05/logo-afip-900.png"
                             style="width:120px;margin:20px;">
                    </td>
                    <td style="width:120px;">
                        <p style="text-align:center;font-size:0.8rem;">Comprobante autorizado</p>
                    </td>
                </tr>
            </table>
        </td>
        <td style="text-align:center;font-size:0.8rem;width:170px;">
            CAE Nª:
        </td>
        <td style="text-align:center;font-size:0.8rem;width:188px;">
            {{$factura->CAE}}
        </td>
    </tr>
    <tr>
        <!--codigo de barras-->
        <td style="border-right:1px solid black;padding:10px;">
            <font style="font-family: 'barcode', cursive; font-size:50">
                {{$factura->CAE}}
            </font>
        </td>
        <td style="text-align:center;font-size:0.8rem;width:170px;">Fecha de VTO CAE:</td>
        <td style="text-align:center;font-size:0.8rem;width:188px;">{{$factura->CAEFchVto}}</td>
    </tr>
</table>


</body>
</html>
