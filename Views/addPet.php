<?php require_once("header.php");
include("nav.php") ?>
<form action="<?php echo FRONT_ROOT . "Pet/add" ?>" method="POST" enctype="multipart/form-data">
 <div  id="baseUrl" data-baseurl="<?php echo FRONT_ROOT ?>" hidden> </div>
<div class="container text-white">
  <div class="row justify-content-center" >
    <div class="col-md-6">
      <div class="p-4">
        <h2 class="bg-dark text-lg rounded text-center p-2">REGISTRO DE MASCOTA</h2>
        <div class="form-container border border-dark rounded p-5 m-auto mt-2" style="background-color:  #110257;">
        <form >
          <div class="mb-3 ">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Nombre" maxlength="30" required>
          </div>

          <div class="mb-3 text-center">
            <label class="form-label">Tipo de mascota</label>
            <div>
              <label class="form-check-label" for="dog">Perro</label>
              <input type="radio" class="form-check-input" name="typePet" id="dog" value="dog" required>
              <label class="form-check-label" for="cat">Gato</label>
              <input type="radio" class="form-check-input" name="typePet" id="cat" value="cat">
            </div>
          </div>

          <div class="mb-3">
            <label for="size" class="form-label">Tamaño</label>
            <select class="form-select" id="size" name="size" required>
              <option value="">Seleccione tamaño</option>
              <option value="small">Pequeño (hasta 8kg)</option>
              <option value="medium">Mediano (hasta 15kg)</option>
              <option value="big">Grande (más de 15kg)</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="breed" class="form-label">Raza</label>
            <select class="form-select" id="breed" name="breed" required>
            </select>
          </div>

          <div class="mb-3">
            <label for="vaccPlan" class="form-label">Plan de vacunación</label>
            <input type="file" class="form-control" id="vaccPlan" name="vaccPlan" placeholder="Plan de vacunación" required>
          </div>

          <div class="mb-3">
            <label for="video" class="form-label">Video</label>
            <input type="file" class="form-control" id="video" name="video" placeholder="Video (opcional)">
          </div>

          <div class="mb-3">
            <label for="pfp" class="form-label">Foto de perfil</label>
            <div class="input-group">
              <input type="file" class="form-control" id="pfp" name="pfp" placeholder="Foto de perfil" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="age" class="form-label">Edad</label>
            <div class="input-group">
              <input type="number" class="form-control" id="age" name="age" placeholder="Edad" required>
            </div>
          </div>

          <div class="text-end">
          <button type="submit" class="btn btn-success">Agregar mascota</button>
          </div>
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
  registerPetForm.limitAge();
  registerPetForm.filterName();
</script>
<?php require_once("footer.php") ?>