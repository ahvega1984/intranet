<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');
echo "<div class='alert alert-info fade in' role='alert'><p class='lead'><i class='far fa-bell'> </i> Informes de tutoría activos (Asignaturas pendientes)</p><br />";

// Alumnos pendientes con asignaturas sin continuidad para los Jefes de Departamento

$query = mysqli_query($db_con,"SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, infotut_alumno.claveal, alma.unidad FROM infotut_alumno, alma WHERE infotut_alumno.claveal = alma.claveal and date(F_ENTREV) >= '$hoy' ORDER BY F_ENTREV asc");
while($row = mysqli_fetch_array($query)){
	$query2 = mysqli_query($db_con,"select distinct claveal, nombre, abrev from pendientes, asignaturas where pendientes.codigo = asignaturas.codigo and claveal = '$row[4]' and abrev like '%\_%' and nombre not in (select materia from profesores where grupo = '$row[5]')");
	if (mysqli_num_rows($query2)>0) {
		while($row2 = mysqli_fetch_array($query2)){
			$query3 = mysqli_query($db_con,"select distinct materia, asignaturas.codigo, asignaturas.abrev from profesores, asignaturas where materia = nombre and materia = '$row2[1]' and abrev like '%\_%' and profesor in (select distinct nombre from departamentos where departamento like '".$_SESSION['dpt']."')");
			if (mysqli_num_rows($query3)>0) {
				$si_pend = mysqli_query($db_con, "select * from infotut_profesor where id_alumno = '$row[0]' and asignatura = '$row2[1] ($row2[2])'");
				if (mysqli_num_rows($si_pend) > 0)
				{ }
				else
				{

					$n_infotut = $n_infotut+1;

					$fechac = explode("-",$row[3]);

					echo "<p>$fechac[2]-$fechac[1]-$fechac[0].
					<a class='alert-link' data-toggle='modal' href='#infotut$n_infotut' > $row[2] $row[1]</a> -- $row[5]  
					<span class='pull-right'>
					<a href='./admin/infotutoria/infocompleto.php?id=$row[0]' class='alert-link' data-bs='tooltip' title='Ver informe'><span class='fas fa-search fa-fw fa-lg'></span></a>
					<a href='./admin/infotutoria/informar.php?id=$row[0]&materia=$row2[1]' class='alert-link' data-bs='tooltip' title='Rellenar'><span class='fas fa-pencil-alt fa-fw fa-lg'></span></a>
					</span>
					</p>";

					?>
					<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="infotut<?php echo $n_infotut;?>">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span
						aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" style="color: #333;">Informe de tutoría de materias pendientes<br><small><?php echo "$row[2] $row[1]";?></small>
					</h4>
					</div>
					<div class="modal-body"><?php
					$alumno=mysqli_query($db_con, "SELECT APELLIDOS, NOMBRE, unidad, id, TUTOR, F_ENTREV, CLAVEAL FROM infotut_alumno WHERE ID='$row[0]'");
					$dalumno = mysqli_fetch_array($alumno);
					$claveal=$dalumno[6];
					$datos=mysqli_query($db_con, "select asignatura, informe from infotut_profesor where id_alumno = '$row[0]' and asignatura = '$row2[1] (Asignatura pendiente)'");
					if(mysqli_num_rows($datos) > 0)
					{
						while($informe = mysqli_fetch_array($datos))
						{
							echo "<p style='color:#08c'>$informe[0]. <span style='color:#555'> $informe[1]</span></p>";
						}
					}
					else{
						echo "<p style='color:#08c'>Los profesores no han rellenado aún su informe de tutoría.</p>";
					}
					?>
					</div>

					<div class="modal-footer"><a href="#" class="btn btn-primary"
						data-dismiss="modal">Cerrar</a></div>
					</div>
					</div>
					</div>
					<?
				}						
			}				
		}			
	}		
}


if ($n_i==1) {
	echo "<br>";
}

?>	

<script language="javascript">
    $('#myModal2').modal()
    </script>
</div>