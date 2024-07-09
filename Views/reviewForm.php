<?php 
 include_once('nav.php');
?>

<div>
    <form action="<?php echo FRONT_ROOT.'Review/add' ?>" method="GET">
<table>
        <th style="width: 10%;">Email</th>
        <th style="width: 10%;">Nombre</th>
        <th style="width: 10%;">Apellido</th>
        <th style="width: 10%;">Foto de perfil</th>


        <tr>
            <td><?php echo $keeper->getEmail(); ?></td>
            <td><?php echo $keeper->getName(); ?></td>
            <td><?php echo $keeper->getLastname(); ?></td>
            <td class="border bordered-1 border-dark"><?php echo $keeper->getPfp(); ?></td>
        </tr>
        </tr>
    </table>

    <label for="Comment">Dejá tu comentario</label>
    <textarea name="comment" id="Comment" class="round rounded" cols="30" rows="10" maxlength="150" ></textarea>

    <input type="number" name="score" id="" min=1 max=5 required>Puntua 

    <button type="submit" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>">Enviar comentario</button>
    </form>
</div>
