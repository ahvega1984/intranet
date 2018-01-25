<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

function obtener_problema_por_id_asunto($id_asunto, $tipos_incidencia) {
    foreach ($tipos_incidencia as $problema => $asuntos) {
        foreach ($asuntos as $id => $asunto) {
            if ($id == $id_asunto) {
                return array(
                    'problema' => $problema,
                    'asunto'   => $asunto
                );
            }
        }
    }
    return false;
}

$tipos_incidencia = array(
    'Aulas con PDI (Ordenador en la mesa del profesor)' => array(
        101 => 'Ordenador: El ordenador no se enciende',
        102 => 'Ordenador: El ordenador se enciende, pero no se inicia el sistema operativo Windows o Guadalinex',
        103 => 'Ordenador: No funciona uno o varios puertos USB del ordenador',
        104 => 'Ordenador: No funciona el lector de CD / DVD del ordenador',
        105 => 'Monitor: El monitor no se enciende',
        106 => 'Monitor: El monitor se enciende, pero no muestra imagen del ordenador',
        107 => 'Proyector: El proyector no se enciende (no se enciende ningún led)',
        108 => 'Proyector: El proyector se enciende, pero no proyecta la imagen del ordenador',
        109 => 'Proyector: El proyector se enciende, pero se ve con dificultad',
        110 => 'Proyector: El proyector se enciende, pero se apaga automáticamente pasado unos segundos o minutos (led pardadea en rojo)',
        111 => 'Proyector: El proyector se enciende, pero muestra el mensaje "Reemplace la lámpara / Replace lamp"',
        112 => 'Proyector: El proyector se enciende, pero muestra el mensaje "Limpie el filtro / Clean filter"',
        113 => 'Proyector: El proyector y el monitor se encienden, pero la imagen se muestra o proyecta en la pizarra con tono verde, amarillo o magenta',
        114 => 'Pizarra Digital: La Pizarra Digital Interactiva no está encendida',
        115 => 'Pizarra Digital: La Pizarra Digital Interactiva está encendida, pero no hay respuesta táctil',
        116 => 'Pizarra Digital: La Pizarra Digital Interactiva está encendida, pero la respuesta táctil es errónea',
        117 => 'Altavoces: Los altavoces no se encienden',
        118 => 'Altavoces: Los altavoces se encienden, pero no se oye nada',
        119 => 'Punto de acceso: Router neutro D-LINK DIR300 / DI-524 no se enciende',
        120 => 'Punto de acceso: Router neutro D-LINK DIR300 / DI-524 se enciende, pero la dirección IP es incorrecta',
        100 => 'Otros...'
    ),
    'Aulas con SDI (Ordenador integrado en la pizarra digital)' => array(
        201 => 'Ordenador: El ordenador Intel NUC no se enciende',
        202 => 'Ordenador: El ordenador Intel NUC se enciende, pero no se inicia el sistema operativo Windows o Guadalinex',
        203 => 'Ordenador: No funciona uno o varios puertos USB del ordenador Intel NUC',
        204 => 'Proyector: El proyector no se enciende',
        205 => 'Proyector: El proyector y el ordenador Intel NUC se encienden, pero no proyecta imagen del ordenador',
        206 => 'Proyector: El proyector se enciende, pero se ve con dificultad',
        207 => 'Proyector: El proyector se enciende, pero se apaga automáticamente pasado unos segundos o minutos (led pardadea en rojo)',
        208 => 'Proyector: El proyector se enciende, pero muestra el mensaje "Reemplace la lámpara / Replace lamp"',
        209 => 'Proyector: El proyector se enciende, pero muestra el mensaje "Limpie el filtro / Clean filter"',
        210 => 'Proyector: El proyector se enciende, pero se proyecta con tono verde, amarillo o magenta',
        211 => 'Pizarra Digital: La bandeja interactiva no se enciende (no se enciende ningún led)',
        212 => 'Pizarra Digital: La bandeja interactiva se enciende (led en verde), pero la respuesta táctil es errónea',
        213 => 'Pizarra Digital: La bandeja interactiva se enciende (led en verde), pero no hay respuesta táctil',
        214 => 'Altavoces: El altavoz no se enciende (no se enciende ningún led)',
        215 => 'Altavoces: El altavoz se enciende (led en rojo o naranja), pero no se oye nada',
        216 => 'Punto de acceso: Router neutro D-LINK DIR-860L no se enciende',
        217 => 'Punto de acceso: Router neutro D-LINK DIR-860L se enciende, pero la dirección IP es incorrecta',
        200 => 'Otros...'
    ),
    'Problemas con portátiles de carros TIC' => array(
        301 => 'Alimentación: La batería no carga o dura muy poco tiempo',
        302 => 'Alimentación: Falta cargador o no funciona correctamente',
        303 => 'Altavoces: Los altavoces no funcionan correctamente',
        304 => 'Conexiones: Uno o varios puertos USB no funcionan correctamente',
        305 => 'Pantalla: La pantalla muestra una mancha negra (píxeles muertos) o está rota',
        306 => 'Sistema operativo: No arranca el sistema operativo correctamente',
        307 => 'Teclado: Al teclado le falta una o varias teclas',
        308 => 'Teclado: No funcionan algunas teclas',
        309 => 'Touchpad: El touchpad o panel táctil no funciona correctamente',
        300 => 'Otros...'
    ),
    'Problemas con ratón, teclado, impresora, escaner, etc.' => array(
        401 => 'Ratón: Falta ratón o no funciona correctamente',
        402 => 'Teclado: Falta teclado o no funciona correctamente',
        403 => 'Impresora: La impresora o fotocopiadora no imprime desde el ordenador',
        404 => 'Impresora: La impresora o fotocopiadora no tiene tinta o toner',
        405 => 'Impresora: La impresora o fotocopiadora tiene papel atascado',
        406 => 'Impresora: La impresora o fotocopiadora no escanea al ordenador',
        406 => 'Impresora: He extraviado mi clave o tarjeta para usar la fotocopiadora',
        407 => 'Escaner: El escaner no funciona correctamente',
        408 => 'Altavoces: Los altavoces no funcionan correctamente',
        400 => 'Otros...'
    ),
    'Problemas de software en Windows o Guadalinex' => array(
        501 => 'Libro digital: No puedo instalar o no funciona correctamente el libro digital (Windows)',
        502 => 'Libro digital: No puedo instalar o no funciona correctamente el libro digital (Guadalinex)',
        503 => 'Instalación de aplicación: No puedo instalar una aplicación (Windows)',
        504 => 'Instalación de aplicación: No puedo instalar una aplicación (Guadalinex)',
        505 => 'Multimedia: No puedo reproducir audio y/o videos correctamente con el reproductor VLC',
        500 => 'Otros...'
    ),
    'Problemas de conexión a Internet' => array(
        601 => 'Conexión: El ordenador no tiene conexión a Internet',
        602 => 'Conexión: El ordenador tiene conexión a Internet, pero es demasiado lenta',
        603 => 'Conexión: No puedo conectarme a Andared con mi ordenador o dispositivo móvil',
        604 => 'Hardware: Cable de red ethernet deteriorado o la clavija RJ45 se sale fácilmente',
        605 => 'Hardware: Tarjeta de red inalámbrica sin antena',
        606 => 'Navegador: No puedo acceder a algunas páginas web con el navegador. Aparece el mensaje "Acceso denegado".',
        607 => 'Navegador: No puedo acceder a algunas páginas web con el navegador Mozilla Firefox (Guadalinex)',
        608 => 'Navegador: No puedo acceder a algunas páginas web con el navegador Google Chromium (Guadalinex)',
        609 => 'Configuración: No puedo acceder a algunas páginas web con el navegador. Aparece el mensaje "Conexión no segura / Secure Connection Failed"',
        610 => 'Configuración: No puedo acceder a algunas páginas web con el navegador. Aparece el mensaje "Servidor no encontrado / Server Not Found"',
        611 => 'Configuración: Aparece el mensaje "Windows detectó un conflicto en la dirección IP / Windows has detected an IP address conflict"',
        612 => 'Configuración: La dirección IP está en un rango de direcciones incorrecto',
        600 => 'Otros...'
    ),
    'Extravío de dispostivos personales o documentos del Centro / Virus informáticos / Suplantación de identidad' => array(
        701 => 'Infección de virus: Ordenador, tablet o teléfono personal tiene un virus',
        702 => 'Infección de virus: Pendrive o disco duro externo tiene un virus',
        703 => 'Extravío de documentos del centro: He extraviado un informe, examenes u otros documentos con información personal sobre alumnos y/o profesores',
        704 => 'Extravío de equipos personales: He extraviado mi ordenador, tablet o teléfono personal con información personal sobre alumnos y/o profesores',
        705 => 'Extravío de equipos personales: Sospecho que alguien ha tenido acceso a mi ordenador, tablet o teléfono personal con información personal sobre alumnos y/o profesores',
        706 => 'Extravío de equipos personales: He extraviado mi pendrive o disco duro externo con información personal sobre alumnos y/o profesores',
        707 => 'Correo electrónico: He extraviado mi contraseña o sospecho que alguien ha tenido acceso a mi cuenta de correo electrónico personal con información personal sobre alumnos y/o profesores',
        708 => 'Cuentas corporativas: He extraviado mi contraseña o sospecho que alguien ha tenido acceso a mi cuenta de correo corporativa',
        709 => 'Cuentas corporativas: He extraviado mi contraseña o sospecho que alguien ha tenido acceso a mi cuenta de Séneca',
        710 => 'Cuentas corporativas: He extraviado mi contraseña o sospecho que alguien ha tenido acceso a mi cuenta de Intranet',
        711 => 'Otros...'
    ),
    'Otros problemas' => array(
        901 => 'Otros...'
    )
);

$estados_incidencia = array(
    1 => 'Abierta',
    2 => 'En curso',
    3 => 'Cerrada',
    4 => 'Cancelada'
);