<?php 
 include_once('nav.php');
?>

<div>
    <form action="<?php echo FRONT_ROOT.'Review/add' ?>" method="GET">
<table>
        <th style="width: 10%;">Email</th>
        <th style="width: 10%;">Name</th>
        <th style="width: 10%;">Lastname</th>
        <th style="width: 10%;">Pfp</th>


        <tr>
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

    <button type="submit" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>">Send review</button>
    </form>
</div>
