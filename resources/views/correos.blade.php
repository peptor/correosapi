<!DOCTYPE html>
<html lang="ca">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
	<title>Conexions test API correos</title>	
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
	<script src="{{ asset('js/app.js') }}" type="text/js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class = 'col-md-12'>
			<h1>Proves de connexió a API correos.es SOAP</h1>
			<hr>
			<h2>Funcions Preproducció</h2>
			<div>	
				<a class="btn btn-primary" href = "{{url('/llistaFuncions/1')}}">Llista funcions API</a>		
				<a class="btn btn-success" href = "{{url('/PreRegistroEnvio/1')}}">preRegistro Envio</a>
				<a class="btn btn-success" href = "{{url('/AnularOp/1')}}">Anular Operación</a>
				<a class="btn btn-success" href = "{{url('/SolicitudEtiquetaOp/1')}}">Solicitud Etiqueta</a>
				{{-- <a class="btn btn-success" href = "{{url('/preLocalizadorOficinas')}}">Localizador Oficinas</a> --}}
			</div>
			<hr>
			<div class="hidden">
				<h2>Funcions Producció</h2>
				<div>	
					<a class="btn btn-primary" href = "{{url('/llistaFuncions/0')}}">Llista funcions API</a>		
					<a class="btn btn-success disabled" href = "{{url('/PreRegistroEnvio/0')}}">preRegistro Envio</a>
				</div>
			</div>
			<hr>
		</div>
		
			{{-- <div class = 'col-md-6'> --}}
			{{-- <h1>Proves de connexió a API correos.es CURL</h1> --}}
			{{-- <hr> --}}
			{{-- <h2>Funcions Preproducció</h2> --}}
			{{-- <div>	 --}}
				{{-- <a class="btn btn-success" href = "{{url('/preCurlPreRegistroEnvio')}}">preRegistro Envio</a> --}}
				{{-- <a class="btn btn-success" href = "{{url('/preCurAnularOp')}}">Anular Operación</a> --}}
				{{-- <a class="btn btn-success" href = "{{url('/preCurlAnularOp')}}">Anular Operación 2</a> --}}
				{{-- <a class="btn btn-success" href = "{{url('/preCurlLocalizadorOficinas')}}">Localizador Oficinas</a> --}}
			{{-- </div> --}}
			{{-- <hr> --}}
			{{-- <div class="hidden"> --}}
				{{-- <h2>Funcions Producció</h2> --}}
				{{-- <div>	 --}}
					{{-- <a class="btn btn-primary" href = "{{url('/llistaProducio')}}">Llista funcions API</a>		 --}}
					{{-- <a class="btn btn-success disabled" href = "{{url('/prodPreRegistroEnvio')}}">preRegistro Envio</a> --}}
					{{-- <a class="btn btn-success" href = "{{url('/prodCalculaTarifa')}}">Calcula Tarifa</a> --}}
				{{-- </div> --}}
			{{-- </div> --}}
			{{-- <hr> --}}
		{{-- </div>  --}}
		{{-- <div class="row" style="padding: 10px; border: 2px;"> --}}
			{{-- <label>Petición</label> --}}
			{{-- <div> --}}
				<?php 
				//    if(isset($peticion)){ 
                //					echo $peticion;
				//	}
					?>	
			{{-- </div> --}}
		{{-- </div> --}}
		
		<div class="row" style="padding: 10px; border: 2px;">
			<label>Respuesta</label>
			<div>
				<?php
					echo $log;
				?>	
			</div>
		</div>	

		@if(isset($etiqueta))
			<?php
				$dades = file_get_contents($etiqueta);
				$dades = base64_encode($dades);
			?>

			<div class="row" style="padding: 10px; border: 2px;">
				<label>Etiqueta</label>   
				<object
				data="data:application/pdf;base64,{{$dades}}"
				type="application/pdf" width="100%" height="600px"></object>
			</div>
		@endif
	</div>		
</body>
</html>