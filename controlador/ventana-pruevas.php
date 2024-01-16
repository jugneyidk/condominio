<?php 
if (is_file("vista/" . $p . ".php")) {
	if (!empty($_POST)) {
		
		exit;
	}
		require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
?>