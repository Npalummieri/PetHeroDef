<?php require_once("header.php");
include("nav.php");
?>
<!-- How it Works Section -->
<h1 class="bg-dark rounded text-center text-white m-2 p-2">Propósito</h1>
<section class="section about-section rounded round-3 m-5" id="how-it-works" style="background-color: #110257;">
    <div class="container">
        <div class="row justify-content-center align-items-center p-2">
            <div class="col-lg-8">
                <p class="text-center text-white">
                    PetHero es una plataforma en línea,innovadora y diseñada con la misión de conectar a los dueños de mascotas con cuidadores confiables y apasionados. Nuestra visión es ofrecer un espacio seguro donde los dueños de mascotas puedan encontrar responsables que brinden servicios de calidad y también compartan un profundo amor y compromiso con los animales.
                </p>
                <p class="text-center text-white">
                    En PetHero, entendemos que las mascotas son más que solo animales; son miembros queridos de nuestras familias. Por eso, nos esforzamos por ofrecer una experiencia completa tanto para los dueños de mascotas como para los cuidadores. Nuestro objetivo es proporcionar un servicio que promueva el bienestar y la felicidad de las mascotas, asegurándonos de que reciban la atención y el amor que merecen.
                </p>

            </div>
        </div>
    </div>
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" style="background-color: #110257;">
        <div class="carousel-inner col-4  text-center">
            <?php if (count($images) > 0) {

                for ($i = 0; $i < count($images); $i += 3) : ?>
                    <?php $active = ($i === 0) ? 'active' : ''; ?>
                    <div class="carousel-item <?php echo $active ?>">
                        <div class="row m-2 p-2">
                            <?php for ($j = $i; $j < min($i + 3, count($images)); $j++) : ?>
                                <div class="col p-2">

                                    <!-- file_exists doesn't work with relative so had to reference ROOT instead of FRONT_ROOT -->
                                    <img class="d-block mx-auto" src="<?php echo (file_exists(ROOT . 'Images/' . $images[$j])) ?  FRONT_ROOT . 'Images/' . $images[$j] : FRONT_ROOT . 'Images/SysImages/labrador-retriever-scaled.jpg'; ?>" alt="Slide" width="256px" height="256px">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>
<?php } ?>

</section>




<?php
require_once("footer.php"); ?>