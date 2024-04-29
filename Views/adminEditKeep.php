<?php include("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Keeper Editing</h2>
<?php 
include("msgeDisplay.php");
?>
<div class="container text-white mt-5" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT . "Keeper/adminEditKeeper" ?>" method="POST">
        <input type="text" class="form-control" id="keeperCode" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>" hidden>
        <div class="form-group m-2">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $keeper->getEmail(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $keeper->getUsername(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="active" <?php if ($keeper->getStatus() === 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($keeper->getStatus() === 'inactive') echo 'selected'; ?>>Inactive</option>
                <option value="suspended" <?php if ($keeper->getStatus() === 'suspended') echo 'selected'; ?>>Suspended</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $keeper->getName(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="lastname">Lastname:</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php echo $keeper->getLastname(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="status">Type care (size):</label>
            <select class="form-control" id="typeCare" name="typeCare">
                <option value="big" <?php if ($keeper->getTypeCare() === 'big') echo 'selected'; ?>>Big</option>
                <option value="medium" <?php if ($keeper->getTypeCare() === 'medium') echo 'selected'; ?>>Medium</option>
                <option value="small" <?php if ($keeper->getTypeCare() === 'small') echo 'selected'; ?>>Small</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="status">Type pet:</label>
            <select class="form-control" id="typePet" name="typePet">
                <option value="dog" <?php if ($keeper->getTypePet() === 'dog') echo 'selected'; ?>>Dog</option>
                <option value="cat" <?php if ($keeper->getTypePet() === 'cat') echo 'selected'; ?>>Cat</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="score">Score:</label>
            <input type="text" class="form-control" id="score" name="score" min="1" max="5" placeholder="<?php echo $keeper->getScore(); ?>">
        </div>
		<div class="form-group m-2">
            <label for="score">Price :</label>
            <input type="text" class="form-control" id="price" name="price" placeholder="<?php echo $keeper->getPrice(); ?>">
        </div>
        <button type="submit" class="btn btn-primary m-2">Save changes</button>
    </form>
</div>
<?php include("footer.php"); ?>
