<?php require_once("header.php");
require_once("nav.php"); ?>


<div id="curUrl" data-cururl = "<?php echo FRONT_ROOT ?>"></div>
<div class="container col-sm-4 col-md-8 col-lg-12">
  <h1 class="bg-dark text-white text-center rounded m-2 p-2 text-truncate">MAKE YOUR BOOKING</h1>
    <div class="card text-white" style="background-color: #110257;" >
      <div class="card-body">
        
        <?php include("msgeDisplay.php"); ?>
        
        <form action="<?php echo FRONT_ROOT . "Booking/addBooking" ?>" method="POST" id="BookForm">
          <div class="form-group row m-2">
            <label for="InitDate">INITIAL DATE</label>
            <input type="date" name="initDate" id="InitDate" class="form-control" min="<?php echo date('Y-m-d'); ?>"  required>
          </div>
          <div class="form-group row m-2">
            <label for="EndDate">END DATE</label>
            <input type="date" name="endDate" id="EndDate" class="form-control" min="<?php echo date('Y-m-d'); ?>"  required>
          </div>
          <div>
            <p id="AvailMsge" class="text-lg text-uppercase"><strong></strong></p>
          </div>
          <p class="text-center">REMEMBER! This keeper only takes care of <strong><?php echo $typePet ?></strong> with this specific size: <strong><?php echo $typeSize ?></strong></p>
          <div id="DivType" data-typepet="<?php echo $typePet ?>"></div>
          <div id="DivSize" data-typesize="<?php echo $typeSize ?>"></div>
          <div class="form-group row m-2">
            <label for="PetCode">Pet to keep :</label>
            <select name="petCode" id="PetCode" class="form-control" required>
              <option value="">Select your Pet : </option>
            </select>
            <p class="text-danger m-1">Note: If you don't see any pets displayed to select, most probably you don't have the type of Pet that Keeper attends</p>
          </div>
          <div class="form-group row m-2">
            <label for="visitPerDaySelect" class="">Visit per day :</label>
          <select id="visitPerDaySelect" class="form-control" name="visitPerDay">

          </select>
          </div>
          <input type="hidden" id="keeperCode" name="keeperCode" value="<?php echo $keeperToCheck ?>">
          <input type="hidden" name="typePet" value="<?php echo $typePet ?> ">
          <input type="hidden" name="typeSize" value="<?php echo $typeSize ?> ">

        

          <div class="form-group row m-2">
            <button type="submit" id="ButtonCheck" class="btn btn-primary">Confirm</button>
          </div>



          <div id="buttonToForm"></div>
        </form>
      </div>
    </div>
    </div>
<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
    FormAjaxModule.getSpecificPets();
    FormAjaxModule.generateVisitPerDaySelect();

</script>
<?php include_once("footer.php") ?>