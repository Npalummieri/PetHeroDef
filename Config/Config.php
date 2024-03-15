<?php 

namespace Config;

define("ROOT", dirname(__DIR__) . "/");
//Path to your project's root folder
define("FRONT_ROOT", "/LabIV/ReDefTesis/PetHeroDef/");
define("VIEWS_PATH", "Views/");
define("CSS_PATH", FRONT_ROOT.VIEWS_PATH . "css/");
define("JS_PATH", FRONT_ROOT.VIEWS_PATH . "js/");
define("IMG_PATH",ROOT."Images/");
define("VIDEO_PATH",ROOT."Videos/");
define("PFP_KEEPERS",IMG_PATH."PFPKeepers/");
define("PFP_OWNERS",IMG_PATH."PFPOwners/");
define("PFP_PETS",IMG_PATH."PetImages/PFPets/");
define("VACCS_PLAN",IMG_PATH."PetImages/Vaccplan/");

define("DB_HOST", "localhost");
define("DB_NAME", "pethero");
define("DB_USER", "root");
define("DB_PASS", "");



?>

