<?php
include_once("header.php");
include_once("nav.php");
require_once(VIEWS_PATH . "formBookingList.php");
?>

<p class="bg-danger">
  <?php if (isset($errorMsge)) {
    echo $errorMsge;
  } ?>
</p>

<div class="table-responsive">
  <table class="table table-striped table-bordered align-middle mb-0">
    <thead class="bg-light">
      <tr>
        <th>Pet PFP / Owner name</th>
        <th>Pet code/Pet name</th>
        <th>Status</th>
        <th>Initial date</th>
        <th>End date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($myBookings === null || empty($myBookings)) { ?>
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
              <a href="<?php echo FRONT_ROOT . 'Pet/ShowPetProfile/' . $objBook->getPetCode(); ?>" class="fw-normal mb-1">
                <img src="<?php echo FRONT_ROOT . 'Images/' . $value["pfp"]; ?>" alt="petPhoto" style="width: 45px; height: 45px" class="rounded-circle" />
              </a>
              <div class="ms-3">
                <a href="<?php echo FRONT_ROOT . 'Owner/ShowOwnerProfile/' . $objBook->getOwnerCode(); ?>" class="fw-normal mb-1"><?php echo $value["ownerName"]; ?></a>
                <p class="text-muted mb-0"></p>
              </div>
            </div>
          </td>
          <td>
            <a href="<?php echo FRONT_ROOT . 'Pet/ShowPetProfile/' . $objBook->getPetCode(); ?>" class="fw-normal mb-1"><?php echo $value["petName"]; ?></a>
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
              <a href="<?php echo FRONT_ROOT . 'Booking/manageBooking/' . $objBook->getBookCode(); ?>" class="btn btn-success">Confirm</a>
            <?php } elseif ($objBook->getStatus() === "confirmed" && $loggedUser instanceof Models\Owner) { ?>
              <a href="<?php echo FRONT_ROOT . 'Coupon/showCouponFromBook/' . $objBook->getBookCode(); ?>" class="btn btn-success">Pay it</a>
            <?php } ?>
            <a class="btn btn-link" href="<?php echo FRONT_ROOT . 'Booking/fullInfoBookView/' . $objBook->getBookCode(); ?>"><i class="bi bi-eyeglasses"></i> Full Info</a>
          </td>

        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php
include("footer.php");
?>