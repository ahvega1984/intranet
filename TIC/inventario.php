<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

// IMPORTACION INVENTARIO TIC
if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])) {
    if (isset($_POST['submitImportacion'])) {
        if (isset($_FILES['archivo']) && ! empty($_FILES['archivo']["tmp_name"])) {
            
            $file = fopen($_FILES['archivo']["tmp_name"], "r") or die("Error: No ha sido posible abrir el archivo.");

            mysqli_query($db_con, "TRUNCATE TABLE `inventario_tic`");

            $numlinea = 0;
            while (!feof($file)) {
                $numlinea++;

                $linea = fgets($file);

                if ($numlinea > 8) {
                    $exp_linea = explode('|', $linea);

                    $numregistro    = utf8_encode(trim($exp_linea[0]));
                    $numserie       = utf8_encode(trim($exp_linea[1]));
                    $tipo           = utf8_encode(trim($exp_linea[2]));
                    $articulo       = trim($exp_linea[3]);
                    $proveedor      = trim($exp_linea[4]);
                    $expediente     = (trim($exp_linea[5]) == '') ? 'Sin expediente' : utf8_encode(trim($exp_linea[5]));
                    $procedencia    = utf8_encode(trim($exp_linea[6]));
                    $localizacion   = (trim($exp_linea[7]) == '') ? 'Sin asignar' : utf8_encode(trim($exp_linea[7]));
                    $adscripcion    = utf8_encode(trim($exp_linea[8]));
                    $exp_fechaalta  = explode('/', trim($exp_linea[9]));
                    $fechaalta      = $exp_fechaalta[2].'-'.$exp_fechaalta[1].'-'.$exp_fechaalta[0];
                    if ($exp_linea[10] == '') {
                        $fechabaja = '0000-00-00';
                    }
                    else {
                        $exp_fechabaja  = explode('/', trim($exp_linea[10]));
                        $fechabaja      = $exp_fechabaja[2].'-'.$exp_fechabaja[1].'-'.$exp_fechabaja[0];
                    }
                    $motivobaja     = utf8_encode(trim($exp_linea[11]));
                    $estado         = utf8_encode(trim($exp_linea[12]));
                    $descripcion    = utf8_encode(trim($exp_linea[13]));
                    $dotacionapae   = utf8_encode(trim($exp_linea[14]));

                    if ($numregistro != "") {
                        $result = mysqli_query($db_con, "INSERT INTO `inventario_tic` (`numregistro`, `numserie`, `tipo`, `articulo`, `proveedor`, `expediente`, `procedencia`, `localizacion`, `adscripcion`, `fechaalta`, `fechabaja`, `motivobaja`, `estado`, `descripcion`, `dotacionapae`, `marcadobaja`) VALUES ('$numregistro', '$numserie', '$tipo', '$articulo', '$proveedor', '$expediente', '$procedencia', '$localizacion', '$adscripcion', '$fechaalta', '$fechabaja', '$motivobaja', '$estado', '$descripcion', '$dotacionapae', 0)") or die ("Error: ". mysqli_error($db_con));
                    }
                    
                }
            }
            fclose($file);

        }
        else {
            $msg_error = true;
            $msg_error_text = "No ha seleccionado el archivo.";
        }

    }

    // MARCAR BAJA EN INVENTARIO SÉNECA
    if (isset($_POST['marcarBaja'])) {
        $numregistro = mysqli_real_escape_string($db_con, $_POST['numregistro']);
        $marcadobaja_actual = mysqli_real_escape_string($db_con, $_POST['marcadobaja']);

        if (! empty($numregistro)) {
            $marcadobaja = ($marcadobaja_actual == 1) ? 0 : 1;
            mysqli_query($db_con, "UPDATE `inventario_tic` SET `marcadobaja` = ".$marcadobaja." WHERE `numregistro` = '".$numregistro."' LIMIT 1") or die (mysqli_error($db_con));
        }

    }

    // GUARDAR OBSERVACIONES RECURSOS
    if (isset($_POST['guardarCambiosInformacion'])) {
        $numregistro = mysqli_real_escape_string($db_con, $_POST['numregistro']);
        $observaciones = mysqli_real_escape_string($db_con, trim($_POST['observaciones']));

        if (! empty($numregistro)) {
            mysqli_query($db_con, "UPDATE `inventario_tic` SET `observaciones` = '".$observaciones."' WHERE `numregistro` = '".$numregistro."' LIMIT 1") or die (mysqli_error($db_con));
        }
    }
}

