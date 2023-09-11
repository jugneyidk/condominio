
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .balance {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
    <?php require_once('comunes/head.php'); ?>
<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Balance condominio</h2>
			<hr />
		</div>
</div>
 <div class="container">
        <h1>Saldo</h1>
        <?php
        // Simula un saldo ficticio
        $balance = 1000.50;
        ?>
        <div class="balance">
            Tu saldo actual es: $<?php echo number_format($balance, 2); ?>
        </div>
    </div>



	</div>
	<?php require_once('comunes/foot.php'); ?>
</body>
