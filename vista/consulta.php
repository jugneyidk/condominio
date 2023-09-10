<?php require_once('comunes/head.php'); ?>
<link rel="stylesheet" type="text/css" href="css/consulta.css">

<body class="d-flex flex-column justify-content-center">
    <?php require_once('comunes/carga.php'); ?>
    <section class="body">
        <div class="container">
            <div class="px-3 px-sm-0 login-box mx-auto d-flex flex-column justify-content-center">
                <div class="row">
                    <div class="col-md-8">
                        <div class="logo font-weight-bold mb-5">
                            <span class="">Condominio</span>
                            <span class="logo-font d-block">José María Vargas</span>
                        </div>
                        <span class="h3 header-title d-block text-center">Consulta tu deuda</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <br>
                        
                        <form class="login-form" id="f" method="post">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" placeholder="Correo" id="usuario" name="usuario">
                                <span id="susuario" class="text-danger espacio-vacio"></span>
                            </div>
                            <div class="form-group mb-3">
                                <input type="Password" class="form-control" placeholder="Contraseña(Cedula)" id="clave" name="clave">
                                <span id="sclave" class="text-danger espacio-vacio"></span>
                            </div>
                            <div class="form-group">
                                <button type="button" id="entrar" class="btn btn-primary btn-block">CONSULTAR</button>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-dark btn-block" href="./">Regresar</a>
                            </div>
                            <div class="form-group">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/carga.js"></script>
    <script src="js/consulta.js"></script>
    <?php require_once('comunes/modal.php'); ?>
</body>

</html>