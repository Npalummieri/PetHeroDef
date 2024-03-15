<!-- 1. -->
<?php 
 include("header.php");
 include("nav.php");
?>
<div class="wrapper row4">
<main class="container clear" style="width: max-content;"> 
  <div class="content"> 
      <h2>LOGIN</h2>
      <p class="alert alert-danger" ><?php  echo $message; ?></p>
        <form action="<?php echo FRONT_ROOT . "Auth/login" //2 ?>" method="POST" class="login-form bg-dark-alpha p-5 text-white">
          <div class="form-group">
            <label for="User">User</label>
            <input type="text" name="userField" class="form-control form-control-lg" placeholder="Username or Email" required>
          </div>
          <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
          </div>

          <div>
            <p><a href="">Forgot password? <strong>CLICK HERE</strong></a></p>
          </div>
          <br/>
          <button class="btn btn-dark btn-block btn-lg" type="submit">Login</button>
        </form>
  </div>
</main>
</div>
<?php 
 include("footer.php");
 
?>