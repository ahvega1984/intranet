<?php
require('../../../bootstrap.php');

error_reporting(E_ALL);

function getBrowser($u_agent) {
    if (empty($u_agent)) {
        $u_agent = 'Agente de usuario no detectado';
    }
    $bname = 'Navegador desconocido';
    $bversion= "";
    $ub = "Navegador desconocido";
    $platform = 'Dispositivo desconocido';
    $pname = "";
    $pversion= "";

    $u_agent = str_replace('; es-es', '', $u_agent);
    $u_agent = str_replace('; en-us', '', $u_agent);
    $u_agent = str_replace('; en-uk', '', $u_agent);
    
    // First get the platform?
    if (preg_match('/android/i', $u_agent)) {
        $platform_name = 'Android';
        $pname = 'Android';
    } elseif (preg_match('/ubuntu/i', $u_agent)) {
        $platform = 'Ubuntu / Guadalinex';
        $pname = 'Ubuntu';
    } elseif (preg_match('/Linux Mint/i', $u_agent)) {
        $platform = 'Linux Mint';
        $pname = 'Linux Mint';
    } elseif (preg_match('/x11; linux/i', $u_agent)) {
        $platform = 'GNU/Linux';
        $pname = 'GNU/Linux';
    } elseif (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
        $pname = 'Linux';
    } elseif (preg_match('/iPhone/i', $u_agent)) {
        $platform = 'iPhone iOS';
        $pname = 'iPhone OS';
    } elseif (preg_match('/iPad/i', $u_agent)) {
        $platform = 'iPad iOS';
        $pname = 'iPad; CPU OS';
    } elseif (preg_match('/mac os x 10_13|mac os x 10_12/i', $u_agent)) {
        $platform = 'macOS';
        $pname = 'Mac OS X';
    } elseif (preg_match('/mac os x 10_11|mac os x 10_10|mac os x 10_9/i', $u_agent)) {
        $platform = 'OS X';
        $pname = 'Mac OS X';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac OS X';
        $pname = 'Mac OS X';
    } elseif (preg_match('/windows nt 10/i', $u_agent)) {
        $platform = 'Windows 10';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.3/i', $u_agent)) {
        $platform = 'Windows 8.1';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.2/i', $u_agent)) {
        $platform = 'Windows 8';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.1/i', $u_agent)) {
        $platform = 'Windows 7';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.0/i', $u_agent)) {
        $platform = 'Windows Vista';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 5.1/i', $u_agent)) {
        $platform = 'Windows XP';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 5.0/i', $u_agent)) {
        $platform = 'Windows 2000';
        $pname = 'Windows';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
        $pname = 'Windows';
    }

    if ($pname != "" && $pname != 'Windows') {
        // finally get the correct version number
        $known = array($pname, $ub, 'other');
        if ($pname == 'Android') {
            $pattern = '#(?<platform>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|0-9_|a-zA-Z.|a-zA-Z_]*;[0-9]*([a-zA-Z]*[-| ])*[a-zA-Z]*[-| ]*[0-9]*[-]*[a-zA-Z]*[-]*[0-9]*)#';
        }
        else {
            $pattern = '#(?<platform>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|0-9_|a-zA-Z.|a-zA-Z_]*)#';
        }
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['platform']);
        if ($i > 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $pversion= str_replace('_', '.', $matches['version'][0]);
            } else {
                $pversion= str_replace('_', '.', $matches['version'][1]);
            }
        } elseif ($i == 1) {
            $pversion= str_replace('_', '.', $matches['version'][0]);
        }
        // check if we have a number
        if ($pversion==null || $pversion=="") {
            $pversion="";
        }
        elseif ($pname == 'Android') {
            $pversion = str_replace(' es-es; ', '', $pversion);
            $exp_pversion = explode(';', $pversion);
            $platform = ltrim(trim($exp_pversion[1]).' - Android', ' - ');
            $pversion = trim($exp_pversion[0]);
        }
    }
    

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Safari';
        $ub = "Safari";
    } elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i > 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $bversion= $matches['version'][0];
        } else {
            $bversion= $matches['version'][1];
        }
    } elseif ($i == 1) {
        $bversion= $matches['version'][0];
    }
    // check if we have a number
    if ($bversion==null || $bversion=="") {$bversion="";}
    return array(
    'userAgent'         => $u_agent,
    'browser_name'      => $bname,
    'browser_version'   => $bversion,
    'platform_name'     => $pname,
    'platform'          => $platform,
    'platform_version'  => $pversion,
    'pattern'           => $pattern
    );
}

