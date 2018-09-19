<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO DE TAREAS PENDIENTES -->
<?php $result = mysqli_query($db_con, "SELECT id, idea, titulo, tarea, estado, fechareg, prioridad FROM tareas WHERE idea = '".$idea."' AND estado = 0 ORDER BY prioridad ASC, fechareg DESC"); ?>
<?php if (mysqli_num_rows($result)): ?>

<div class="well well-sm">
    
    <h4><span class="fas fa-tasks fa-fw"></span> Tareas pendientes</h4>
    
    <div class="list-group">
        <?php while ($row = mysqli_fetch_array($result)): ?>
        <a class="list-group-item" href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $row['id']; ?>">
            <div class="row">
                <div class="col-sm-2">
                    <form action="" method="post">
                        <input type="hidden" name="id_tarea" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="submit_tarea" class="btn btn-xs btn-default" data-bs='tooltip' title='Pulsar con el ratón para finalizar la tarea'><span class="fas fa-check fa-fw"></span></button>
                    </form>
                </div>
                <div class="col-sm-10">
                    <?php echo substr(stripslashes($row['titulo']),0 , 96); ?>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>

    <a href="//<?php echo $config['dominio']; ?>/intranet/tareas/" class="btn btn-default btn-sm">Ver tareas</a>
    <a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php" class="btn btn-default btn-sm pull-right">Nueva tarea</a>
</div>
<?php else: ?>

<?php if (isset($_GET['tour']) && $_GET['tour']): ?>
<div class="well well-sm">
    
    <h4><span class="fas fa-users fa-fw"></span> Tareas pendientes</h4>
    
    <div class="list-group">
        <a class="list-group-item" href="#">
            <div class="row">
                <div class="col-sm-2">
                    <button type="button" class="btn btn-xs btn-default"><span class="fas fa-check fa-fw"></span></button>
                </div>
                <div class="col-sm-10">
                    Subir programación a la Intranet
                </div>
            </div>
        </a>
        <a class="list-group-item" href="#">
            <div class="row">
                <div class="col-sm-2">
                    <button type="button" class="btn btn-xs btn-default"><span class="fas fa-check fa-fw"></span></button>
                </div>
                <div class="col-sm-10">
                    Redactar acta de departamento
                </div>
            </div>
        </a>
    </div>

</div>
<?php endif; ?>

<?php endif; ?>

<!-- FIN MODULO DE TAREAS PENDIENTES -->