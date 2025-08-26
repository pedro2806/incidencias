<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>MESS - Modulo de incidencias</title>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
	<script type="text/javascript" class="init">
	
$(document).ready(function() {
	// List of cookies to delete
	var cookies = [
		"antiguedad",
		"diasD",
		"noEmpleado",
		"nombredelusuario",
		"rol"
	];

	// Delete cookies
	cookies.forEach(function(cookie) {
		document.cookie = cookie + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/incidencias;";
	});

	// Function to check if cookies are deleted
	function areCookiesDeleted() {
		var allDeleted = true;
		cookies.forEach(function(cookie) {
			if (document.cookie.split('; ').find(row => row.startsWith(cookie + '='))) {
				allDeleted = false;
			}
		});
		return allDeleted;
	}

	// Wait until cookies are deleted, then redirect
	function tryRedirect() {
		if (areCookiesDeleted()) {
			window.location.assign("../loginMaster/inicio.php");
		} else {
			setTimeout(tryRedirect, 100);
		}
	}

	tryRedirect();
});


	</script>

</head>
<body>
</body>