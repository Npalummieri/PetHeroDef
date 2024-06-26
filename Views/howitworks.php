<?php require_once("header.php");
include("nav.php");
?>
<!-- How it Works Section -->
<h2 class="bg-dark rounded text-center text-white m-2 p-2">How it Works</h2>
<section class="section about-section m-5"  id="how-it-works" style="background-color: #110257;">
    <div class="container" >
        <div class="row justify-content-center align-items-center p-2">
            <div class="col-lg-8">
                <p class="lead text-center text-white">
                    PetHero is an innovative online platform designed with the mission of connecting pet owners with
                    reliable and passionate pet caregivers. Our vision is to offer a safe and reliable space where pet
                    owners can find caregivers who not only provide quality services, but also share a deep love and
                    commitment to animals.
                </p>
                <p class="lead text-center text-white">
                    At PetHero, we understand that pets are more than just animals; they are beloved members of our
                    families. That's why we strive to offer a complete and satisfying experience for both pet owners and
                    caregivers. Our goal is to provide a service that not only meets the basic care needs, but also
                    promotes the well-being and happiness of pets, ensuring they receive the attention and love they
                    deserve.
                </p>
            </div>
        </div>
    </div>
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" style="background-color: #110257;">
    <div class="carousel-inner col-4  text-center">
        <?php for ($i = 0; $i < count($images); $i += 3) : ?>
            <?php $active = ($i === 0) ? 'active' : ''; ?>
            <div class="carousel-item <?php echo $active ?>">
                <div class="row m-2 p-2">
                    <?php for ($j = $i; $j < min($i + 3, count($images)); $j++) : ?>
                        <div class="col p-2">
                            
                            <!-- file_exists doesn't work with relative so had to reference ROOT instead of FRONT_ROOT -->
                            <img class="d-block mx-auto" src="<?php echo (file_exists(ROOT . 'Images/' . $images[$j])) ?  FRONT_ROOT . 'Images/' . $images[$j] : FRONT_ROOT.'Images/SysImages/labrador-retriever-scaled.jpg'; ?>" alt="Slide" width="256px" height="256px">
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

</section>




<?php
require_once("footer.php"); ?>