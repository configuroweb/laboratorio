<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition ">
  <script>
    start_loader()
  </script>
  <style>
    html, body{
      height:calc(100%) !important;
      width:calc(100%) !important;
    }
    body{
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size:cover;
      background-repeat:no-repeat;
    }
    .login-title{
      text-shadow: 2px 2px black
    }
    #login{
      flex-direction:flex !important
    }
    #logo-img{
        height:150px;
        width:150px;
        object-fit:scale-down;
        object-position:center center;
        border-radius:100%;
    }
    #login .col-7,#login .col-5{
      width: 100% !important;
      max-width:unset !important
    }
  </style>
  <div class="h-100 d-flex align-items-center w-100" id="login">
    <div class="col-7 h-100 d-flex align-items-center justify-content-center">
      <div class="w-100">
        <center><img src="<?= validate_image($_settings->info('logo')) ?>" alt="" id="logo-img"></center>
        <h1 class="text-center py-5 login-title"><b><?php echo $_settings->info('name') ?> ConfiguroWeb</b></h1>
      </div>
      
    </div>
    <div class="col-5 h-100 bg-gradient-dark">
      <div class="d-flex w-100 h-100 justify-content-center align-items-center">
        <div class="card col-lg-10 card-outline card-primary rounded-0 shadow">
          <div class="card-header rounded-0">
            <h4 class="text-dark text-center"><b>Crear una Nueva Cuenta</b></h4>
          </div>
          <div class="card-body rounded-0 text-dark">
            <form id="registration-frm" action="" method="post">
                <input type="hidden" name="id">
              <div class="row">
                  <div class="form-group col-md-4">
                      <input type="text" name="firstname" id="firstname" placeholder="Nombre" autofocus required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Nombre</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="middlename" id="middlename" placeholder="(opcional)" class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Segundo Nombre</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="lastname" id="lastname" placeholder="Apellido" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Apellido</small>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-4">
                      <select name="gender" id="gender" class="form-control form-control-sm form-control-border" required>
                          <option>Masculino</option>
                          <option>Femenino</option>
                      </select>
                      <small class="mx-2">Género</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="date" name="dob" id="dob" placeholder="(opcional)" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Día Nacimiento</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="contact" id="contact" placeholder="+57 3XX-XXXXXXX" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2"># Teléfono</small>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-12">
                      <small class="mx-2">Dirección</small>
                      <textarea name="address" id="address" rows="3" class="form-control form-control-sm rounded-0"></textarea>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="email" name="email" id="email" placeholder="tucorreo@mail.com" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Email</small>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="password" name="password" id="password" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Contraseña</small>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="password" name="cpass" id="cpass" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Confirma Contraseña</small>
                  </div>
              </div>
              <div class="row align-items-center">
                <div class="col-8">
                  <a href="<?php echo base_url ?>">¿Ya tienes una cuenta?</a>
                </div>
                <!-- /.col -->
                <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Regístrate</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
    $(function(){
        end_loader();
        $('#registration-frm').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            if($('#password').val() != $('#cpass').val()){
                el.addClass('alert-danger')
                el.text("Las contraseñas no coinciden")
                $('#password').focus()
                $('#password, #cpass').addClass('is-invalid');
                $('#registration-frm').append(el)
                el.show('slow')
                return false;
            }
            $('#password, #cpass').removeClass('is-invalid');

            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=save_client",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
				error:err=>{
					console.log(err)
					alert_toast("Ocurrió un error",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp == '1'){
                        location.href=_base_url_;
                    }else{
                        el.addClass("alert-danger")
                        el.text("Ocurrió un error al registrar la cuenta.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>
</body>
</html>