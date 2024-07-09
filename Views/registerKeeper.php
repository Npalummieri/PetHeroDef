<?php require_once("header.php"); ?>
<?php include("nav.php"); ?>

<div class="wrapper row4 ">
    <main class="container clear d-flex justify-content-center" >
        <div class="content mt-3 ">
            <h2 class="text-center bg-dark text-white rounded">Registro de cuidador</h2>

            <?php include("msgeDisplay.php"); ?>
            <form action="<?php echo FRONT_ROOT . "Keeper/registerKeeper"; ?>" method="POST" enctype="multipart/form-data" class="rounded p-5 text-white" style="background-color :  #110257;">

                <?php require_once("register.php"); ?>

                <p class="text-center  mt-3"><strong>Información de cuenta</strong></p>

                <div class="form-group m-2">
                    <label for="TypePet" class="form-label">Tipo mascota :</label>
                    <select class="form-select" name="typePet" id="TypePet" required>
                        <option value="" selected>Seleccione tipo</option>
                        <option value="dog">Perro</option>
                        <option value="cat">Gato</option>
                    </select>
                </div>

                <div class="form-group m-2">
                    <label for="Size" class="form-label">Tamaño a cuidar : </label>
                    <select class="form-select" name="typeCare" id="Size" required>
                        <option value="" selected>Seleccione tamaño</option>
                        <option value="small">Pequeño (hasta 8 kg)</option>
                        <option value="medium">Mediano (hasta 15 kg)</option>
                        <option value="big">Grande (Mayor a 15 kg)</option>
                    </select>
                </div>

                <div class="form-group m-2">
                    <div class="text-center">
                    <label for="availDays" class="form-label m-3">Disponibilidad</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="initDate" class="form-label">Fecha inicial :</label>
                            <input type="date" name="initDate" id="initDate" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">Fecha final :</label>
                            <input type="date" name="endDate" id="endDate" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group m-2">
                    <label for="price" class="form-label">Precio (por visita)</label>
                    <input type="number" name="price" class="form-control" placeholder="Precio" required>
                </div>

                <div class="form-group m-2">
                    <label for="visitPerDay" class="form-label">Visitas por dia</label>
                    <select name="visitPerDay" id="visitPerDay" class="form-select">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>

                <div class="text-end">
                <button class="btn btn-primary btn-lg btn-block " type="submit">Registrar</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="<?php echo JS_PATH . 'formScript.js' ?>"></script>

<?php require_once("footer.php"); ?>
