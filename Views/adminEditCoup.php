<?php include("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Coupon Editing</h2>
<?php include("msgeDisplay.php"); ?>
<div class="container text-white mt-5" style="background-color: #110257;">
  <form action="<?php echo FRONT_ROOT."Coupon/adminEditCoupon" ?>" method="POST">
    <input type="text" class="form-control" id="coupCode" name="coupCode" value="<?php echo $coupon->getCouponCode(); ?>" hidden>
    <div class="form-group m-2">
      <label for="status">Status</label>
      <select class="form-control" id="status" name="status">
        <option value="pending" <?php if ($coupon->getStatus() === 'pending') echo 'selected'; ?>>Pending</option>
        <option value="paidup" <?php if ($coupon->getStatus() === 'paidup') echo 'selected'; ?>>Paid Up</option>
        <option value="finished" <?php if ($coupon->getStatus() === 'finished') echo 'selected'; ?>>Finished</option>
        <option value="rejected" <?php if ($coupon->getStatus() === 'rejected') echo 'selected'; ?>>Rejected</option>
      </select>
    </div>
    <div class="form-group m-2">
      <label for="price">Price :</label>
      <input type="number" min="1" class="form-control" id="price" name="price" value="<?php echo $coupon->getPrice(); ?>">
    </div>
    <button type="submit" class="btn btn-primary m-2">Save changes</button>
  </form>
</div>
<?php include("footer.php"); ?>
