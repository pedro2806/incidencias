<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE = edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RR HH</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family = Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php

        include 'menu.php';
        ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                //session_start();
                //if(isset($_SESSION['nombredelusuario'])){}

                include 'encabezado.php';
                ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php
                    include 'conteo.php';
                    ?>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12">
                            <div class="card shadow">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: center">
                                            <img class="sidebar-card-illustration" src="img/MESS_05_Imagotipo_1.png" width="160">
                                        </div>
                                        <div class="col-xl-4">
                                            <center>
                                                <p class="fs-4"><b>SOLICITUD DE INCIDENCIAS</b></p>
                                            </center>
                                        </div>
                                        <div class="col-xl-4" style="text-align: center">
                                            <b>Recursos Humanos - </b>
                                            <b><?php $hoy = date("d-m-Y");
                                                print_r($hoy); ?></b>
                                        </div>
                                    </div>
                                    <form method="POST"><!--action="procesar_solicitud.php"-->
                                        <div class="row card-footer border-left-primary">
                                            <!--<div class = "col-xl-1"></div>-->
                                            <div class="col-sm-1 mb-0">
                                                <b>Solicita:</b>
                                            </div>
                                            <div class="col-sm-3 mb-0">
                                                <h5 class="text-primary" id="NSolicita" name="NSolicita"></h5>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <div id="Divsolicita" name="Divsolicita">
                                                    <select id="solicita" name="solicita" class="form-select">
                                                        <option value="">Selecciona...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="DivsolicitaMss" name="DivsolicitaMss">
                                                    <div class="alert alert-info" role="alert" style="font-size: 12px;">
                                                        Si deseas realizar la solicitud en nombre de otra persona, por favor selecciona su nombre.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row card-footer border-left-primary">
                                            <!--<div class = "col-xl-1"></div>-->
                                            <div class="col-xl-8 mb-0">
                                                <b>Por favor selecciona que tipo de incidencia es la que se autorizará:</b>
                                            </div>
                                        </div>
                                        <div class="row card-footer border-left-primary">
                                            <div class="col-xl-1"></div>

                                            <!-- Primera opción: Vacaciones -->
                                            <div class="col-xl-3" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti1" onchange="tIncidencia(1);" value="1" required checked>
                                                    <label class="form-check-label" for="ti1">
                                                        <b>Vacaciones</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Segunda opción: Permiso sin goce -->
                                            <div class="col-xl-3" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti2" onchange="tIncidencia(2);" value="2" required>
                                                    <label class="form-check-label" for="ti2">
                                                        <b>Permiso sin goce</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Tercera opción: Permiso con goce -->
                                            <div class="col-xl-4" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti3" onchange="tIncidencia(3);" value="3" required>
                                                    <label class="form-check-label" for="ti3">
                                                        <b>Permiso con goce. (ver anexo: Reglamento)</b>
                                                        <input type="hidden" name="opIncidencia" id="opIncidencia" value="1">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <!--<div class = "col-xl-1"></div>-->
                                            <div class="col-xl-12"><br>
                                                <b>Favor de tomar nota de los días que el empleado estará fuera de Jornada Laboral (aplica para permiso sin goce, con goce y vacaciones):</b>
                                            </div>
                                        </div>
                                        <!--
                                            <div class = "row card-footer border-left-primary">
                                                <div class = "col-xl-1"></div>
                                                <div class = "col-xl-3">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">Fecha de incio</label>
                                                        <input type="date" class="form-control" id="fechaInicial" name = "fechaInicial" onchange="diasEntreFechas();"  required>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-3">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">Fecha de termino</label>
                                                        <input type="date" class="form-control" id="fechaFinal" name  = "fechaFinal" onchange="diasEntreFechas();" required>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-2">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">No de días</label>
                                                        <input type="number" class="form-control" id="noDias" name ="noDias">
                                                    </div>  
                                                </div>
                                            </div>
                                            -->
                                        <div class="row card-footer border-left-primary dynamic-row">
                                            <div class="col-sm-10">
                                                <div id="renglones-container">
                                                    <!-- Renglón inicial que no se puede eliminar -->
                                                    <div class="row" id="renglon-1">
                                                        <div class="col-sm-1"></div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-1">
                                                                <label for="fechaInicial-1" class="form-label">Fecha de inicio</label>
                                                                <input type="date" class="form-control" id="fechaInicial-1" name="fechaInicial[]" onchange="diasEntreFechas(1);" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-1">
                                                                <label for="fechaFinal-1" class="form-label">Fecha de término</label>
                                                                <input type="date" class="form-control" id="fechaFinal-1" name="fechaFinal[]" onchange="diasEntreFechas(1);" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="mb-1">
                                                                <label for="noDias-1" class="form-label">No de días</label>
                                                                <input type="number" class="form-control" id="noDias-1" name="noDias[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="mb-1">
                                                    <label class="form-label">Opciones</label><br>
                                                    <button type="button" class="btn btn-success" onclick="agregarRenglon()"><i class="fas fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger" onclick="eliminarUltimoRenglon()"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row card-header border-left-primary">
                                            <!--<div class = "col-xl-1"></div>-->
                                            <div class="col-xl-6">
                                                <div class="mb-0">
                                                    <b for="exampleFormControlTextarea1" class="form-label">Indicar fechas cuando los días no sean un periodo corrido</b>
                                                    <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="mb-0">
                                                    <b for="exampleFormControlTextarea1" class="form-label">Comentarios</b>
                                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  card-header border-left-success">
                                            <div class="col-xl-3"></div>
                                            <div class="col-xl-6">
                                                <center>
                                                    <?php
                                                    $noEmp = $_COOKIE['noEmpleado'];

                                                    $Qjefe = "SELECT us.nombre AS jefe 
                                                                        FROM usuarios a
                                                                        LEFT JOIN usuarios us ON a.jefe = us.noEmpleado
                                                                        WHERE a.noEmpleado = $noEmp";
                                                    $resJefe = mysqli_query($conn, $Qjefe) or die(mysqli_error($conn));

                                                    while ($row = mysqli_fetch_array($resJefe)) {
                                                        $jefe = $row["jefe"];
                                                    }
                                                    echo '<h4 class ="text-success text-xl">Autoriza: ' . $jefe . '    </h4> <br>  ';
                                                    ?>

                                                    <button id="btnSolicitar" type="button" class="btn btn-success" onclick="generarSolicitud()">Solicitar</button><br>
                                                    <p id="mensaje" class="badge text-bg-primary"></p>
                                                </center>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">¿Estas seguro?</div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    <script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            empleadoSolicita();
            validaRol();
            validarAntiguedad();
            $('#solicita').select2();
        });

        let renglonCounter = 1;

        function agregarRenglon() {
            renglonCounter++;

            const nuevoRenglon = document.createElement('div');
            nuevoRenglon.classList.add('row');
            nuevoRenglon.id = `renglon-${renglonCounter}`;

            nuevoRenglon.innerHTML = `
                <div class="col-sm-1"></div>
                <div class="col-sm-4">
                    <div class="mb-1">
                        <input type="date" class="form-control" id="fechaInicial-${renglonCounter}" name="fechaInicial[]" onchange="diasEntreFechas(${renglonCounter});" required>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-1">
                        <input type="date" class="form-control" id="fechaFinal-${renglonCounter}" name="fechaFinal[]" onchange="diasEntreFechas(${renglonCounter});" required>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-1">
                        <input type="number" class="form-control" id="noDias-${renglonCounter}" name="noDias[]">
                    </div>
                </div>
            `;

            document.getElementById('renglones-container').appendChild(nuevoRenglon);
        }

        function eliminarUltimoRenglon() {
            if (renglonCounter > 1) {
                // Obtener el último renglón añadido
                const ultimoRenglon = document.getElementById(`renglon-${renglonCounter}`);

                if (ultimoRenglon) {
                    // Eliminar el último renglón si existe
                    ultimoRenglon.remove();
                    renglonCounter--; // Disminuir el contador de renglones
                }
            } else {
                alert("Se tiene que capturar al menos un periodo.");
            }
        }


        var tIncidencia = function(ti) {
            $('#opIncidencia').val(ti);
        }

        var diasEntreFechas = function(idRenglon) {
            // Seleccionar los campos específicos usando el ID del renglón
            var fechaInicial = $(`#fechaInicial-${idRenglon}`).val();
            var fechaFinal = $(`#fechaFinal-${idRenglon}`).val();

            // Verificar que las fechas no estén vacías
            if (!fechaInicial || !fechaFinal) {
                return;
            }

            var fechaDesde = new Date(fechaInicial);
            var fechaHasta = new Date(fechaFinal);
            var contador = 0;

            // Calcular los días excluyendo fines de semana
            while (fechaDesde <= fechaHasta) {
                var dia = fechaDesde.getDay(); // Obtener el día de la semana (0=Domingo, 6=Sábado)

                // Si no es fin de semana (Domingo o Sábado), sumar al contador
                if (dia != 0 && dia != 6) {
                    contador++;
                }
                fechaDesde.setDate(fechaDesde.getDate() + 1); // Avanzar al siguiente día
            }

            // Asignar el valor calculado en el campo correspondiente de "no de días"
            $(`#noDias-${idRenglon}`).val(contador);
        }

        function empleadoSolicita() {
            var opcion = "empleadoSolicita";
            let cookieRol = getCookie('rol');
            let cookieNoEmpleado = getCookie('noEmpleado');
            $.ajax({
                url: 'funciones_select.php', // La URL del script PHP que obtendra los datos
                method: 'GET',
                dataType: 'json',
                data: {
                    opcion,
                    cookieRol,
                    cookieNoEmpleado
                },
                success: function(data) {
                    var select = $('#solicita');
                    data.forEach(function(usuarios) {
                        var option = $('<option></option>').attr('value', usuarios.noEmpleado).text(usuarios.nombre);
                        select.append(option);
                    });
                    seleccionaUsuario();

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: "La solicitúd no se pudo procesar!",
                        icon: "error",
                        draggable: true
                    });

                }
            });

        }

        function generarSolicitud() {
            opIncidencia = $('#opIncidencia').val();
            notas = $('#notas').val();
            comentarios = $('#comentarios').val();
            solicita = $('#solicita').val();
            cuantos = renglonCounter;
            accion = 'agregaSolicitud';
            // Crear un array para almacenar los datos
            var periodos = [];

            // Recorrer cada fila dinámica
            $('.dynamic-row .row').each(function(index, row) {
                var fechaInicial = $(row).find('input[name="fechaInicial[]"]').val();
                var fechaFinal = $(row).find('input[name="fechaFinal[]"]').val();
                var noDias = $(row).find('input[name="noDias[]"]').val();

                // Agregar los valores de la fila al array
                periodos.push({
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal,
                    noDias: noDias
                });
            });

            if (opIncidencia == '' || opIncidencia == null) {
                Swal.fire({
                    title: "Es necesario seleccionar el tipo de incidencia!",
                    icon: "warning",
                    draggable: true
                });
            } else {

                $.ajax({
                    url: 'procesar_solicitud.php', // La URL del script PHP que obtendra los datos
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        opIncidencia,
                        notas,
                        comentarios,
                        solicita,
                        cuantos,
                        accion,
                        periodos
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "La solicitúd se proceso son éxito!",
                            icon: "success",
                            draggable: true
                        });
                        //enviaNotificacion(solicita);
                        window.location.href = 'solicitudestatus';
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: "La solicitúd no se pudo procesar!",
                            icon: "error",
                            draggable: true
                        });

                    }
                });
            }
        }

        function enviaNotificacion() {
            $.ajax({
                url: 'enviaNotificacion.php', // La URL del script PHP que obtendra los datos
                method: 'POST',
                dataType: 'json',
                data: {
                    solicita
                },
                success: function(data) {
                    /*Swal.fire({
                      title: "Se notifico de tu solicitud",
                      icon: "info",
                      draggable: true
                    });*/
                    //window.location.href = 'solicitudestatus';
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    /*Swal.fire({
                      title: "Error al notificar la solicitud!",
                      icon: "error",
                      draggable: true
                    });*/

                }
            });
        }

        function seleccionaUsuario() {
            let cookieValue = getCookie('noEmpleado');

            if (cookieValue) {
                let selectElement = document.getElementById('solicita');
                selectElement.value = cookieValue;
                selectElement.options[selectElement.selectedIndex].setAttribute('selected', true);
            }
        }

        function validaRol() {

            let cookieRol = getCookie('rol');
            let nombre = getCookie('nombredelusuario');
            etiqueta = document.getElementById('NSolicita');
            etiqueta.style.display = 'True'; // Ocultar select
            etiqueta.innerHTML = nombre;

            if (cookieRol == 1) {
                let selectElement = document.getElementById('Divsolicita');
                selectElement.style.display = 'none';
                let selectElementM = document.getElementById('DivsolicitaMss');
                selectElementM.style.display = 'none';

                //selectElement.disabled = true;
            }

        }

        function validarAntiguedad() {
            let antiguedad = getCookie('antiguedad');

            if (antiguedad < 1) {
                $("#btnSolicitar").prop("disabled", true);
                $("#mensaje").text("No puedes solicitar vacaciones hasta tener un año de antigüedad.");
            } else {
                $("#btnSolicitar").prop("disabled", false);
                $("#mensaje").text("MESS 2025");
            }
        }

        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();

        }
    </script>
</body>

</html>