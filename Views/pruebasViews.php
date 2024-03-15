<?php
//include_once("header.php");
?>

<p>PRUEBA EMAIL <a href="Utils\PHPMailer\Mailer.php">HOLAAAAAAA</a></p>
<table style="text-align:center;">
            <thead>
              <tr>
                <th style="width: 10%;">Email</th>
                <th style="width: 10%;">Name</th>
                <th style="width: 10%;">Lastname</th>
                <th style="width: 10%;">Pfp</th>
                <th style="width: 10%;">Typecare</th>
                <th style="width: 10%;">Price</th>
                <!-- Deberia tener el de puntajes -->
                <!-- Podria displayear la disponibilidad buscandolo en el DAO Availability -->

                      <th style="width: 10%;">Day</th>
                      <th style="width: 10%;">InitHour</th>
                      <th style="width: 10%;">EndHour</th>
              </tr>


            </thead>

            <tbody>
<p><?php foreach($keeperList as $keeper){?>

                    <td><?php echo $keeper->getEmail(); ?></td>
                      <td><?php echo $keeper->getName(); ?></td>
                      <td><?php echo $keeper->getLastname(); ?></td>
                      <td><?php echo $keeper->getPfp(); ?></td>
                      <td><?php echo $keeper->getTypeCare(); ?></td>
                      <td><?php echo $keeper->getPrice(); ?></td>
 <?php
    }?>
</p>

</tbody>
<?php
include_once(VIEWS_PATH."footer.php");
?>