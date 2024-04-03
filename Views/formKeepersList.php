<div class="container mx-auto my-3 rounded" style="background-color: #364a6e;">
    <form action="<?php echo FRONT_ROOT . "Keeper/getFilteredKeepers" ?>" method="GET" id="SearchForm" class="form-horizontal">
        <div class="row justify-content-center align-items-center p-3 text-white">
            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label class="text-truncate">Initial date:</label>
                <input type="date" class="form-control" name="initDate" id="InitDate" required>
            </div>

            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label class="text-truncate">End date:</label>
                <input type="date" class="form-control" name="endDate" id="EndDate" required>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Size:</label>
                <select class="form-control" name="size" id="Size" required>
                    <option value="">Select size</option>
                    <option value="big">Big</option>
                    <option value="medium">Medium</option>
                    <option value="small">Small</option>
                </select>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Type pet:</label>
                <select class="form-control" name="typePet" id="TypePet" required>
                    <option value="">Select type</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                </select>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12">
                <label class="text-truncate">Visits per day:</label>
                <select class="form-control" name="visitPerDay" id="visitPerDay" required>
                    <option value="">Select visits per day</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>

            <div class="form-group col-lg-2 col-md-6 col-sm-12 justify-content-center mt-3 ">
                <button class="btn w-100 text-white" style="background-color: #37914c;" type="submit" id="FilterButton">FILTER</button>
            </div>
        </div>

        <div id="results"></div>
    </form>
</div>