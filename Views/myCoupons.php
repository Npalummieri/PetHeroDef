<?php require_once("header.php"); ?>
<?php include("nav.php"); ?>

<h2 class="bg-dark rounded text-white text-center m-2 p-2">Mis cupones</h2>
<div class="container text-white p-4 "  style="background-color: #110257;">
<?php require_once("msgeDisplay.php"); ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Codigo cupon</th>
                    <th scope="col">Cod. Reserva</th>
                    <th scope="col">Desde</th>
                    <th scope="col">Hasta</th>
                    <th scope="col">Nombre mascota</th>
                    <th scope="col">Email cuidador</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody class="">

                <?php foreach ($myCoupons as $coupon) { ?>
                    <tr>
                        <td class="align-middle"><?php echo $coupon["couponCode"]; ?></td>
                        <td class="align-middle"><a class="link m-2" href="<?php echo FRONT_ROOT . 'Booking/fullInfoBookView/'.$coupon['bookCode'] ?>" ><?php echo $coupon["bookCode"]; ?></a></td>
                        <td class="align-middle"><?php echo $coupon["initDate"]; ?></td>
                        <td class="align-middle"><?php echo $coupon["endDate"]; ?></td>
                        <td class="align-middle"><?php echo $coupon["namePet"]; ?></td>
                        <td class="align-middle"><?php echo $coupon["emailKeeper"]; ?></td>
                        <td class="align-middle"><?php switch($coupon["statusCoup"]){
                            case "pending":
                                echo "PENDIENTE";
                                break;
                            case "paidup":
                                echo "PAGADO";
                                break;
                            case "cancelled":
                                echo "CANCELADO";
                                break;
                            case "finished":
                                echo "FINALIZADO";
                                break;
                        } ?></td>
                        <td class="align-middle">
                            <a href="<?php echo FRONT_ROOT . "Coupon/myCouponView/" . $coupon["couponCode"]; ?>" class="btn btn-primary">Administrar</a>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>
        <?php if($myCoupons == null || empty($myCoupons))
                {
                    echo "<p class='text-center'>No posee cupones </p>";
                } ?>
    </div>
</div>

<?php require_once("footer.php"); ?>