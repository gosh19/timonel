@extends( auth()->user()->id_categoria == 1 ? 'Layout/_LayoutSU' : 'Layout/_Layout')

@section('ProfiImage')
{{ auth()->user()->profile_image }}
@endsection

@section('empresaUth')
{{auth()->user()->empresa->razon_social}}
@endsection

@section('descripcion_puesto')
   {{auth()->user()->descripcion_puesto}}
@endsection

@section('namesidebar')
   {{ auth()->user()->name }}
@endsection

@section('wrapper')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="//js.pusher.com/3.0/pusher.min.js"></script>

<script type="text/javascript">

 function getMessages(nombre,id){
     $('.msg_history').html('');
     var m="#Mesages_"+id;
     var mensajes=$("#Mesages_"+id).html();

     $('.msg_history').html(mensajes);
     $('.'+nombre).css("display", "block");
     $('#usuarioActivo').val(id);
     //alert(id);
     //alert(mensajes);
 }
  $(document).ready(function(){
    Pusher.log = function(msg) {
        console.log(msg);
    };
    $('#addMessage').click(function(){
      var destino=$('#usuarioActivo').val();
      var message=$('#NewMessage').val();
      $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

     var data='message='+message+"&destino="+destino;
      $.ajax({
          method: "post",
          data: data,
          url: "<?php echo route('messages')?>",
          success: function(response){

              console.log(response);
          }
         });
    });
  });
</script>

<link rel="stylesheet" type="text/css" href="../../public/css/chat.css">
<div class="container">
<h3 class=" text-center">Chat</h3>
<input type="hidden" id="usuarioActivo" value="">
<div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recientes</h4>
            </div>
          </div>
          <input type="hidden" id="idauth" name="" value="{{auth()->user()->id}}">
          <div class="inbox_chat">
            @foreach($usuarios as $usuario)
              @if($usuario->id!=auth()->user()->id)
            <div class="chat_list"> <!--active_chat-->
              <div onClick="getMessages('{{$usuario->name}}',{{$usuario->id}});" class="chat_people">
                <div class="chat_img"> <img src="{{$usuario->profile_image}}" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>{{$usuario->name}} <span class="chat_date"></span></h5>
                  <!--p>Test, which is a new approach to have all solutions
                    astrology under one roof.</p-->
                </div>
              </div>
            </div>
            <div style="display:none;" id="Mesages_{{$usuario->id}}">
                @foreach($mensajes as $mensaje)
                    @if($usuario->id==$mensaje->destino_id && $mensaje->user_id==auth()->user()->id)
                      <div class="outgoing_msg">
                         <div class="sent_msg">
                            <p>{{$mensaje->message}}</p>
                            <span class="time_date"> 11:01 AM    |    June 9</span> </div>
                        </div>
                     @endif
                    @if($mensaje->user_id==$usuario->id && auth()->user()->id==$mensaje->destino_id)
                           <div class="incoming_msg">
                             <div class="incoming_msg_img"> <img src="{{$mensaje->user->profile_image}}" alt="sunil"> </div>
                             <div class="received_msg">
                               <div class="received_withd_msg">
                                 <p>{{$mensaje->message}}</p>
                                 <span class="time_date"> 11:01 AM    |    June 9</span></div>
                             </div>
                           </div>
                    @endif
                @endforeach
            </div>
            @endif
            @endforeach
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">


            <!--div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test which is a new approach to have all
                    solutions</p>
                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
              </div>
            </div-->
            <!--div class="outgoing_msg">
              <div class="sent_msg">
                <p>Test which is a new approach to have all
                  solutions</p>
                <span class="time_date"> 11:01 AM    |    June 9</span> </div>
            </div-->
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" id="NewMessage" class="write_msg" placeholder="Type a message" />
              <button class="msg_send_btn" id="addMessage" type="button"><i class="fas fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div></div>
<script type="text/javascript">


function addMessage(data) {
    // Create element from template and set values
    var msj="<div class='outgoing_msg'>"+
      "<div class='sent_msg'>"+
        "<p>"+data.message+"</p>"+
        "<span class='time_date'> 11:01 AM    |    June 9</span> </div>"+
    "</div>";

    $('.msg_history').append(msj);
}

var pusher = new Pusher('{{env("PUSHER_APP_KEY")}}',{
   cluster : '{{env("PUSHER_CLUSTER")}}',
    encrypted: false
});

var channel = pusher.subscribe('new-message');
channel.bind('App\\Events\\MessageSent', function(data) {
  var id=$('#idauth').val();
  var te=data.message['user_id'];
  var fecha=data.message['created_at'];
  var destino=data.message['destino_id'];
  var date = new Date(fecha);
  //$usuario->id==$mensaje->destino_id && $mensaje->user_id==auth()->user()->id
  //$mensaje->user_id==$usuario->id && auth()->user()->id==$mensaje->destino_id
  if(te==id){

    var msj="<div class='outgoing_msg'>"+
      "<div class='sent_msg'>"+
        "<p>"+data.message['message']+"</p>"+
        "<span class='time_date'> "+date.getTime()+"   |    June 9</span> </div>"+
    "</div>";


    $('#Mesages_'+destino).append(msj);
    var cont=$('#Mesages_'+destino).html();
    $('.msg_history').html(cont);
  }else{
     if(id==destino){
       var msj="<div class='incoming_msg'>"+
         "<div class='incoming_msg_img'> <img src='"+data.user['profile_image']+"' alt='sunil'> </div>"+
         "<div class='received_msg'>"+
           "<div class='received_withd_msg'>"+
           "  <p>"+data.message['message']+"</p>"+
           "  <span class='time_date'> "+date.getTime()+"   |    June 9</span></div>"+
         "</div>"+
       "</div>";

       $('#Mesages_'+te).append(msj);
       var cont=$('#Mesages_'+te).html();
       $('.msg_history').html(cont);

     }

  }

  console.log(data);
});

</script>
@endsection
