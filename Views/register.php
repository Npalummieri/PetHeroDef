<div class="container" style="background-color: #110257;" id="formEmbed">
  <div class="d-none justify-content-center" id="msgFilter" >
    <ul id="warningList" class="d-none w-100" >
    <li class="d-none text-center text-truncate-multiline" id="warnEmail"></li>
    <li class="d-none text-center text-truncate-multiline" id="warnUsername"></li>
    <li class="d-none text-center text-truncate-multiline" id="warnPass"></li>
    <li class="d-none text-center text-truncate-multiline" id="warnName"></li>
    <li class="d-none text-center text-truncate-multiline" id="warnLast"></li>
    <li class="d-none text-center text-truncate-multiline" id="warnPfp"></li>
  </ul></div>
  <div class="row justify-content-center mt-3 " >
    <div class="col-sm-6 col-md-8 col-lg-12">
    <div class="">
      <p class="text-center mt-lg-0 mt-3"><strong>Datos de cuenta</strong></p>
      <div class="form-group m-2">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email" maxlength="30" required>
      </div>
      <div class="form-group m-2">
        <label for="username">Nombre de usuario</label>
        <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="Nombre de usuario" maxlength="12" required>
      </div>
      <div class="form-group m-2">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Contraseña" minlength="8" maxlength="15" required>
      </div>
    </div>
    <div class="row-lg-6">
      <p class="text-center mt-4"><strong>Información personal</strong></p>
      <div class="form-group m-2">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Nombre" maxlength="20" required>
      </div>
      <div class="form-group m-2">
        <label for="lastname">Apellido</label>
        <input type="text" name="lastname" id="lastname" class="form-control form-control-lg" placeholder="Apellido" maxlength="20" required>
      </div>
      <div class="form-group m-2">
        <label for="dni">Dni</label>
        <input type="number" name="dni" id="dni" class="form-control form-control-lg" placeholder="Dni" required>
      </div>
      <div class="form-group m-2">
        <label for="pfp">Foto de perfil</label>
        <input type="file" name="pfp" id="pfp" class="form-control form-control-lg" placeholder="Foto de perfil">
      </div>
    </div>
  </div>
  </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script >
  registerForm.limitDni();
  registerForm.formControl();
</script>