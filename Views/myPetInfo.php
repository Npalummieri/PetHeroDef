<?php include("header.php");
include("nav.php") ?>
<form action="<?php echo FRONT_ROOT . "Pet/UpdatePetInfo" ?>" method="POST" enctype="multipart/form-data">

  <h2>UPDATE YOUR PET INFO</h2>

  <div class="form-group">
    <label for="size">Size</label>
    <select name="size" id="size">
      <option value="s">Small</option>
      <option value="m">Medium</option>
      <option value="b">Big</option>
    </select>
  </div>


  <div class="form-group">
    <label for="vaccPlan">Vaccine Plan</label>
    <img src="<?php echo $value["vaccPlan"]; ?>" alt="vaccPlan">
    <input type="file" name="vaccPlan" placeholder="Vaccine Plan">
  </div>

  <div class="form-group">
    <label for="video">Video</label>
    <img src="<?php echo $value["video"]; ?>" alt="video">
    <input type="file" name="video" placeholder="Video (optional)">
  </div>

  <div class="form-group">
    <label for="pfp">Profile picture</label>
    <img src="<?php echo $value["pfp"]; ?>" alt="pfp">
    <input type="file" name="pfp" class="form-control form-control-lg" placeholder="Profile picture">
  </div>

  <div class="form-group">
    <label for="Age">Age</label>
    <input type="number" name="age" class="form-control form-control-lg" placeholder="<?php echo $value["age"]; ?>" id="Age">
  </div>


  <button type="submit">Update info</button>
</form>
<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>