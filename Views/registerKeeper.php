<?php require_once("header.php"); ?>
<?php include("nav.php"); ?>

<div class="wrapper row4 ">
    <main class="container clear d-flex justify-content-center" >
        <div class="content mt-3 ">
            <h2 class="text-center bg-dark text-white rounded">REGISTER KEEPER</h2>

            <?php include("msgeDisplay.php"); ?>
            <form action="<?php echo FRONT_ROOT . "Keeper/registerKeeper"; ?>" method="POST" enctype="multipart/form-data" class="rounded p-5 text-white" style="background-color :  #110257;">

                <?php require_once("register.php"); ?>

                <p class="text-center  mt-3"><strong>KEEPER RELATED INFO</strong></p>

                <div class="form-group m-2">
                    <label for="TypePet" class="form-label">Type Pet</label>
                    <select class="form-select" name="typePet" id="TypePet" required>
                        <option value="" selected>Select type</option>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                    </select>
                </div>

                <div class="form-group m-2">
                    <label for="Size" class="form-label">Size to care</label>
                    <select class="form-select" name="typeCare" id="Size" required>
                        <option value="" selected>Select size</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="big">Big</option>
                    </select>
                </div>

                <div class="form-group m-2">
                    <label for="availDays" class="form-label">Availability</label>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="initDate" class="form-label">Initial Date :</label>
                            <input type="date" name="initDate" id="initDate" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End date :</label>
                            <input type="date" name="endDate" id="endDate" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group m-2">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" placeholder="Price" required>
                </div>

                <div class="form-group m-2">
                    <label for="visitPerDay" class="form-label">Visit per day</label>
                    <select name="visitPerDay" id="visitPerDay" class="form-select">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>

                <div class="text-end">
                <button class="btn btn-primary btn-lg btn-block " type="submit">Register</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="<?php echo JS_PATH . 'formScript.js' ?>"></script>

<?php require_once("footer.php"); ?>
