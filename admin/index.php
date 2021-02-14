<?php
	session_start();
	if (!isset($_SESSION['Username'])) {
		header("Location: login.php");
	}
	include "init.php";
	include $tpl . "header.php";
	include $tpl . "nav.php";
?>
<?php
	echo "Hello In Index Page";
?>
<?php
	include $tpl . "footer.php";
?>