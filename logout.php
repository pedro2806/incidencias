<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>MESS - Modulo de incidencias</title>
	

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

			// Llamar a la función antes de borrar las cookies
			leerCookiesAntesDeBorrar();
			//document.cookie = "antiguedad='.$antiguedad.';expires=" + new Date(Date.now() + 9600000).toUTCString() + ";SameSite=Lax;";
			document.cookie = "antiguedad=00; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			document.cookie = "diasD=00; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			document.cookie = "noEmpleado=00; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			document.cookie = "nombredelusuario=00; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			document.cookie = "rol=00; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			
			let cookieSesion = getCookie("SesionLogin");
			//alert("Cerrando sesión..." + cookieSesion);
			if (cookieSesion === "LoginMaster") {
				window.location.assign("../loginMaster/inicio");
			} else {
				window.location.assign("https://www.messbook.com.mx/incidencias");
			}
			
		});
		// Leer cookies antes de borrarlas
		function leerCookiesAntesDeBorrar() {
			let cookies = document.cookie.split(';');
			let cookiesObj = {};
			cookies.forEach(function(cookie) {
				let parts = cookie.split('=');
				let name = parts[0].trim();
				let value = parts[1] ? parts[1].trim() : '';
				cookiesObj[name] = value;
			});
			console.log("Cookies antes de borrar:", cookiesObj);
			return cookiesObj;
		}

		
		//Funcion para leer cookies
		function getCookie(name) {
			let value = "; " + document.cookie;
			let parts = value.split("; " + name + "=");
			if (parts.length === 2) return parts.pop().split(";").shift();
			return null; // Si no encuentra la cookie, retorna null
		}
	</script>

</head>
<body>
</body>