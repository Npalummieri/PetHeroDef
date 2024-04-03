<?php 
include ("header.php");
include ("nav.php");

?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Recovering password</h5>
                        <form action="<?php echo FRONT_ROOT."Auth/recoverPassword" ?>" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Recover password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
include ("footer.php");
?>