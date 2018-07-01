<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

echo "<div class='alert alert-warning fade in' role='alert'><p class='lead'><i class='far fa-bell'> </i> Informes de Grupo activos</p><br />";
//echo $SQLcurso3."<br>";
$resultcurs33 = mysqli_query($db_con, $SQLcurso3);
while($rowcurs = mysqli_fetch_array($resultcurs33))
{
	$curso = $rowcurs[0];
	$curso0 = explode("-",$curso);
	$nivel_i = $curso;
	$asignatura = trim($rowcurs[1]);
	$texto_asig="";
	$c_asig="";
	
	$asigna0 = "select codigo from asignaturas where nombre = '$asignatura' and curso like '$rowcurs[2]' and abrev not like '%\_%'";
	//echo $asigna0."<br>";

	$asigna1 = mysqli_query($db_con, $asigna0);
	
	$asigna2 = mysqli_fetch_array($asigna1);
	$c_asig=" asignatura = '$asigna2[0]'";

	if($c_asig){
		$hoy = date('Y-m-d');
		$query = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, infotut_alumno.claveal, motivo FROM infotut_alumno WHERE date(F_ENTREV) >= '$hoy' and infotut_alumno. unidad = '$curso' and apellidos like 'Informe general%' ORDER BY F_ENTREV asc";
		//echo $query."<br>";
		$result = mysqli_query($db_con, $query);
		$n_inotut="";
		if (mysqli_num_rows($result) > 0)
		{
				
			$n_i=1;
			while($row1 = mysqli_fetch_array($result))
			{			
								
			$hay = "select * from infotut_profesor where id_alumno = '$row1[0]'  and asignatura = '$asignatura'";
			$si = mysqli_query($db_con, $hay);
			$num_inf = mysqli_num_rows($si);

			if ( $num_inf > 0)
			{}
			else
			{
				$n_infotut = $n_infotut+1;
				$fechac = explode("-",$row1[3]);

				echo "<p>$fechac[2]-$fechac[1]-$fechac[0].
	<a class='alert-link' data-toggle='modal' href='#infotut$n_infotut' > $row1[5]</a> -- $curso $row[6]  
	<span class='pull-right'>
	<a href='./admin/infotutoria/infocompleto.php?id=$row1[0]' class='alert-link' data-bs='tooltip' title='Ver informe'><span class='far fa-search fa-fw fa-lg'></span></a>
	<a href='./admin/infotutoria/informar_general.php?id=$row1[0]' class='alert-link' data-bs='tooltip' title='Rellenar'><span class='far fa-pencil-alt fa-fw fa-lg'></span></a>
	</span>
	</p>";
			?>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="infotut<?php echo $n_infotut;?>">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span
	aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" style="color: #333;">Informe de tutoría<br><small><?php echo "$row1[5]";?></small></h4>
</div>
<div class="modal-body"><?php
$alumno=mysqli_query($db_con, "SELECT APELLIDOS, NOMBRE, unidad, id, TUTOR, F_ENTREV, CLAVEAL FROM infotut_alumno WHERE ID='$row1[0]'");
$dalumno = mysqli_fetch_array($alumno);
$claveal=$dalumno[6];
$datos=mysqli_query($db_con, "SELECT asignatura, informe, id, profesor FROM infotut_profesor WHERE id_alumno='$row1[0]'");
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
?></div>
<div class="modal-footer"><a href="#" class="btn btn-primary"
	data-dismiss="modal">Cerrar</a></div>
</div>
</div>
</div>

<?php
				
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
