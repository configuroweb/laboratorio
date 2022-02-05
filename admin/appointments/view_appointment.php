<?php 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT a.*,CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) as fullname, c.contact, c.gender,c.email,c.address FROM `appointment_list` a inner join `client_list` c on a.client_id = c.id where a.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    $test_ids = [];
    if(isset($id)){
        $atl = $conn->query("SELECT * FROM `appointment_test_list` where appointment_id = '{$id}' ");
        $res = $atl->fetch_all(MYSQLI_ASSOC);
        $test_ids = array_column($res,'test_id');
    }
}
?>

<div class="content py-5">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title"><b>Detalles de tu Cita Reservada</b></h4>
            <div class="card-tools">
                <?php if(isset($status) && $status == 4): ?>
                <button class="btn btn-info bg-gradient-info btn-flat btn-sm" type="button" id="upload_report"><i class="fa fa-upload"></i> Subir informe</button>
                <?php endif; ?>
                <button class="btn btn-default bg-gradient-navy btn-flat btn-sm" type="button" id="update_status"> Estado de Actualización</button>
                <button class="btn btn-primary btn-flat btn-sm" type="button" id="edit_data"><i class="fa fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-flat btn-sm" type="button" id="delete_data"><i class="fa fa-trash"></i> Eliminar</button>
                <a class="btn btn-default border btn-flat btn-sm" href="./?page=appointments" id="delete_data"><i class="fa fa-angle-left"></i> Volver</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid" id="outprint">
                <div class="row">
                    <div class="col-2 border bg-gradient-primary text-light">Código de Cita</div>
                    <div class="col-4 border"><?= isset($code) ? $code :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Calendario</div>
                    <div class="col-4 border"><?= isset($schedule) ? date("M d, Y h:i A", strtotime($schedule)) :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Nombre del@ Paciente</div>
                    <div class="col-10 border"><?= isset($fullname) ? $fullname :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light">Género</div>
                    <div class="col-3 border"><?= isset($gender) ? $gender :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light"># Teléfono</div>
                    <div class="col-3 border"><?= isset($contact) ? $contact :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light">Email</div>
                    <div class="col-3 border"><?= isset($email) ? $email :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Dirección</div>
                    <div class="col-10 border"><?= isset($address) ? $address :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Estado</div>
                    <div class="col-4 border ">
                        <?php 
                            switch ($status){
                                case 0:
                                    echo '<span class="">Pendiente</span>';
                                    break;
                                case 1:
                                    echo '<span class">Aprobado</span>';
                                    break;
                                case 2:
                                    echo '<span class">Muestra-Recolectada</span>';
                                    break;
                                case 3:
                                    echo '<span class="rounde">Entregado al laboratorio</span>';
                                    break;
                                case 4:
                                    echo '<span class">Finalizado</span>';
                                    break;
                                case 5:
                                    echo '<span class">Cancelado</span>';
                                    break;
                            }
                        ?>
                    </div>                    
                    <?php if(isset($status) && $status == 6): ?>
                    <div class="col-2 border bg-gradient-primary text-light">Informe Cargado</div>
                    <div class="col-10 border ">
                        <?php if(isset($code) && is_file(base_app."uploads/reports/".$code.".pdf")): ?>
                            <a href='<?= base_url."uploads/reports/".$code.".pdf" ?>' target='_blank' download='<?= $code.".pdf" ?>'><?= $code.".pdf" ?></a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-muted">Lista de Pruebas</legend>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="45%">
                            <col width="45%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-primary text-light">
                                <th class="text-center">#</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            if(isset($test_ids) && count($test_ids) > 0):
                            $tests = $conn->query("SELECT* FROM `test_list` where id in (".(implode(',',$test_ids)).") order by name asc");
                            while($row= $tests->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                <td class="py-1 px-2"><?= $row['name'] ?></td>
                                <td class="py-1 px-2 text-right"><?= number_format($row['cost'],2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </fieldset>
                <hr>
                <fieldset>
                    <legend class="text-muted">Historial de Actualizaciones</legend>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="20%">
                            <col width="40%">
                            <col width="30%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-primary text-light">
                                <th class="text-center">#</th>
                                <th>Fecha</th>
                                <th>Observaciones</th>
                                <th>Nuevo Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            if(isset($test_ids) && count($test_ids) > 0):
                            $history = $conn->query("SELECT* FROM `history_list` where appointment_id = '{$id}' order by unix_timestamp(date_created) asc");
                            while($row= $history->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                <td class="py-1 px-2"><?= date("M d, Y H:i",strtotime($row['date_created'])) ?></td>
                                <td class="py-1 px-2"><?= $row['remarks'] ?></td>
                                <td class="py-1 px-2">
                                <?php 
                                    switch ($row['status']){
                                        case 0:
                                            echo '<span class="">Pendiente</span>';
                                            break;
                                        case 1:
                                            echo '<span class">Aprobado</span>';
                                            break;
                                        case 2:
                                            echo '<span class">Muestra Recolectada</span>';
                                            break;
                                        case 3:
                                            echo '<span class="rounde">Entregado al laboratorio</span>';
                                            break;
                                        case 4:
                                            echo '<span class">Finalizado</span>';
                                            break;
                                        case 5:
                                            echo '<span class">Cancelado</span>';
                                            break;
                                        case 6:
                                            echo '<span class">Informe Subido</span>';
                                            break;
                                    }
                                ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($history->num_rows <=0): ?>
                                <tr>
                                    <th class="py-1 text-center" colspan="4">Sin Datos que Mostrar</th>
                                </tr>
                            <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <th class="py-1 text-center" colspan="4">Sin Datos que Mostrar</th>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#delete_data').click(function(){
			_conf("¿Estás segur@ de eliminar esta cita de forma permanente?","delete_appointment",['<?= isset($id) ? $id : '' ?>'])
		})
        $('#edit_data').click(function(){
			uni_modal("Actualizar Detalles de la Cita","appointments/manage_appointment.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
        $('#update_status').click(function(){
			uni_modal("Actualizar el Estado de la Cita","appointments/update_status.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
        $('#upload_report').click(function(){
			uni_modal("Cargar resultado de prueba/informe","appointments/upload.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
    })
    function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("Ocurrió un error.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.href="./?page=appointments";
				}else{
					alert_toast("Ocurrió un error.",'error');
					end_loader();
				}
			}
		})
	}
</script>