<?php
require_once("header.php");
include("nav.php");
?>
<div class="container text-center">
    <h2 class=" mt-3 bg-dark rounded text-white">PAGO DE CUPÓN</h2>
</div>
<?php
require_once("msgeDisplay.php");

?>

<div class="container d-flex justify-content-center mt-5 mb-5">

    <div class="row g-3 p-2 rounded" style="background-color: #110257;">
        <div class="col-md-6">
            <form action="" id="payForm" method="POST">
                <span class="text-white">PAGO</span>
                <div class="card">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header p-0">
                                <h2 class="mb-0">
                                    <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <div class="d-flex align-items-center justify-content-between">

                                            <span>Tarjetas de credito</span>
                                            <div class="icons">
                                                <img src="https://i.imgur.com/2ISgYja.png" width="30" alt="mastercard">
                                                <img src="https://i.imgur.com/W1vtnOV.png" width="30" alt="visa">
                                                <img src="https://i.imgur.com/35tC99g.png" width="30" alt="stripe">
                                            </div>

                                        </div>
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body payment-card-body">

                                    <span class="font-weight-normal card-text">Numero de tarjeta</span>
                                    <div class="input m-2">

                                        <i class="fa fa-credit-card"></i>
                                        <input type="text" class="form-control" name="ccnum" id="ccnum" placeholder="0000 0000 0000 0000" required>

                                    </div>



                                    <span class="font-weight-normal card-text">Titular </span>
                                    <div class="input m-2">
                                        <i class="fa fa-credit-card"></i>
                                        <input type="text" class="form-control" name="cardholder" id="cholder" placeholder="John D. Doe" required>
                                    </div>


                                    <div class="row mt-3 mb-3">

                                        <div class="col-md-6">

                                            <span class="font-weight-normal card-text">Vencimiento</span>
                                            <div class="input">

                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control" name="expdate" id="expDate" placeholder="MM/YY" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" required>

                                            </div>

                                        </div>


                                        <div class="col-md-6">

                                            <span class="font-weight-normal card-text">CVC/CVV</span>
                                            <div class="input">

                                                <i class="fa fa-lock"></i>
                                                <input type="text" class="form-control" name="ccv" id="ccv" placeholder="000" minlength="3" maxlength="3" required>

                                            </div>

                                        </div>


                                    </div>

                                    <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Transacción asegurada con ceritifcación SSL</span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

        </div>

        <div class="alert bg-warning text-dark text-center" id="sending" hidden><h3>ENVIANDO...</h3></div>

        <div class="col-md-6">
            <span class="text-white">RESUMEN</span>

            <div class="card">

                <div class="d-flex justify-content-between p-3">

                    <div class="d-flex flex-column">

                        <span>Pago por visita</i></span>
                        <a href="#" class="billing"></a>

                    </div>

                    <div class="mt-1">
                        <sup class="super-price"><?php echo $fullCoup["totalPrice"] / 2 . '$';  ?></sup>
                        <span class="super-month">/Visit</span>
                    </div>

                </div>

                <hr class="mt-0 line">

                <div class="p-3">

                    <div class="d-flex justify-content-between mb-2">

                        <span>Bonus</span>
                        <span><?php echo $fullCoup["totalPrice"] * 0.02 . '$';  ?></span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>Pago adelantado <i class="fa fa-clock-o"></i></span>
                        <span>-2%</span>

                    </div>


                </div>

                <hr class="mt-0 line">


                <div class="p-3 d-flex justify-content-between bg-light">

                    <div class="d-flex flex-column">

                        <span>Hoy abona</span>
                        <small>Luego de 24hs +$5.00</small>

                    </div>
                    <span><?php echo ($fullCoup["totalPrice"] / 2) - $fullCoup["totalPrice"] * 0.02 . '$' ?></span>



                </div>


                <div class="p-3 text-end">

                    <button type="submit" id="submitButton" class="btn btn-success btn-block free-button " onsubmit="disableSubmitButton()">Pagar</button>

                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script src="<?php echo JS_PATH . 'formScripts.js'; ?>"></script>
<script>
    cardFuncs.sendingEmail();
    cardFuncs.manageExpire();
    cardFuncs.manageCardNumb();
    cardFuncs.manageCcv();
    cardFuncs.manageCardHolder();
</script>
<?php require_once("footer.php"); ?>