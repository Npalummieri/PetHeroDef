<?php include("header.php"); ?>
<?php include("nav.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">My Coupons</h2>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Coupon Code</th>
                    <th>Booking Code</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Hour</th>
                    <th>End Hour</th>
                    <th>Pet Name</th>
                    <th>Keeper Email</th>
                    <th>Status</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myCoupons as $coupon) { ?>
                    <tr>
                        <td><?php echo $coupon["couponCode"]; ?></td>
                        <td><?php echo $coupon["bookCode"]; ?></td>
                        <td><?php echo $coupon["initDate"]; ?></td>
                        <td><?php echo $coupon["endDate"]; ?></td>
                        <td><?php echo $coupon["initHour"]; ?></td>
                        <td><?php echo $coupon["endHour"]; ?></td>
                        <td><?php echo $coupon["namePet"]; ?></td>
                        <td><?php echo $coupon["emailKeeper"]; ?></td>
                        <td><?php echo $coupon["statusCoup"]; ?></td>
                        <td>
                            <a href="<?php echo FRONT_ROOT . "Coupon/myCouponView/" . $coupon["couponCode"]; ?>" class="btn btn-primary">Manage</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("footer.php"); ?>
