<?php include("header.php");
include("nav.php") ?>
<form action="<?php echo FRONT_ROOT . "Pet/add" ?>" method="POST" enctype="multipart/form-data">
 
<div class="container text-white">
  <div class="row justify-content-center" >
    <div class="col-md-6">
      <div class="p-4">
        <h2 class="text-center">ADD YOUR PET</h2>
        <div class="form-container border border-dark p-5 m-auto mt-2" style="background-color: #364a6e;">
        <form >
          <div class="mb-3 ">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Name" maxlength="30" required>
          </div>

          <div class="mb-3 text-center">
            <label class="form-label">Type Pet</label>
            <div>
              <label class="form-check-label" for="dog">Dog</label>
              <input type="radio" class="form-check-input" name="typePet" id="dog" value="dog" required>
              <label class="form-check-label" for="cat">Cat</label>
              <input type="radio" class="form-check-input" name="typePet" id="cat" value="cat">
            </div>
          </div>

          <div class="mb-3">
            <label for="size" class="form-label">Size</label>
            <select class="form-select" id="size" name="size" required>
              <option value="">Select size</option>
              <option value="small">Small</option>
              <option value="medium">Medium</option>
              <option value="big">Big</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="breed" class="form-label">Breed</label>
            <select class="form-select" id="breed" name="breed" required>
            </select>
          </div>

          <div class="mb-3">
            <label for="vaccPlan" class="form-label">Vaccine Plan</label>
            <input type="file" class="form-control" id="vaccPlan" name="vaccPlan" placeholder="Vaccine Plan" required>
          </div>

          <div class="mb-3">
            <label for="video" class="form-label">Video</label>
            <input type="file" class="form-control" id="video" name="video" placeholder="Video (optional)">
          </div>

          <div class="mb-3">
            <label for="pfp" class="form-label">Profile picture</label>
            <div class="input-group">
              <input type="file" class="form-control" id="pfp" name="pfp" placeholder="Profile picture" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <div class="input-group">
              <input type="number" class="form-control" id="age" name="age" placeholder="Age" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">ADD PET</button>
        </form>
      </div>
    </div>
  </div>
</div>
</form>
</div>
<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
  breedManage.preloadBreed();
  registerPetForm.limitage();
</script>