$inventario = array();
$inventario_expediente = array();
$inventario_localizacion = array();
$inventario_estado = array();
$result = mysqli_query($db_con, "SELECT `numregistro`, `numserie`, `tipo`, `articulo`, `proveedor`, `expediente`, `procedencia`, `localizacion`, `adscripcion`, `fechaalta`, `fechabaja`, `motivobaja`, `estado`, `descripcion`, `dotacionapae`, `observaciones`, `marcadobaja` FROM `inventario_tic` ORDER BY fechaalta DESC");
while ($row = mysqli_fetch_array($result)) {
    array_push($inventario_expediente, $row['expediente']);
    array_push($inventario_localizacion, $row['localizacion']);
    array_push($inventario_estado, $row['estado']);

    // Aplicamos los filtros
    if (isset($_GET['expediente']) && in_array($_GET['expediente'], $inventario_expediente)) {
        $filtro_expediente = $_GET['expediente'];
        if ($row['expediente'] == $filtro_expediente) array_push($inventario, $row);
    }
    elseif (isset($_GET['localizacion']) && in_array($_GET['localizacion'], $inventario_localizacion)) {
        $filtro_localizacion = $_GET['localizacion'];
        if ($row['localizacion'] == $filtro_localizacion) array_push($inventario, $row);
    }
    elseif (isset($_GET['estado']) && in_array($_GET['estado'], $inventario_estado)) {
        $filtro_estado = $_GET['estado'];
        if ($row['estado'] == $filtro_estado) array_push($inventario, $row);
    }
    elseif (! isset($_GET['expediente']) && ! isset($_GET['localizacion']) && ! isset($_GET['estado'])) {
        array_push($inventario, $row);
    }
    
}

$inventario_expediente = array_unique($inventario_expediente);
$inventario_localizacion = array_unique($inventario_localizacion);
$inventario_estado = array_unique($inventario_estado);
asort($inventario_expediente);
asort($inventario_localizacion);
asort($inventario_estado);

