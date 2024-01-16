<?php require_once('comunes/head.php'); 
$morososData = getMorososData();
?>
<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?> 
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Estadisticas del sistema</h2>
			<hr />
		</div>


        <div class="container">
    <h2>Morosos</h2>
    <div class="chart-container">
        <canvas id="myChart" width="100" height="220"></canvas>
    </div>
</div>

<script>
    // Convierte los datos PHP a JavaScript
    var labels = <?php echo json_encode(array_keys($morososData)); ?>;
    var data = <?php echo json_encode(array_values($morososData)); ?>;

    var ctx = document.getElementById("myChart").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Morosos",
                    data: data,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 50,
            stepSize: 1,
                },
            },
        },
    });
</script>


	</div>
	<?php require_once('comunes/foot.php'); ?>
</body>
