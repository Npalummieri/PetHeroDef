<?php require_once("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Booking Editing</h2>
<?php include("msgeDisplay.php"); ?>
<div class="container text-white mt-5" style="background-color: #110257;">
  <form action="<?php echo FRONT_ROOT."Booking/adminEditBooking" ?>" method="POST">
    <input type="text" class="form-control" id="bookCode" name="bookCode" value="<?php echo $booking->getBookCode(); ?>" hidden>
    <div class="form-group m-2">
      <label for="status">Status</label>
      <select class="form-control" id="status" name="status">
        <option value="confirmed" <?php if ($booking->getStatus() === 'confirmed') echo 'selected'; ?>>Confirmed</option>
        <option value="pending" <?php if ($booking->getStatus() === 'pending') echo 'selected'; ?>>Pending</option>
        <option value="cancelled" <?php if ($booking->getStatus() === 'cancelled') echo 'selected'; ?>>Cancelled</option>
		<option value="finished" <?php if ($booking->getStatus() === 'finished') echo 'selected'; ?>>Finished</option>
		<option value="paidup" <?php if ($booking->getStatus() === 'paidup') echo 'selected'; ?>>Paid Up</option>
      </select>
    </div>
    <div class="form-group m-2">
      <label for="price">Price :</label>
      <input type="number" min="1" class="form-control" id="price" name="price" placeholder="<?php echo $booking->getTotalPrice(); ?>">
    </div>
    <button type="submit" class="btn btn-primary m-2">Save changes</button>
  </form>
</div>
<?php require_once("footer.php"); ?>
