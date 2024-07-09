<?php 
require_once ("header.php");
include ("nav.php");

?>
    <h2 class="bg-dark text-lg text-white text-center rounded p-2 mt-2">RECUPERACIÓN DE CONTRASEÑA</h2>
    <div class="container mt-5 ">
        <div class="row justify-content-center " >
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #110257;">
                    <div class="card-body">
                        <form action="<?php echo FRONT_ROOT."Auth/recoverPassword" ?>" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese su email" required>
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="number" class="form-control" id="dni" name="dni" placeholder="DNI" required aria-valuemin="0">
                            </div>
                            <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">Recuperar contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?php echo JS_PATH."formScripts.js" ?>"> </script>
<script>
    registerForm.limitDni();
</script>
    <?php 
require_once ("footer.php");
?>