include("../../../menu.php"); 
?>
	
	<div class="container">

		<div class="page-header">
			<h2 class="page-title">Accesos a la Intranet <small>Dónde iniciaste sesión</small></h2>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				
				<table class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Últimos accesos</th>
                        </tr>
                    </thead>
					<tbody>
                        <?php $result = mysqli_query($db_con, "SELECT id, fecha, ip, useragent FROM reg_intranet WHERE profesor = '".$_SESSION['ide']."' ORDER BY fecha DESC LIMIT 8"); ?>
                        <?php while ($row = mysqli_fetch_array($result)): ?>
                        <?php $u_agent = getBrowser($row['useragent']); ?>
                        <?php switch ($u_agent['platform_name']) {
                            case 'Windows 10': 
                            case 'Windows 8.1': 
                            case 'Windows 8': 
                            case 'Windows 7': 
                            case 'Windows Vista': 
                            case 'Windows XP': 
                            case 'Windows 2000': 
                            case 'Windows':
                                $icon1 = 'fa-desktop';
                                $icon2 = 'fa-windows';
                                break;
                            
                            case 'Ubuntu':
                            case 'Linux Mint':
                            case 'GNU/Linux':
                            case 'Linux':
                                $icon1 = 'fa-desktop';
                                $icon2 = 'fa-linux';
                                break;

                            case 'Android':
                                $icon1 = 'fa-mobile';
                                $icon2 = 'fa-android';
                                break;

                            case 'macOS': 
                            case 'OS X': 
                            case 'Mac OS X': 
                                $icon1 = 'fa-desktop';
                                $icon2 = 'fa-apple';
                                break;
                            
                            case 'iPhone OS':  
                                $icon1 = 'fa-mobile';
                                $icon2 = 'fa-apple';
                                break;
                            
                            case 'iPad; CPU OS':  
                                $icon1 = 'fa-tablet';
                                $icon2 = 'fa-apple';
                                break;
                            
                            default:
                                $icon1 = 'fa-dot-circle-o';
                                $icon2 = '';
                                break;
                        }
                        ?>
                        <?php $meta_geoip = @unserialize(file_get_contents('http://ip-api.com/php/'.$row['ip'])); ?>
                        <?php $geoip = (isset($meta_geoip['city']) && $meta_geoip['country']) ? ltrim($meta_geoip['city'].', '.$meta_geoip['country'], ', ') : 'Localización desconocida'; ?>
                        <tr>
                            <td>
                                <div class="col-xs-2 col-md-1 text-center float-left">
                                    <span class="fa-stack fa-lg" style="margin-top: 10px;">
                                        <i class="fa <?php echo $icon1; ?> fa-stack-2x" style="font-size: 3em;"></i>
                                        <i class="fa <?php echo $icon2; ?> fa-stack-1x" style="<?php echo ($icon1 == 'fa-desktop') ? 'font-size: 0.65em; margin-top: 0px; margin-left: 12px;' : 'font-size: 0.55em; margin-top: 8px;'; ?>"></i>
                                    </span>
                                </div>
                                 <div class="col-xs-10 col-md-11 float-left">
                                    <?php if ($row['id'] != $_SESSION['id_pag']): ?>
                                    <div class="pull-right" style="margin-top: 20px;">
                                        <div class="btn-group">
                                        <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="fa fa-ellipsis-v fa-fw fa-lg"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="#" data-toggle="modal" data-target="#modal-<?php echo $row['id']; ?>">No he sido yo</a></li>
                                        </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="modal fade" id="modal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">¿Fuiste tú?</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Si no fuiste tú quien inició sesión, podemos guiarte por algunos pasos para proteger tu cuenta.</p>

                                                    <h5><strong>Estas son algunas de las acciones que se realizó:</strong></h5>
                                                    <?php $result_historico = mysqli_query($db_con, "SELECT DISTINCT pagina FROM reg_paginas WHERE id_reg = '".$row['id']."' ORDER BY id ASC LIMIT 10"); ?>
                                                    <?php if (mysqli_num_rows($result_historico)): ?>
                                                    <ul>
                                                    <?php while ($row_historico = mysqli_fetch_array($result_historico)): ?>
                                                    
                                                    <?php
                                                        // Página principal
                                                        if (stristr($row_historico['pagina'], 'index.php') == true) {
                                                            echo '<li>Consulta la página principal de la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], '?mes=') == true) {
                                                            echo '<li>Consulta las actividades del calendario</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'estadisticas/estadisticas.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'ajax_alumnos.php') == true) {
                                                            // Nada aquí...
                                                        }

                                                        // Documentos
                                                        elseif (stristr($row_historico['pagina'], 'upload/') == true) {
                                                            echo '<li>Acceso a documentos públicos del Centro</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'upload/index.php?index=privado') == true) {
                                                            echo '<li>Acceso a documentos personales</li>';
                                                        }
                                                        

                                                        // Mensajes
                                                        elseif (stristr($row_historico['pagina'], 'admin/mensajes/post_verifica.php') == true) {
                                                            echo '<li>Lectura de uno o varios mensajes desde la página principal</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/mensajes/mensaje.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Lectura de un mensaje</a></li>';
                                                        } 
                                                        elseif (stristr($row_historico['pagina'], 'admin/mensajes/redactar.php?profes=1') == true) {
                                                            echo '<li>Redacción de respuesta a un mensaje</li>';
                                                        } 
                                                        elseif (stristr($row_historico['pagina'], 'admin/mensajes/redactar.php') == true) {
                                                            echo '<li>Redacción un mensaje</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/mensajes/') == true) {
                                                            echo '<li>Consulta de los mensajes recibidos</li>';
                                                        } 

                                                        // Consultas 
                                                        elseif (stristr($row_historico['pagina'], 'admin/datos/datos.php?seleccionado=1&alumno=') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de datos de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/datos/cdatos.php') == true) {
                                                            echo '<li>Consulta de datos de alumnos o grupos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/ccursos.php') == true) {
                                                            echo '<li>Consulta de listados de alumnos o grupos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/listados.php') == true) {
                                                            echo '<li>Consulta de listados de alumnos o grupos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/informes/cinforme.php') == true) {
                                                            echo '<li>Consulta de informe de un alumno</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/chorarios.php') == true) {
                                                            echo '<li>Consulta de horarios de grupos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/aulas_libres.php') == true) {
                                                            echo '<li>Consulta de aulas libres</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/profes.php') == true) {
                                                            echo '<li>Consulta de horarios de profesores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/hor_aulas.php?aula=') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de horario de un aula</a></li>';
                                                        }

                                                        // Módulo de tutoría
                                                        elseif (stristr($row_historico['pagina'], 'admin/tutoria/tutores.php') == true) {
                                                            echo '<li>Acceso a la página de Control de Tutorías</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tutoria/consulta_fotografias.php') == true) {
                                                            echo '<li>Consulta o modificación de la foto de los alumnos</li>';
                                                        }

                                                        // Actas
                                                        elseif (stristr($row_historico['pagina'], 'admin/departamento/actas/administracion.php') == true) {
                                                            echo '<li>Consulta o modificación de la configuración de las actas de Departamento</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/evaluaciones/actas.php') == true) {
                                                            echo '<li>Consulta o redacción de un acta de evaluación</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/evaluaciones/actas.php?id=') == true) {
                                                            echo '<li>Modificación de un acta de evaluación</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/evaluaciones/imprimir.php?id=') == true) {
                                                            echo '<li>Consulta o impresión de un acta de evaluación</li>';
                                                        }

                                                        // Cuaderno
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/c_nota.php') == true) {
                                                            echo '<li>Creación de una columna en el cuaderno de notas</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/n_col.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/n_col.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/editar.php') == true) {
                                                            echo '<li>Operación con columnas del cuaderno de notas</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/orden.php') == true) {
                                                            echo '<li>Cambio de orden de las columnas del cuaderno de notas</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno/informe.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta informe de un alumno desde cuaderno de notas<a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'cuaderno.php') == true) {
                                                            echo '<li>Consulta de cuaderno de notas</li>';
                                                        }

                                                        // Intervenciones
                                                        elseif (stristr($row_historico['pagina'], 'admin/jefatura/index.php') == true) {  
                                                            echo '<li>Consulta o registro de una intervención a un alumno</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/jefatura/profesores.php') == true) {  
                                                            echo '<li>Consulta o registro de una intervención a un profesor</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tutoria/intervencion.php') == true) {  
                                                            echo '<li>Consulta o registro de una intervención a un alumno</li>';
                                                        }

                                                        // Informes
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/activar.php') == true) {
                                                            echo '<li>Activación de un informe de tutoría</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/infotut.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/informar.php') == true) {
                                                            echo '<li>Se rellenó un informe de tutoría</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/imprimir.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Impresión de informe de tutoría de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/infocompleto.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de informe de tutoría de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/infotutoria/buscar.php') == true) {
                                                            echo '<li>Búsqueda de un informe de tutoría</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/activar.php') == true) {
                                                            echo '<li>Activación de un informe de tareas</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/infotut.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/informar.php') == true) {
                                                            echo '<li>Se rellenó un informe de tareas</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/imprimir.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Impresión de informe de tareas de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/infocompleto.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de informe de tareas de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/tareas/buscar.php') == true) {
                                                            echo '<li>Búsqueda de un informe de tareas</li>';
                                                        }

                                                        // Asistencia
                                                        elseif (stristr($row_historico['pagina'], 'faltas/poner_falta.php') == true) {
                                                            echo '<li>Registro de faltas de asistencia</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/faltas/informes.php?claveal=') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de faltas de asistencia de un alumno</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/faltas/informes.php?claveal=') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de faltas de asistencia de un alumno</a></li>';
                                                        }

                                                        // Actividades extraescolares
                                                        elseif (stristr($row_historico['pagina'], 'faltas/seneca/') == true) {
                                                            echo '<li>Exportación de faltas de asistencia</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/actividades/indexextra.php') == true) {
                                                            echo '<li>Administración de actividades extraescolares</li>';
                                                        }
                                                        

                                                        // Fotografias
                                                        elseif (stristr($row_historico['pagina'], 'admin/fotos/profes.php') == true) {
                                                            echo '<li>Consulta las fotografías de los profesores</li>';
                                                        }

                                                        // Reservas
                                                        elseif (stristr($row_historico['pagina'], 'reservas/reservar/index_aulas.php?') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta y/o realización de reserva de aula</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'reservas/reservar/index_aulas.php') == true) {
                                                            // Nada aquí...
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'reservas/index_aula.php?recurso=aula_grupo') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta de reservas de aula</a></li>';
                                                        }

                                                        // Tareas 
                                                        elseif (stristr($row_historico['pagina'], 'tareas/tarea.php') == true) {
                                                            echo '<li><a href="../../../'.$row_historico['pagina'].'" target="_blank">Consulta una tarea pediente</a></li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'tareas') == true) {
                                                            echo '<li>Consulta de listado de tareas pedientes</li>';
                                                        }

                                                        // Informes de accesos
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/informes/accesos.php') == true) {
                                                            echo '<li>Consulta el informe de accesos de profesores a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/informes/accesos_alumnos.php') == true) {
                                                            echo '<li>Consulta el informe de accesos de alumnos/padres a la web del Centro</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/informes/sesiones.php') == true) {
                                                            echo '<li>Consulta informe de accesos a la Intranet</li>';
                                                        }

                                                        // Centro TIC
                                                        elseif (stristr($row_historico['pagina'], 'TIC/index.php') == true) {
                                                            echo '<li>Registro de nueva incidencia TIC</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'TIC/incidencias.php') == true) {
                                                            echo '<li>Consulta de incidencias TIC</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'TIC/perfiles_alumnos.php') == true) {
                                                            echo '<li>Consulta de perfiles TIC de alumnos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'TIC/perfiles_profesores.php') == true) {
                                                            echo '<li>Consulta de perfiles TIC de profesores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'TIC/estadisticas.php') == true) {
                                                            echo '<li>Consulta de estadística de uso de recursos TIC</li>';
                                                        }

                                                        // Administración de la Intranet
                                                        elseif (stristr($row_historico['pagina'], 'config/config.php') == true) {
                                                            echo '<li>Consulta o modificación de la Configuración de la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index2.php') == true) {
                                                            echo '<li>Importación por primera vez de los alumnos a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_xml.php') == true) {
                                                            echo '<li>Importación de los datos del Centro a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_departamentos.php') == true) {
                                                            echo '<li>Importación de los profesores a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_departamentos.php') == true) {
                                                            echo '<li>Importación de los horarios del personal a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_profesores.php') == true) {
                                                            echo '<li>Importación de la relación de profesores y asignaturas a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_pas.php') == true) {
                                                            echo '<li>Importación del personal no docente a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_festivos.php') == true) {
                                                            echo '<li>Importación de los días festivos a la Intranet</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/rof/index.php') == true) {
                                                            echo '<li>Modificación del ROF del Centro</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'config/cargos.php') == true) {
                                                            echo '<li>Modificación de los perfiles de los profesores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/gest_dep.php') == true) {
                                                            echo '<li>Modificación de los Departamentos del Centro</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/reset_password.php') == true) {
                                                            echo '<li>Restablecimiento de contraseña de uno o varios profesores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/horarios/index.php') == true) {
                                                            echo '<li>Creación o modificación del horario</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/horas.php') == true) {
                                                            echo '<li>Impresión de la hoja de firmas del profesorado</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_hor.php') == true) {
                                                            echo '<li>Copia de los datos de un profesor a otro por susitución</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_fotos_profes.php') == true) {
                                                            echo '<li>Subida masiva de fotos de los profesores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/cursos/listatotal.php') == true) {
                                                            echo '<li>Impresión de el listado total de grupos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/form_carnet.php') == true) {
                                                            echo '<li>Generación de los carnet de estudiantes de un grupo o alumno</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_fotos.php') == true) {
                                                            echo '<li>Subida masiva de fotos de los alumnos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'admin/libros/indextextos.php') == true) {
                                                            echo '<li>Importación o consulta de los libros de texto del Programa de Gratuidad</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/index_notas.php') == true) {
                                                            echo '<li>Importación de las calificaciones de los alumnos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=alumnos.txt') == true) {
                                                            echo '<li>Generación de archivo de exportación de alumnos para Gesuser</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=alumnos_moodle.txt') == true) {
                                                            echo '<li>Generación de archivo de exportación de alumnos para la Moodle</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=profesores.txt') == true) {
                                                            echo '<li>Generación de archivo de exportación de profesores para Gesuser</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=profesores_moodle.txt') == true) {
                                                            echo '<li>Generación de archivo de exportación de profesores para Moodle</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=profesores_gsuite.csv') == true) {
                                                            echo '<li>Generación de archivo de exportación de profesores para G Suite</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/exportaTIC.php?exportar=profesores_office365.csv') == true) {
                                                            echo '<li>Generación de archivo de exportación de profesores para Office 365</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'reservas/gestion_tipo.php') == true) {
                                                            echo '<li>Creación o modificación de los recursos para reservar</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'config/config_datos.php') == true) {
                                                            echo '<li>Modificación de la configuración de bases de datos de años anteriores</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/copia_db/index.php?action=crear') == true) {
                                                            echo '<li>Creación de una copia de seguridad de la base de datos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/copia_db/index.php') == true) {
                                                            echo '<li>Consulta de copias de seguridad de la base de datos</li>';
                                                        }
                                                        elseif (stristr($row_historico['pagina'], 'xml/jefe/copia_db/restaurar.php') == true) {
                                                            echo '<li>Restauración de una copia de seguridad de la base de datos</li>';
                                                        }

                                                        // Otras acciones
                                                        else {
                                                            if ($row_historico['pagina'] != '') {
                                                                echo '<li>Acción en la página: '.$row_historico['pagina'].'</li>';
                                                            }
                                                        }
                                                    ?>
                                                    </li>
                                                    <?php endwhile; ?>
                                                    </ul>
                                                    <?php else: ?>
                                                    <p>Solo iniciaste sesión en la Intranet</p>
                                                    <?php endif; ?> 
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <a href="../../../clave.php" class="btn btn-primary">Proteger mi cuenta</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <h5><strong><?php echo $u_agent['platform'].' '.$u_agent['platform_version']; ?> &middot; <?php echo $geoip; ?></strong></h5>
                                    <p class="text-muted"><small><?php echo $u_agent['browser_name'].' '.$u_agent['browser_version']; ?> &middot; <?php echo ($row['id'] == $_SESSION['id_pag']) ? '<span class="text-success">Sesión actual</span>' : strftime('%e de %B a las %H:%M', strtotime($row['fecha'])); ?>
                                    <br><?php echo $row['ip']; ?><?php echo (isset($meta_geoip['isp'])) ? ' ('.$meta_geoip['isp'].')' : ''; ?></small></p>
                                    <!--
                                    <p><small><?php echo $u_agent['userAgent']; ?></small></p>
                                    -->
                                 </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
					</tbody>
				</table>
				
			</div><!-- /.col-sm-12 -->
		</div><!-- /.row -->
	  
	</div><!-- /.container -->

<?php include('../../../pie.php'); ?>
	
</body>
</html>