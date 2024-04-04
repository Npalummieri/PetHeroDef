<!-- 1. -->
<?php 
 include("header.php");
 include("nav.php");
 use Utils\Session as Session;
?>
<div class="wrapper row4">
<main class="container clear mt-5" style="width: max-content;"> 
  <div class="content"> 
      <h2 class="text-center text-white bg-dark rounded">LOGIN</h2>
      <?php  include_once("msgeDisplay.php") ?>
      
        <form action="<?php echo FRONT_ROOT . "Auth/login" //2 ?>" method="POST" class="login-form p-5 text-white rounded"  style = "background-color: #110257;">
          <div class="form-group m-2">
            <label for="user">User</label>
            <input type="text" name="userField" id="user" class="form-control form-control-lg" placeholder="Username or Email" required>
          </div>
          <div class="form-group m-2">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
          </div>

          <div class="m-2">
            <p><a href="<?php echo FRONT_ROOT."Auth/recoverPasswordView" ?>" class="text-white">Forgot password? <strong>CLICK HERE</strong></a></p>
          </div>
          <br/>
          <div class="m-2">
          <button class="btn btn-dark btn-block btn-lg" type="submit">Login</button>
          </div>
        </form>
  </div>
</main>
</div>
<?php 
 include("footer.php");
 
?>