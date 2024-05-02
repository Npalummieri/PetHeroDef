<?php
include_once("header.php");
include_once("nav.php");
use Utils\Session as Session;
require_once(VIEWS_PATH . "formBookingList.php");
?>

<?php require_once("msgeDisplay.php"); ?>


<div class="table-responsive" style="background-color: #110257;">
  <table class="table table-striped table-bordered align-middle mb-0">
    <thead class="bg-light">
      <tr>
        <th><?php if(Session::IsLogged()){echo (Session::GetTypeLogged() == "Models\Keeper") ? "PetPFP / Owner name" : "PetPFP / Keeper name" ;}  ?></th>
        <th>Pet name</th>
        <th>Status</th>
        <th>Initial date</th>
        <th>End date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($myBookings == null || empty($myBookings)) { ?>
        <tr>
          <td colspan="7" class="text-center">No bookings yet</td>
        </tr>
      <?php } ?>
      <?php foreach ($myBookings as $key => $value) {
        $objBook = $value["booking"];
      ?>
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <a href="<?php echo FRONT_ROOT . 'Pet/showPetProfile/' . $objBook->getPetCode(); ?>" class="fw-normal mb-1">
                <img src="<?php echo FRONT_ROOT . 'Images/' . $value["pfp"]; ?>" alt="petPhoto" style="width: 45px; height: 45px" class="rounded-circle" />
              </a>
              <div class="ms-3">
			  <?php if(Session::GetTypeLogged() == "Models\Keeper")
			  { ?>
				 <a href="<?php echo FRONT_ROOT . 'Owner/showProfileOwner/' . $objBook->getOwnerCode(); ?>" class="fw-normal mb-1"><?php echo $value["ownerName"]; ?></a> 
			  <?php }else{ ?>
				<a href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $objBook->getKeeperCode(); ?>" class="fw-normal mb-1"><?php echo $value["keeperName"]; ?></a> 
			  <?php }?>
                <p class="text-muted mb-0"></p>
              </div>
            </div>
          </td>
          <td>
            <a href="<?php echo FRONT_ROOT . 'Pet/showPetProfile/' . $objBook->getPetCode(); ?>" class="fw-normal mb-1"><?php echo $value["petName"]; ?></a>
            <p class="text-muted mb-0"></p>
          </td>
          <td>
            <span class="badge text-dark badge-success rounded-pill d-inline"><?php echo $objBook->getStatus();  ?></span>
          </td>
          <td><?php echo $objBook->getInitDate(); ?></td>
          <td>
            <span><?php echo $objBook->getEndDate(); ?></span>
          </td>


          <td>
            <?php if ($objBook->getStatus() === "pending" && $loggedUser instanceof Models\Keeper) { ?>
              <a href="<?php echo FRONT_ROOT . 'Booking/manageBooking/' . $objBook->getBookCode(); ?>" class="btn btn-dis btn-success" data-msg="Confirm the booking?">Confirm</a>
              <a href="<?php echo FRONT_ROOT . 'Booking/cancelBooking/' . $objBook->getBookCode(); ?>" class="btn  btn-dis btn-danger" data-msg="Cancel the booking?">Reject</a>
            <?php } elseif ($objBook->getStatus() === "confirmed" && $loggedUser instanceof Models\Owner) { ?>
              <a href="<?php echo FRONT_ROOT . 'Coupon/showCouponFromBook/' . $objBook->getBookCode(); ?>" class="btn btn-success">Pay it</a>
              <a href="<?php echo FRONT_ROOT . 'Booking/cancelBooking/' . $objBook->getBookCode(); ?>" class="btn  btn-dis btn-danger" data-msg="Cancel the booking?">Cancel</a>
            <?php } ?>
            <a class="link m-2" href="<?php echo FRONT_ROOT . 'Booking/fullInfoBookView/' . $objBook->getBookCode(); ?>"><i class="bi bi-eyeglasses"></i> Full Info</a>
          </td>

        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php
include("footer.php");
?>
<script src="<?php echo JS_PATH . "formScripts.js";  ?>">
</script>
<script>
  KeepersInteract.reConfirm();
</script>