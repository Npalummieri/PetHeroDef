<!-- 1. -->
<?php 
 include("header.php");
 include("nav.php");
 use Utils\Session as Session;
?>
<div class="wrapper row4">
<main class="container clear rounded" style="width: max-content;"> 
  <div class="content"> 
      <h2>LOGIN</h2>
      <?php  include_once("msgeDisplay.php") ?>
      
        <form action="<?php echo FRONT_ROOT . "Auth/login" //2 ?>" method="POST" class="login-form p-5 text-white"  style = "background-color: #364a6e;">
          <div class="form-group">
            <label for="User">User</label>
            <input type="text" name="userField" class="form-control form-control-lg" placeholder="Username or Email" required>
          </div>
          <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
          </div>

          <div>
            <p><a href="<?php echo FRONT_ROOT."Auth/recoverPasswordView" ?>" class="text-white">Forgot password? <strong>CLICK HERE</strong></a></p>
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