<div class="container mx-auto my-3 rounded" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT . "Keeper/getFilteredKeepers" ?>" method="GET" id="SearchForm" class="form-horizontal">
        <div class="row justify-content-center align-items-center p-3 text-white">
            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label class="text-truncate">Desde :</label>
                <input type="date" class="form-control" name="initDate" id="InitDate" required min="<?php echo date("Y-m-d"); ?>" value="<?php echo isset($initDate) ? htmlentities($initDate) : '' ?>">
            </div>

            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label class="text-truncate">Hasta :</label>
                <input type="date" class="form-control" name="endDate" id="EndDate" required min="<?php echo date("Y-m-d"); ?>" value="<?php echo isset($endDate) ? htmlentities($endDate) : '' ?>">
            </div>

            <?php $size = isset($size) ? htmlentities($size) : '' ?>
            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Tamaño :</label>
                <select class="form-control" name="size" id="Size" required value="<?php echo isset($size) ? htmlentities($size) : '' ?>">
                    <option value="">Seleccione tamaño</option>
                    <option value="big" <?php if($size == 'big'){ ?> selected = 'true' <?php } ?>>Grande</option>
                    <option value="medium" <?php if($size == 'medium'){ ?> selected = 'true' <?php } ?>>Mediano</option>
                    <option value="small" <?php if($size == 'small'){ ?> selected = 'true' <?php } ?>>Pequeño</option>
                </select>
            </div>

            <?php $typePet = isset($typePet) ? htmlentities($typePet) : '' ?>
            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Tipo :</label>
                <select class="form-control" name="typePet" id="TypePet" required value="<?php echo isset($typePet) ? htmlentities($typePet) : '' ?>">
                    <option value="">Seleccionar tipo</option>
                    <option value="dog" <?php if($typePet == 'dog'){ ?> selected = 'true' <?php } ?>>Perro</option>
                    <option value="cat" <?php if($typePet == 'cat'){ ?> selected = 'true' <?php } ?>>Gato</option>
                </select>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Visitas por dia :</label>
                <?php $visitPerDay = isset($visitPerDay) ? htmlentities($visitPerDay) : '' ?>
                <select class="form-control" name="visitPerDay" id="visitPerDay" required >
                    <option value="">visitas por dia</option>
                    <option value="1" <?php if($visitPerDay == '1'){ ?> selected = 'true' <?php } ?>>1</option>
                    <option value="2" <?php if($visitPerDay == '2'){ ?> selected = 'true' <?php } ?>>2</option>
                </select>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12 justify-content-center mt-3 ">
                <button class="btn w-100 text-white" style="background-color: #37914c;" type="submit" id="FilterButton">FILTRAR</button>
            </div>
        </div>

        <div id="results"></div>
    </form>
</div>