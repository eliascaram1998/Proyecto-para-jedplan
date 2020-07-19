@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="login-page">
					<div class="form">
					<div id='first'>
					<form id='form'>
					<input type='hidden' name='_token' value='{{ csrf_token() }}'/>
					<label>Fecha de nacimiento</label>
					<input name='birthday' id='birth' type="date" value='{{\Carbon\Carbon::parse($user->birthday)->format('Y-m-d')}}'>
					<label>Sexo</label>
					<select class="form-control" name='sex'>
					@if($user->sex=='Masculino')
					<option selected>Masculino</option>
					<option>Femenino</option>
					@elseif($user->sex=='Femenino')
					<option>Masculino</option>
					<option selected>Femenino</option>
					@else
					<option>Masculino</option>
					<option>Femenino</option>
					@endif
					</select><br>
					<label>Altura En CM</label>
					<input type="number" name='height' maxlength="3" id='height' value='{{$user->height}}'>
					<label>Peso en KG</label>
					<input type="number" name='weight' id='weight' value='{{$user->weight}}'>
					<button onclick='sig()' class="btn btn-primary">
                    {{ __('Siguiente') }}
                    </button>
					</form>
					</div>
					<div id='dir'>
					<div><p id="coordenadas"></p></div>
					<input type="text" id="search" value='{{$user->adress}}'> <input type="button" value="Ingrese su direccion" onClick="mapa.getCoords()">
					<div id="mapa" style="width: 250px; height: 350px;"> </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyCs38z3vgMbqi2_D68q7yB4sl2attlxsfc"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
	$('#dir').hide();
	mapa.initMap();
})
function sig() {
	event.preventDefault();
	const date=document.getElementById('birth').value;
	const age=calcularEdad(date);
	$.ajax({
            type: "POST",
            url: '/cargar/datos',
            data: $('#form').serialize(),
            success: function(response)
            {
             if (response=='Error') {
				 alert('El servidor detecto un dato en que no cumple las condiciones');
			 }
			 else {
				if (age>=18) {
				$('#first').hide();
				$('#dir').fadeIn(300);	
				}
				else {
				alert('Debe ser mayor de edad para el siguiente paso');
				}
			 }
			}
       });
}
function calcularEdad(fecha) {
    var hoy = new Date();
    var cumpleanos = new Date(fecha);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }
    return edad;
}
$("#height").on("keyup", function(evt) { //Verificamos que la altura sea posible
  if (this.value>250) {
	alert('Ingrese un altura valida');
	const value = this.value.substring(0, this.value.length - 1);
    $('#height').val(value);
  }
});
$("#weight").on("keyup", function(evt) { //Verificamos que el peso sea posible
  if (this.value>350) {
	alert('Ingrese un peso valido');
	const value = this.value.substring(0, this.value.length - 1);
    $('#weight').val(value);
  }
});
</script>
<script>

mapa = {
 map : false,
 marker : false,

 initMap : function() {

 // Creamos un objeto mapa y especificamos el elemento DOM donde se va a mostrar.

 mapa.map = new google.maps.Map(document.getElementById('mapa'), {
   @if (isset($user->lat) and isset($user->lng))
   center: {lat: {{$user->lat}}, lng: {{$user->lng}}},
   @else 
   center:{lat: -34.6083, lng: -58.3712},
   @endif
   scrollwheel: false,
   zoom: 14,
   zoomControl: true,
   rotateControl : false,
   mapTypeControl: true,
   streetViewControl: false,
 });

 // Creamos el marcador
 mapa.marker = new google.maps.Marker({
 position: {lat: 43.2686751, lng: -2.9340005},
 draggable: true
 });

 // Le asignamos el mapa a los marcadores.
  mapa.marker.setMap(mapa.map);

 },

// función que se ejecuta al pulsar el botón buscar dirección
getCoords : function()
{
  // Creamos el objeto geodecoder
 var geocoder = new google.maps.Geocoder();

 address = document.getElementById('search').value;
 if(address!='')
 {
  // Llamamos a la función geodecode pasandole la dirección que hemos introducido en la caja de texto.
 geocoder.geocode({ 'address': address}, function(results, status)
 {
   if (status == 'OK')
   {
// Mostramos las coordenadas obtenidas en el p con id coordenadas
   document.getElementById("coordenadas").innerHTML='Coordenadas:   '+results[0].geometry.location.lat()+', '+results[0].geometry.location.lng();
// Posicionamos el marcador en las coordenadas obtenidas
   mapa.marker.setPosition(results[0].geometry.location);
// Centramos el mapa en las coordenadas obtenidas
   mapa.map.setCenter(mapa.marker.getPosition());
   cargarUbicacion(results[0].geometry.location.lat(),results[0].geometry.location.lng());
   }
  });
 }
 }
}
function cargarUbicacion(lat,lng) {
	$.ajax({
            type: "POST",
            url: '/cargar/ubicacion',
            data: {
			"_token": "{{ csrf_token() }}",
			"lat": lat,
			"lng":lng,
			"addr":document.getElementById('search').value,
			},
            success: function(response)
            {
             
			}
       });
 }
</script>
@endsection
