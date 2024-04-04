<div class="container" style="background-color: #110257;">
  <div class="row justify-content-center mt-3 " >
    <div class="row-lg-6">
      <p class="text-center mt-lg-0 mt-3"><strong>ACCOUNT RELATED INFO</strong></p>
      <div class="form-group m-1">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email" maxlength="30" required>
      </div>
      <div class="form-group m-1">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="User" maxlength="12" required>
      </div>
      <div class="form-group m-1">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" minlength="8" maxlength="15" required>
      </div>
    </div>
    <div class="row-lg-6">
      <p class="text-center mt-4"><strong>PERSONAL INFO</strong></p>
      <div class="form-group m-1">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Name" maxlength="20" required>
      </div>
      <div class="form-group m-1">
        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" class="form-control form-control-lg" placeholder="Lastname" maxlength="20" required>
      </div>
      <div class="form-group m-1">
        <label for="dni">Dni</label>
        <input type="number" name="dni" id="dni" class="form-control form-control-lg" placeholder="Dni" min="8000000" required>
      </div>
      <div class="form-group m-1">
        <label for="pfp">Profile picture</label>
        <input type="file" name="pfp" id="pfp" class="form-control form-control-lg" placeholder="Profile picture">
      </div>
    </div>
  </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script >
  registerForm.limitDni();
</script>