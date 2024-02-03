<?php require_once('comunes/head.php'); ?>
<link rel="stylesheet" type="text/css" href="css/style.css">
<body>
	<?php require_once('comunes/menu.php');?>
	<?php require_once("comunes/carga.php"); ?>

	<?php if(isset($control_time_reset)){ ?>

		<?php if(!$control_time_reset){?>

	<div class="container bg-white p-4 text-center" id="link-invalid">
		<div class="row">
			<div class="col">
				<h1 class="titulo_muestra">Restablecer contraseña</h1>
				<p class="mb-5 lead">El link no esta disponible o es invalido</p>
				<a class="btn btn-secondary text-light" href="?p=principal">Volver</a>
			</div>
		</div>
	</div>
		<?php }
		else{ ?>
	<div class="container bg-white p-4" id="new-pass-container">
		<div class="row justify-content-center">
			<div class="col col-lg-6 col-md-8">
				
				<h1 class="titulo_muestra text-center">Restablecer contraseña</h1>
				<br>
				<form method="POST" action="" id="form1">
					<input type="hidden" name="i" value='<?=json_encode($data_user_reset); ?>'>
					<div class="row">
						<div class="col">
							<label for="nueva_contrasena">Nueva Contraseña</label>
							<div class="input-group">
								<input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" data-span="invalid-span-nueva_contrasena">
								<span class="input-group-text cursor-pointer" id="visibility_btn_new_pass"><span class="fa fa-eye-slash" ></span></span>
							</div>
							<span id="invalid-span-nueva_contrasena" class="invalid-span text-danger"></span>
						</div>
					</div>
					<div class="row">
						<div class="col">
							
							<label for="confirmar">Confirmar Contraseña</label>
							<div class="input-group">
								<input type="password" class="form-control" id="confirmar" name="confirmar" data-span="invalid-span-confirmar">
								<span class="input-group-text cursor-pointer" id="visibility_btn_confirm"><span class="fa fa-eye-slash" ></span></span>
							</div>
							<span id="invalid-span-confirmar" class="invalid-span text-danger"></span>
						</div>
					</div>

					<br>
					<div class="text-center">
						<button id="confirmar_btn" class="btn btn-primary" type="button">Confirmar</button>
					</div>
				</form>
				<br>
			</div>
		</div>
	</div>

	<div class="d-none container bg-white p-4 text-center" id="new-pass-valid-container">
		<div class="row justify-content-center" >
			<div class="col">
				<h2 class="titulo_muestra">Contraseña Restablecida</h2>
				<p class="mb-5 lead">La contraseña ha sido restablecida exitosamente</p>
				
				<a class="btn btn-secondary text-light" href="?p=principal">Volver</a>
			</div>
			
		</div>
	</div>
	<?php } ?>
<?php }
else{
 ?>
	<div class="container bg-white p-4" id="send-mail-container">
		<!-- TODO eliminar el auto completado -->
		<div class="row justify-content-center">
			<div class="col col-lg-6 col-md-8">
				<h1 class="titulo_muestra text-center">Restablecer contraseña</h1>
				<form method="POST" action="" id="form2">

					<p class="text-center">Ingrese los datos a continuación</p>
					
					<div class="row">
						<div class="col">
							<label for="cedula_for_reset">Cedula</label>
							<input type="text" class="form-control" id="cedula_for_reset" name="cedula_for_reset" data-span="invalid-span-cedula_for_reset">
							<span id="invalid-span-cedula_for_reset" class="invalid-span text-danger"></span>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="select_user_type">Tipo de usuario</label>
							<select class="form-control" id="select_user_type" name="select_user_type" data-span="invalid-span-select_user_type">
								<option value="usuario">Usuario</option>
								<option value="habitante">Habitante</option>
							</select>
							<span id="invalid-span-select_user_type" class="invalid-span text-danger"></span>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="email_for_reset">Correo</label>
							<input type="text" class="form-control" id="email_for_reset" name="email_for_reset" data-span="invalid-span-email_for_reset">
							<span id="invalid-span-email_for_reset" class="invalid-span text-danger"></span>
						</div>
					</div>
					<div class="text-center mt-3">
						<button id="confirmar_btn_send_mail" type="button" class="btn btn-primary">Confirmar</button>
					</div>

					<div class="d-none">
						el nuevo enlace es: <a href="#" id="enlace_final">Este</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="d-none container bg-white p-4 text-center" id="sended-mail-container">
		<div class="row justify-content-center" >
			<div class="col">
				<h2 class="titulo_muestra">Solicitud Enviada</h2>
				<p class="mb-5 lead">Si la cedula y el correo son validos se le enviara un correo para el restablecimientos de su contraseña</p>
				<p>De no haberlo recibido verifique el apartado de <b>correos no deseados</b> o <b>Inténtelo nuevamente</b></p>
				<a class="btn btn-secondary text-light" href="?p=principal">Volver</a>
			</div>
			
		</div>
	</div>

	

<?php } ?>
	


	<?php require_once('comunes/foot.php'); ?>
	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
	<script type="text/javascript">
	// nueva contraseña
		if(document.getElementById('new-pass-container')){

			document.getElementById('visibility_btn_new_pass').onclick = function(){
				if(document.getElementById('nueva_contrasena').type == "password"){
					document.getElementById('nueva_contrasena').type = "text";
					this.getElementsByTagName('span')[0].className = "fa fa-eye cursor-pointer";
				}
				else{
					document.getElementById('nueva_contrasena').type = "password";
					this.getElementsByTagName('span')[0].className = "fa fa-eye-slash cursor-pointer";
				}
			}

			document.getElementById('visibility_btn_confirm').onclick = function(){
				if(document.getElementById('confirmar').type == "password"){
					document.getElementById('confirmar').type = "text";
					this.getElementsByTagName('span')[0].className = "fa fa-eye cursor-pointer";
				}
				else{
					document.getElementById('confirmar').type = "password";
					this.getElementsByTagName('span')[0].className = "fa fa-eye-slash cursor-pointer";
				}
			}

			eventoKeyup("nueva_contrasena", /^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{6,20}$/, "Solo letras y/o numeros entre 6 y 20 caracteres");

			document.getElementById("confirmar").onkeyup = document.getElementById("confirmar").validarme = function (){
				if(document.getElementById("nueva_contrasena").value !== document.getElementById("confirmar").value){
					validarKeyUp(false, "confirmar", "Las contraseñas no coinciden");
					return false;
				}
				else{
					validarKeyUp(true, "confirmar", "");
					return true;
				}
			}

			document.getElementById("confirmar_btn").onclick=function(){
				var resp1 = document.getElementById("nueva_contrasena").validarme();
				var resp2 = document.getElementById("confirmar").validarme();
				if( resp1 == true && resp2  == true)
				{
					// alert("es valido");

					var datos = new FormData($("#form1")[0]);
					datos.append("accion","reset_pass");
					enviaAjax(datos,function(respuesta){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "reset_pass"){
							document.getElementById('new-pass-container').classList.add("d-none");
							document.getElementById('new-pass-valid-container').classList.remove("d-none");
						}
						else if (lee.resultado == 'is-invalid'){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else if(lee.resultado == "error"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee.mensaje);
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
					});




				}
				
			}
		}
	//nueva contraseña
	//enviar correo

		if(document.getElementById('send-mail-container')){


			cedulaKeypress(document.getElementById("cedula_for_reset"));

			eventoKeyup("cedula_for_reset", V.expCedula_l, "La cedula no es valida ej.(V-00000001) ");
			eventoKeyup("email_for_reset", V.expEmail, "El correo no es valido");
			document.getElementById('confirmar_btn_send_mail').onclick = function(){

				if(document.getElementById('cedula_for_reset').validarme()&&document.getElementById('email_for_reset').validarme()){

					var datos = new FormData($("#form2")[0]);
					datos.append("accion","reset_pass_request");
					enviaAjax(datos,function(respuesta){
					
						var lee = JSON.parse(respuesta);
						if(lee.mensaje2){
							document.getElementById('enlace_final').href = lee.mensaje2;
						}
						
						if(lee.resultado == "reset_pass_request"){
							document.getElementById('send-mail-container').classList.add("d-none");
							document.getElementById('sended-mail-container').classList.remove("d-none");
							
						}
						else if (lee.resultado == 'is-invalid'){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else if(lee.resultado == "error"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee);
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
					});
				}

			}
		}

	//enviar correo

	</script>
</body>
</html>