include("../menu.php");
include("menu.php");
?>

    <style type="text/css">
    .font-80 > tbody > tr > td {
        font-size: 0.85em;
    }
    </style>

    <div class="container">

        <div class="page-header">
            <h2>Gestión TIC <small>Inventario de material TIC</small></h2>
        </div>

        <?php if (isset($msg_error) && $msg_error): ?>
        <div class="alert alert-danger">
            <?php echo $msg_error_text; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="hidden-print">
                    <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
                    <a href="#" class="btn btn-primary hidden-xs" data-toggle="modal" data-target="#modalImportacionInventarioTIC">Importar material TIC</a>
                    <?php endif; ?>

                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Localización: <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($inventario_localizacion as $localizacion): ?>
                            <li<?php echo (isset($_GET['localizacion']) && $_GET['localizacion'] == $localizacion) ? ' class="active"' : ''; ?>><a href="?localizacion=<?php echo $localizacion; ?>"><?php echo $localizacion; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Expediente: <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($inventario_expediente as $expediente): ?>
                            <li<?php echo (isset($_GET['expediente']) && $_GET['expediente'] == $expediente) ? ' class="active"' : ''; ?>><a href="?expediente=<?php echo $expediente; ?>"><?php echo $expediente; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Estado: <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($inventario_estado as $estado): ?>
                            <li<?php echo (isset($_GET['estado']) && $_GET['estado'] == $estado) ? ' class="active"' : ''; ?>><a href="?estado=<?php echo $estado; ?>"><?php echo $estado; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php if (isset($_GET['expediente']) || isset($_GET['localizacion']) || isset($_GET['estado'])): ?>
                    <a href="inventario.php" class="btn btn-default">Quitar filtro</a>
                    <?php endif; ?>
                </div>

                <br>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12">

                <table class="table table-condensed table-bordered table-striped font-80">
                    <thead>
                        <tr>
                            <th>Nº registro</th>
                            <th>Descripción</th>
                            <th class="hidden-xs" nowrap>Fecha alta</th>
                            <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
                            <th>Opciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventario as $item): ?>
                        <?php switch ($item['estado']) {
                            case 'En uso' : $label = 'label-success'; break;
                            case 'Disponible' : $label = 'label-success'; break;
                            case 'No disponible' : $label = 'label-danger'; break;
                            case 'En reparación' : $label = 'label-warning'; break;
                            case 'Propiedad del usuario' : $label = 'label-default'; break;
                        }
                        ?>
                        <tr<?php echo ($item['marcadobaja']) ? ' class="danger"' : ''; ?>>
                            <td nowrap>
                                <small><?php echo $item['numregistro']; ?></small><br>
                                <span class="label <?php echo $label; ?>"><?php echo $item['estado']; ?></span>
                            </td>
                            <td>
                                <strong><?php echo $item['descripcion']; ?></strong><br>
                                <?php echo ($item['expediente'] != "") ? '<small>Exp.: '.$item['expediente'].'</small><br>' : ''; ?>
                                <small>Ubicación: <?php echo $item['localizacion']; ?></small>
                            </td>
                            <td class="hidden-xs" nowrap><?php echo $item['fechaalta']; ?></td>
                            <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
                            <td class="text-right" nowrap>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#modalInformacion" data-numregistro="<?php echo $item['numregistro']; ?>" data-numserie="<?php echo $item['numserie']; ?>" data-tipo="<?php echo $item['tipo']; ?>" data-expediente="<?php echo $item['expediente']; ?>" data-procedencia="<?php echo $item['procedencia']; ?>" data-adscripcion="<?php echo $item['adscripcion']; ?>" data-localizacion="<?php echo $item['localizacion']; ?>" data-adscripcion="<?php echo $item['adscripcion']; ?>" data-fechaalta="<?php echo $item['fechaalta']; ?>" data-fechabaja="<?php echo $item['fechabaja']; ?>" data-motivobaja="<?php echo $item['motivobaja']; ?>" data-estado="<?php echo $item['estado']; ?>" data-descripcion="<?php echo $item['descripcion']; ?>" data-dotacionapae="<?php echo $item['dotacionapae']; ?>" data-observaciones="<?php echo $item['observaciones']; ?>" data-bs="tooltip" title="Ver información"><span class="fas fa-search fa-fw"></span></a>
                                <form action="" method="post" style="display: inline; margin: 0; padding: 0;">
                                    <input type="hidden" name="numregistro" value="<?php echo $item['numregistro']; ?>">
                                    <input type="hidden" name="marcadobaja" value="<?php echo $item['marcadobaja']; ?>">
                                    <button type="submit" class="btn btn-default" name="marcarBaja" data-bs="tooltip" title="<?php echo ($item['marcadobaja']) ? 'Desmarcar para dar de baja en Séneca' : 'Marcar para dar de baja en Séneca'; ?>"><?php echo ($item['marcadobaja']) ? '<span class="fas fa-undo fa-fw"></span>' : '<span class="fas fa-times fa-fw"></span>'; ?></button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

        </div>

    </div>
    
    <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
    <div class="modal fade" id="modalImportacionInventarioTIC" tabindex="-1" role="dialog" aria-labelledby="labelImportacionInventarioTIC">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="labelImportacionInventarioTIC">Importar inventario TIC</h4>
                    </div>
                    <div class="modal-body">
                        <div class="well">
                             <p>Para obtener el archivo RegNoEdiInvCen.txt debe dirigirse al apartado <strong>Centro</strong>, <strong>Equipamiento</strong>, <strong>Inventario</strong>, <strong>Material inventariado</strong>. Seleccione tipo <strong>Recursos TIC</strong>; subtipo <strong>Cualquiera</strong>; procedencia <strong>Cualquiera</strong> y estado <strong>Cualquiera</strong>. Pulse el botón <strong>Buscar</strong> y exporte los datos en formato <strong>Texto plano</strong>.</p>
                        </div>

                        <br>

                        <div class="form-group">
                            <label for="archivo">RegNoEdiInvCen.txt</label>
                            <input type="file" id="archivo" name="archivo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="submitImportacion" class="btn btn-primary">Importar</button>
                    </div>
                </form>
            </div>
         </div>
    </div>

    <div class="modal fade" id="modalInformacion" tabindex="-1" role="dialog" aria-labelledby="labelInformacion">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="labelInformacion">Información del recurso</h4>
                    </div>
                    <div class="modal-body">
                    
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="info-tipo" class="control-label">Tipo:</label>
                                        <input type="text" class="form-control" id="info-tipo" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="info-descripcion" class="control-label">Descripción:</label>
                                        <input type="text" class="form-control" id="info-descripcion" readonly>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <hr>

                        <fieldset>
                            <legend>Datos de alta</legend>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="info-numregistro" class="control-label">Nº registro general:</label>
                                        <input type="hidden" id="numregistro" name="numregistro">
                                        <input type="text" class="form-control" id="info-numregistro" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="info-procedencia" class="control-label">Procedencia:</label>
                                        <input type="text" class="form-control" id="info-procedencia" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="info-fechaalta" class="control-label">Fecha de alta:</label>
                                        <input type="text" class="form-control" id="info-fechaalta" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="info-numserie" class="control-label">Nº de serie:</label>
                                        <input type="text" class="form-control" id="info-numserie" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="info-expediente" class="control-label">Nº de expediente:</label>
                                        <input type="text" class="form-control" id="info-expediente" readonly>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                        <hr>

                        <fieldset>
                            <legend>Datos de su ubicación actual</legend>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="info-localizacion" class="control-label">Ubicación:</label>
                                        <input type="text" class="form-control" id="info-localizacion" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="info-adscripcion" class="control-label">Dependencia de adscripción:</label>
                                        <input type="text" class="form-control" id="info-adscripcion" readonly>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                        <hr>

                        <fieldset>
                            <legend>Datos de la baja / Retirada del recurso</legend>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="info-fechabaja" class="control-label">Fecha de baja:</label>
                                        <input type="text" class="form-control" id="info-fechabaja" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="info-motivobaja" class="control-label">Motivo:</label>
                                        <input type="text" class="form-control" id="info-motivobaja" readonly>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <hr>
                        
                        <fieldset>
                            <legend>Observaciones</legend>

                            <div class="form-group">
                                <label for="info-observaciones" class="control-label">Observaciones:</label>
                                <textarea type="text" class="form-control" id="info-observaciones" name="observaciones" rows="5"></textarea>
                            </div>
                        </fieldset>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="guardarCambiosInformacion" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php include("../pie.php"); ?>
    <script>
    $('#modalInformacion').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var infotipo = button.data('tipo')
    var infodescripcion = button.data('descripcion')
    var infonumregistro = button.data('numregistro')
    var infoprocedencia = button.data('procedencia')
    var infofechaalta = button.data('fechaalta')
    var infonumserie = button.data('numserie')
    var infoexpediente = button.data('expediente')
    var infolocalizacion = button.data('localizacion')
    var infoadscripcion = button.data('adscripcion')
    var infofechabaja = button.data('fechabaja')
    var infomotivobaja = button.data('motivobaja')
    var infoobservaciones = button.data('observaciones')
    var modal = $(this)

    if (infofechabaja == '0000-00-00') infofechabaja = ''

    modal.find('.modal-body #info-tipo').val(infotipo)
    modal.find('.modal-body #info-descripcion').val(infodescripcion)
    modal.find('.modal-body #numregistro').val(infonumregistro)
    modal.find('.modal-body #info-numregistro').val(infonumregistro)
    modal.find('.modal-body #info-procedencia').val(infoprocedencia)
    modal.find('.modal-body #info-fechaalta').val(infofechaalta)
    modal.find('.modal-body #info-numserie').val(infonumserie)
    modal.find('.modal-body #info-expediente').val(infoexpediente)
    modal.find('.modal-body #info-localizacion').val(infolocalizacion)
    modal.find('.modal-body #info-adscripcion').val(infoadscripcion)
    modal.find('.modal-body #info-fechabaja').val(infofechabaja)
    modal.find('.modal-body #info-motivobaja').val(infomotivobaja)
    modal.find('.modal-body #info-observaciones').val(infoobservaciones)
    })
    </script>

</body>
</html>