<?php include("header.php"); ?>
<?php include("nav.php"); ?>

<div class="wrapper row4">
    <main class="container clear" style="width: max-content;">
        <div class="content mt-3">
            <h2>REGISTER KEEPER</h2>

            <?php if (!empty($msgResult)) { ?>
                <p class="alert alert-danger"><?php echo $msgResult; ?></p>
            <?php } ?>

            <form action="<?php echo FRONT_ROOT . "Keeper/registerKeeper"; ?>" method="POST" enctype="multipart/form-data" class="login-form bg-dark-alpha p-5 text-white">

                <?php require_once("register.php"); ?>

                <div class="form-group">
                    <label for="TypePet">Type Pet</label>
                    <select class="form-select" name="typePet" id="TypePet" required>
                        <option value="" selected>Select type</option>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Size">Size to care</label>
                    <select class="form-select" name="typeCare" id="Size" required>
                        <option value="" selected>Select size</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="big">Big</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="availDays">Availability</label>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="initDate">Initial Date :</label>
                            <input type="date" name="initDate" id="initDate" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="endDate">End date :</label>
                            <input type="date" name="endDate" id="endDate" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control" placeholder="Price" required>
                </div>

                <button class="btn btn-dark btn-block btn-lg" type="submit">Register</button>
            </form>
        </div>
    </main>
</div>

<script src="<?php echo JS_PATH . 'formScript.js' ?>"></script>
<script>
    // document.getElementById("myForm").addEventListener("submit", function(event) {
    //     var checkboxes = document.querySelectorAll('input[name="availDays[]"]:checked');
    //     if (checkboxes.length === 0) {
    //         alert("At least select 1 day to keep!");
    //         event.preventDefault();
    //     }
    // });
</script>

<?php include("footer.php"); ?>
