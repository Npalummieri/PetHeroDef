<?php 
 include('nav.php');
?>

<div>
    <form action="<?php echo FRONT_ROOT.'Review/add' ?>" method="GET">
<table>
        <th style="width: 10%;">Email</th>
        <th style="width: 10%;">Name</th>
        <th style="width: 10%;">Lastname</th>
        <th style="width: 10%;">Pfp</th>


        <tr>
            <!-- Podria enviar el keeperCode por parametro... -->
            <!-- <input type="hidden" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>"> -->
            <td><?php echo $keeper->getEmail(); ?></td>
            <td><?php echo $keeper->getName(); ?></td>
            <td><?php echo $keeper->getLastname(); ?></td>
            <td><?php echo $keeper->getPfp(); ?></td>
        </tr>
        </tr>
    </table>

    <label for="Comment">What's your opinion...?</label>
    <textarea name="comment" id="Comment" cols="30" rows="10" maxlength="150" ></textarea>

    <input type="number" name="score" id="" min=1 max=5 required>Score it 

    <!-- Averiguar la interaccion de como funciona pq no funciona como un <a>
    <form action="<?php echo FRONT_ROOT.'Review/add/'.$keeper->getKeeperCode() ?>" method="GET"> -->
    <button type="submit" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>">Send review</button>
    </form>
</div>
<!-- Perfil keeper -->
<!-- Puntuacion -->
<!-- Review texto  -->
<!-- Buscar si se puede hacer la verificacion en el momento (cada vez que se modifique la URL usar checkDoReview para ver si da 1) -->
<!-- El form deberia mandarse a reviewController add -->