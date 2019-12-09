<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
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
                      <p style="margin:10px 0px 0px 30px;font-size:1.4rem;">Presupuesto : X</p>


                    <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">Nª <?php echo str_pad($venta -> id, 8, "0", STR_PAD_LEFT); ?></p>
                    <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">C.U.I.T : {{$Company->cuit}}  </p>
                    <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">{{$Company->condicion_fiscal}}</p>
                    <p style="margin:10px 0px 0px 30px;font-size:0.8rem;">INICIO DE ACTIV. 01/01/10</p>

                </th>

            </tr>
            <tr>



                <td style="border:1px solid black;">
                    <p style="text-align:center;font-size:0.7rem;">Tel: {{$Company->telefono_responsable}} - Mail: {{$Company->correo}}</p>
                </td>


                <td style="border:1px solid black;">
                    <p style="text-align:center;font-size:0.7rem;">Fecha : {{$venta -> fecha}}

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

                    {{$venta -> cliente -> nombre}}

            </td>
            <td style="border:1px solid black;width:25px;">

                {{$venta -> cliente -> direccion1}}

            </td>

            <td style="border:1px solid black;width:65px;">

                {{$venta -> cliente -> documento}}

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


            @foreach($items as $Item)





                <style>

                    /*.Tr:last-child {*/
                    /*    min-height:540px;*/
                    /*}*/
                </style>

                 @php
                       $preciounitario = $Item -> item -> total / $Item -> item -> cantidad;

                @endphp

                <tr class="Tr" style="text-align:center;font-size:0.7rem;height:350px;">
                    <td style="padding:10px 0px;">{{$Item -> item -> cantidad}}</td>
                    <td style="padding:10px 0px;">{{$Item -> item -> descripcion}}</td>
                    <td style="padding:10px 0px;"></td>
                    <td style="padding:10px 0px;">$ {{$preciounitario}}</td>
                    <td style="padding:10px 0px;">% 0</td>
                    <td style="padding:10px 0px;">$ {{$Item -> item -> total}}</td>
                    <td style="padding:10px 0px;">% {{0.00}}</td>
                    <td style="padding:10px 0px;">$ {{0.00}}</td>
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










        <table style="border-bottom:2px solid black;border-left:2px solid black;border-right:2px solid black;width:732px;border-collapse: collapse;">
            <tr style="font-size:.7rem;">
                <th style="width:362px;border:1px solid black;">
                    <p style="text-align:center;margin:10px 0px;">Otro importe tributos $ 0.00</p>
                </th>
                <th style="border:1px solid black;">
                    <p style="text-align:center;margin:10px 0px;">Importe Total $ {{$venta->total}}</p>
                </th>
                <th style="border:1px solid black;">
                    <p style="text-align:center;margin:10px 0px;">

                      @php

                        echo "$ ".$venta -> total;;

                     @endphp

                     </p>

                </th>
            </tr>

        </table>



</body>
</html>
