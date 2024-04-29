<?php include("header.php"); ?>

<a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="text-center text-white p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> DASHBOARD</a>
<div class="container">
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">COUPON LIST</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Coupon/listCouponsFiltered" ?>" method=GET >
                    <input class="form-control me-2" type="text" name="code"  placeholder="Insert code (COU,BOOK)" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="table-responsive rounded p-2" style="background-color: #110257;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Coupon Code</th>
                    <th>Book Code</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle;">
                <?php foreach($listCoups as $coupon){ ?>
                <tr>
                    <td><?php echo $coupon->getId() ?></td> 
                    <td><?php echo $coupon->getCouponCode(); ?></td>
                    <td><?php echo $coupon->getBookCode(); ?></td>
                    <td><?php echo $coupon->getPrice(); ?></td>
                    <td><?php echo $coupon->getStatus(); ?></td>
                    <td style="vertical-align: middle;">
                        <a class="btn btn-dis btn-primary m-2" data-msg="Edit this record?" href="<?php echo FRONT_ROOT."Coupon/showAdminEditCoup/".$coupon->getCouponCode() ?>">Edit</a> 
                        <a class="btn btn-dis btn-danger m-2" data-msg = "Record will be removed forever. Sure?" href="<?php echo FRONT_ROOT."Coupon/declineCoupon/".$coupon->getCouponCode() ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script>KeepersInteract.reConfirm();</script>
<?php include("footer.php"); ?>
