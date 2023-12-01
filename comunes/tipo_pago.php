<style>.uppercase{text-transform: uppercase;}</style>
<div id="formulario_tipo_pago_comun">
	<input type="hidden" id="id_tipo_pago_comun">
	<input type="hidden" id="id_pago_comun">

	<div class="row mb-2">
		<div class="col-12 col-md-5">
			<label for="tipo_pago_comun">Tipo de pago</label>
			<select class="form-control dont-erase" id="tipo_pago_comun">
				<option value="1">Efectivo</option>
				<option value="2">Transferencia</option>
				<option value="3">Pago Móvil</option>
				<option value="4">Divisa</option>
			</select>
		</div>
		<div class="col-12 col-md-5 d-none">
			<label>Datos del Tipo de cambio en divisa</label>
			<div><strong id="tipo_cambio_divisa_monto_to_show"></strong></div>
			<div><strong id="tipo_cambio_divisa_fecha_to_show"></strong></div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-5">
			<div class="row">
				<div class="col col-6">
					<label for="tipo_pago_comun-fecha">Fecha:</label>
					<input type="date" class="form-control" id="tipo_pago_comun-fecha" data-span="invalid-span-tipo_pago_comun-fecha">
					<span id="invalid-span-tipo_pago_comun-fecha" class="invalid-span text-danger"></span>
				</div>
				<div class="col d-none">
					<label for="tipo_pago_comun-hora" style="opacity: 0" class="no-select">l</label>
					<input value="00:00" type="time" class="form-control" id="tipo_pago_comun-hora" data-span="invalid-span-tipo_pago_comun-hora">
					<span id="invalid-span-tipo_pago_comun-hora" class="invalid-span text-danger"></span>
				</div>
			</div>
		</div>
		<div class="col-12 col-md">
			<label for="tipo_pago_comun-monto_total">Monto (Bs)</label>
			<input autocomplete="off" maxlength="29" type="text" class="form-control text-right" id="tipo_pago_comun-monto_total" data-span="invalid-span-tipo_pago_comun-monto_total">
			<span id="invalid-span-tipo_pago_comun-monto_total" class="invalid-span text-danger"></span>
		</div>
	</div>
	<hr>

	<!-- ignorar -->
		<div class="nav nav-tabs d-none" id="nav-tab" role="tablist">
			<a class="nav-item nav-link active" id="nav-tab_tipo_pago_comun_efectivo" data-toggle="tab" href="#tab_tipo_pago_comun_efectivo" role="tab">efectivo</a>
			<a class="nav-item nav-link" id="nav-tab_tipo_pago_comun_transferencia" data-toggle="tab" href="#tab_tipo_pago_comun_transferencia" role="tab">transferencia</a>
			<a class="nav-item nav-link" id="nav-tab_tipo_pago_comun_pago_movil" data-toggle="tab" href="#tab_tipo_pago_comun_pago_movil" role="tab">transferencia</a>
			<a class="nav-item nav-link" id="nav-tab_tipo_pago_comun_divisa" data-toggle="tab" href="#tab_tipo_pago_comun_divisa" role="tab">divisa</a>
		</div>
	<!-- ignorar -->

	<div class="tab-content">
		
		<div class="tab-pane fade show" id="tab_tipo_pago_comun_efectivo" role="tabpanel" aria-labelledby="tipo_pago_comun">
		</div>
		<div class="tab-pane fade show" id="tab_tipo_pago_comun_transferencia" role="tabpanel" aria-labelledby="tipo_pago_comun">
			<div class="row mt-2">
				<div class="col-md-5 col-12">
					<label for="tipo_pago_comun-cedula_transf">Cedula</label>
					<input type="text" class="form-control uppercase" id="tipo_pago_comun-cedula_transf" data-span="invalid-span-tipo_pago_comun-cedula_transf">
					<span id="invalid-span-tipo_pago_comun-cedula_transf" class="invalid-span text-danger"></span>
				</div>
				<div class="col-md-5 col-12">
					<label for="tipo_pago_comun-referencia_transf">Referencia</label>
					<input autocomplete="off" type="text" class="form-control" id="tipo_pago_comun-referencia_transf" data-span="invalid-span-tipo_pago_comun-referencia_transf">
					<span id="invalid-span-tipo_pago_comun-referencia_transf" class="invalid-span text-danger"></span>
				</div>
				<div class="col-md-5 col-12">
					<label for="tipo_pago_comun-banco_transf">Banco</label>
					<input autocomplete="off" type="text" class="form-control" id="tipo_pago_comun-banco_transf" data-span="invalid-span-tipo_pago_comun-banco_transf">
					<span id="invalid-span-tipo_pago_comun-banco_transf" class="invalid-span text-danger"></span>
				</div>
				
			</div>
		</div>

		<div class="tab-pane fade show" id="tab_tipo_pago_comun_divisa" role="tabpanel" aria-labelledby="tipo_pago_comun">
			<input type="hidden" name="" id="tipo_pago_comun-billetes_obj" class="d-none">
			<div class="row mt-2 justify-content-center">
				<div class="col-3">
					<label for="tipo_pago_comun-divisa_cantidad">Cantidad</label>
					<input autocomplete="off" type="number" class="form-control" id="tipo_pago_comun-divisa_cantidad" data-span="invalid-span-tipo_pago_comun-divisa_cantidad" min="0">
					<span id="invalid-span-tipo_pago_comun-divisa_cantidad" class="invalid-span text-danger"></span>
				</div>
			</div>
			<div class="fluid-container" id="tipo_pago_comun_divisa_campos">

				
			</div>
		</div>

		<div class="tab-pane fade show" id="tab_tipo_pago_comun_pago_movil" role="tabpanel" aria-labelledby="tipo_pago_comun">
			<div class="row mt-2">
				<div class="col-md-3 col-12">
					
					<label for="tipo_pago_comun-Cedula_pagoMovil">Cedula</label>
					<input autocomplete="off" type="text" class="form-control uppercase" id="tipo_pago_comun-Cedula_pagoMovil" data-span="invalid-span-tipo_pago_comun-Cedula_pagoMovil">
					<span id="invalid-span-tipo_pago_comun-Cedula_pagoMovil" class="invalid-span text-danger"></span>
					
				</div>
				<div class="col-md-3 col-12">
					
					<label for="tipo_pago_comun-Telefono_pagoMovil">Teléfono</label>
					<input autocomplete="off" type="text" class="form-control" id="tipo_pago_comun-Telefono_pagoMovil" data-span="invalid-span-tipo_pago_comun-Telefono_pagoMovil">
					<span id="invalid-span-tipo_pago_comun-Telefono_pagoMovil" class="invalid-span text-danger"></span>
					
				</div>
				<div class="col-md-3 col-12">
					
					<label for="tipo_pago_comun-Referencia_pagoMovil">Referencia</label>
					<input autocomplete="off" type="text" class="form-control" id="tipo_pago_comun-Referencia_pagoMovil" data-span="invalid-span-tipo_pago_comun-Referencia_pagoMovil">
					<span id="invalid-span-tipo_pago_comun-Referencia_pagoMovil" class="invalid-span text-danger"></span>
					
				</div>
				<div class="col-md-3 col-12">
					
					<label for="tipo_pago_comun-Banco_pagoMovil">Banco</label>
					<input autocomplete="off" type="text" class="form-control" id="tipo_pago_comun-Banco_pagoMovil" data-span="invalid-span-tipo_pago_comun-Banco_pagoMovil">
					<span id="invalid-span-tipo_pago_comun-Banco_pagoMovil" class="invalid-span text-danger"></span>
					
				</div>
				
			</div>
		</div>



	</div>
	<!-- <button type="button" onclick="objeto_tipo_pago_comun()">get pago</button> -->
</div>




<!-- <script type="text/javascript" src="js/tipo_pago_comun.js"></script> -->

















