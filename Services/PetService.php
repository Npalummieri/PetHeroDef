<?php

namespace Services;

use Models\Pet as Pet;
use \Exception as Exception;
use Services\OwnerService as OwnerService;
use DAO\PetDAO as PetDAO;
use Models\Size as Size;

class PetService
{

    private $ownerService;
    private $petDAO;

    public function __construct()
    {
        $this->petDAO = new PetDAO();
        $this->ownerService = new OwnerService();
    }

    public function generateCode()
    {
        // Genera un UUID único
        $uuid = uniqid('PET', true);

        // Devuelve el ownerCode generado
        return $uuid;
    }

    public function getBreedsDog()
    {
        $content = file_get_contents(FRONT_ROOT . "DAOJson\dogBreeds.json");

        $decodedContent = json_decode($content, true);

        $dogBreedArray = array();
        foreach ($decodedContent as $id => $breed) {
            array_push($dogBreedArray, $breed);
        }

        return $dogBreedArray;
    }

    public function getBreedsCat()
    {
        $content = file_get_contents(FRONT_ROOT . "DAOJson\catBreeds.json");

        $decodedContent = json_decode($content, true);

        $catBreedArray = array();
        foreach ($decodedContent as $breed) {
            array_push($catBreedArray, $breed);
        }

        return $catBreedArray;
    }


    private function getImgsPetRegister($typeFile, $fileInfo, $typePet)
    {

        $pathToBD = null;
        $pathToSave = null;
        $nameFile = $fileInfo["name"];
        $paths = array();
        $temp = null;
        //Allowed mimes
        $imgTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];


        //null != empty
        if ($fileInfo != null) {

            //Not name,nothing uploaded
            if (!empty($fileInfo["name"])) {
                $mime = mime_content_type($fileInfo["tmp_name"]);
            } else {
                $mime = null;
            }


            if ($mime != null) {

                //obtain extension
                $extension = explode(".", $nameFile);
                // echo "<br> check Extension : <br>";


                //Filter size
                if ($fileInfo["size"] < 10242880 && $fileInfo["size"] > 0) {

                    if (in_array($mime, $imgTypes)) {
                        $imgSize = $fileInfo["size"];
                        $typeImg = $fileInfo["type"];
                        $dim = getimagesize($fileInfo["tmp_name"]);
                        $temp = $fileInfo["tmp_name"];
                        $width = $dim[0];
                        $height = $dim[1];
                        $hashedNameFile = hash_file('sha1', $temp);


                        if ($typeFile == "pfp") {
                            if (!file_exists(PFP_PETS)) {
                                mkdir(PFP_PETS, 0777, true); //make directory
                            }
                            $pathToSave = PFP_PETS . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "PetImages/PFPets/" . $hashedNameFile . '.' . $extension[1];
                            //move_uploaded_file($temp, $pathToSave);

                        } else if ($typeFile == "vaccPlan") {
                            if (!file_exists(VACCS_PLAN)) {
                                mkdir(VACCS_PLAN, 0777, true);
                            }
                            $pathToSave = VACCS_PLAN . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "PetImages/Vaccplan/" . $hashedNameFile . '.' . $extension[1];
                            //move_uploaded_file($temp, $pathToSave);                            
                        }
                    } else if ($typeFile == "video") {
                        if ($mime == "video/mp4" || $mime = "application/octet-stream") {

                            //Dir almacen temp
                            $temp = $fileInfo["tmp_name"];
                            $hashedNameFile = hash_file('sha1', $temp);

                            if (!file_exists(VIDEO_PATH)) {
                                mkdir(VIDEO_PATH, 0777, true);
                            }
                            $pathToSave = VIDEO_PATH . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "Videos/" . $hashedNameFile . '.' . $extension[1];

                            //move_uploaded_file($temp, $pathToSave);
                        } else {
                            $error = ".MP4 unicamente. Revise su video";
                            $pathToBD = null;
                        }
                        //move_uploaded_file($temp, $pathToSave);
                    } else {
                        $error = "Problem with file size!";
                    }
                }
            }
        }
        $paths["file"] = $temp;
        $paths["pathToDB"] = $pathToBD;
        $paths["pathToSave"] = $pathToSave;

        return $paths;
    }


    public function validatePet($name, $typePet, $ownerCode, $size, $breed, $vaccPlan, $video, $pfp, $age)
    {

        try {
            $pet = new Pet();
            $error = null;
            //Validate pet's name
            if (strlen($name) > 1 && strlen($name) < 30 && preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/",$name)) {
                $pet->setName($name);
            } else {
                $error .= " Error en el nombre,30 caracteres máximo. Caracteres especiales no permitidos. Unicamente guiones y apostrofes ";
            }

            //Validate type
            if ($typePet !== "dog" && $typePet !== "cat") {
                $error .= " Error en el tipo de mascota ";
            } else {
                $pet->setTypePet($typePet);
            }

            //If the user is active
            if ($this->ownerService->getByCode($ownerCode) != null) {
                trim($ownerCode);
                $pet->setOwnerCode($ownerCode);
            } else {
                $error .= "Su usuario no esta activado. Comuniquese con soporte";
            }


            //Checking sizes
            if ($size != Size::BIG && $size != Size::MEDIUM && $size != Size::SMALL) {
                $error .= " Tamaño no valido ";
            } else {
                $pet->setSize($size);
            }

            //Revalidate breed's name (depends by typePet)
            if ($typePet == "cat") {
                $content = file_get_contents("DAOJson\catBreeds.json");
                $decodedContent = json_decode($content, true);

                foreach ($decodedContent as $breedArray) {

                    if ($breedArray["name"] === $breed) {
                        $pet->setBreed($breed);
                    }
                }
                if ($pet->getBreed() == null) {
                    $error = " Raza no valida ";
                }
            } else if ($typePet == "dog") {
                $content = file_get_contents("DAOJson\dogBreeds.json");
                $decodedContent = json_decode($content, true);

                foreach ($decodedContent as $breedArray) {

                    if ($breedArray["name"] === $breed) {
                        $pet->setBreed($breed);
                    }
                }
                if ($pet->getBreed() == null) {
                    $error = " Raza no valida ";
                }
            } else {
                $error .=  " Raza no valida ";
            }

            //Setting age -validate if it's a number
            if(ctype_digit($age))
            {
                $pet->setAge($age);
            }else{
                $error .=  " Edad invalida. Digitos unicamente ";
            }
            

            if ($error == null) {
                $pet->setPetCode($this->generateCode());
                $resultInsert = $this->petDAO->Add($pet);
            } else {
                $error .= " <br> No se pudo agregar a su mascota. Intente nuevamente";
            }

            if ($resultInsert != null && $resultInsert != " ") {
                if (isset($pfp)) {

                    $arrayPaths = $this->getImgsPetRegister("pfp", $pfp, $typePet);
                    move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                    $this->petDAO->updatePfp($resultInsert, $arrayPaths["pathToDB"]);
                    $pet->setPfp($arrayPaths["pathToDB"]);
                }

                if (isset($video)) {

                    $arrayPaths = $this->getImgsPetRegister("video", $video, $typePet);
                    if ($arrayPaths["file"] == null) {
                        $error .=  "Mascota agregada. Video no subido. Actualicelo en el perfil de su mascota";
                    } else {
                        $this->petDAO->updateVideo($resultInsert, $arrayPaths["pathToDB"]);
                        move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                        $pet->setVideo($arrayPaths["pathToDB"]);
                    }
                }
                if (isset($vaccPlan)) {

                    $arrayPaths = $this->getImgsPetRegister("vaccPlan", $vaccPlan, $typePet);
                    move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                    $this->petDAO->updateVacc($resultInsert, $arrayPaths["pathToDB"]);
                    $pet->setVaccPlan($arrayPaths["pathToDB"]);
                }
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
            $petAdded = $this->petDAO->searchByCode($resultInsert);
            if ($petAdded->getPfp() == null || $petAdded->getVaccPlan() == null) {
                $error = "Cargue las imagenes correspondientes a su mascota. Mascota añadida";
            }
        }

        return $error;
    }

    public function getAllByOwner($ownerCode)
    {
        return $this->petDAO->getAllByOwner($ownerCode);
    }

    public function petsByOwnAndType($ownerCode, $typePet)
    {
        $array = $this->petDAO->getAllByTypeAndOwner($ownerCode, $typePet);


        $arrayToEncode = array();
        foreach ($array as $pet) {
            $valsToEncode["petCode"] = $pet->getPetCode();
            $valsToEncode["name"] = $pet->getName();
            //$encodedObj = json_encode($valsToEncode);
            array_push($arrayToEncode, $valsToEncode);
        }

        $encodedArray = json_encode($arrayToEncode);
        //Supuestamente si no existe el archivo lo crea,pero nop,lo tuve que hacer manual
        file_put_contents("DAOJson\petsByOwnAndType.json", $encodedArray);


        return $encodedArray;
    }

    public function srv_getPetsByOwnFilters($ownerCode, $typePet, $typeSize)
    {
        try {
            $array = $this->petDAO->getPetsFilteredOwner($ownerCode, $typePet, $typeSize);


            $arrayToEncode = array();
            foreach ($array as $pet) {
                $valsToEncode["petCode"] = $pet->getPetCode();
                $valsToEncode["name"] = $pet->getName();
                //$encodedObj = json_encode($valsToEncode);
                array_push($arrayToEncode, $valsToEncode);
            }

            $encodedArray = json_encode($arrayToEncode);


            file_put_contents("DAOJson\petsByOwnAndType.json", $encodedArray);


            return $encodedArray;
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    //Valida que el codigo de pet este en relacion con el ownerCode en la BD que quiera modificar algo de sus mascotas
    public function srv_validatePetOwner($petCode, $ownerCode)
    {
        try {
            $result = $this->petDAO->checkOwnerByPet($petCode, $ownerCode);
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }

    public function srv_updateVacc($petCode, $ownerCode, $vaccPlan)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("Error con la carga de su plan. Intente nuevamente");
            } else {
                return $this->petDAO->updateVacc($petCode, $vaccPlan);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function srv_updatePfp($petCode, $ownerCode, $pfp)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("Error con la carga de su foto. Intente nuevamente");
            } else {
                return $this->petDAO->updatePfp($petCode, $pfp);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function srv_updateAge($petCode, $ownerCode, $age)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("Error actualizando la edad. Intente nuevamente");
            } else {
                return $this->petDAO->updateAge($petCode, $age);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function srv_updateVideo($petCode, $ownerCode, $video)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("Error actualizando el video. Intente nuevamente");
            } else {
                return $this->petDAO->updateVideo($petCode, $video);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function srv_checkOwnerPet($petCode, $ownerCode)
    {
        try {
            $result = null;

            if (strpos($ownerCode, "OWN") !== false) {
                $result = $this->petDAO->checkOwnerByPet($petCode, $ownerCode);
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }



    public function srv_updatePetInfo($petCode, $ownerCode, $size, $vaccPlan, $video, $pfp, $age)
    {
        try {

            $petSearched = $this->petDAO->searchByCode($petCode);
            $error = 1;
            $typePet = $petSearched->getTypePet();
            $pfpToDelete = $petSearched->getPfp();
            $videoToDelete = $petSearched->getVideo();
            $vaccPlanToDelete = $petSearched->getVaccPlan();
            if ($petSearched != null) {
                //Si esta seteado,si no esta vacio y si efectivamente pesa algo 
                //Saltan warnings logicos si es que no habia nada para borrar,pero funcionan no es critico
                if (isset($pfp["tmp_name"]) && $pfp["size"] > 0 && !empty($pfp["tmp_name"])) {

                    $arrayPaths = $this->getImgsPetRegister("pfp", $pfp, $typePet);


                    $result = $this->petDAO->updatePfp($petCode, $arrayPaths["pathToDB"]);

                    if ($result == 1) {
                        move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                        if (unlink(IMG_PATH . $pfpToDelete)) {
                            $error = "Foto de perfil eliminada";
                        }
                    } else {
                        $error .= "Error actualizando foto de perfil";
                    }
                }


                if (isset($video["tmp_name"]) && $video["size"] > 0 && !empty($video["tmp_name"])) {

                    $arrayPathsVideo = $this->getImgsPetRegister("video", $video, $typePet);
                    if ($arrayPathsVideo["file"] == null) {
                        $error =  "Video sin subir. Puede agregarlo más tarde";
                    } else {
                        $resultVideo = $this->petDAO->updateVideo($petCode, $arrayPathsVideo["pathToDB"]);
                        if ($resultVideo == 1) {
                            move_uploaded_file($arrayPathsVideo["file"], $arrayPathsVideo["pathToSave"]);

                            if (unlink(ROOT . $videoToDelete)) {
                                $error = "Video eliminado";
                            }
                        } else {
                            $error .= "Error en la carga del video. Intente nuevamente";
                        }
                    }
                }
                if (isset($vaccPlan["tmp_name"]) && $vaccPlan["size"] > 0 && !empty($vaccPlan["tmp_name"])) {

                    $arrayPaths = $this->getImgsPetRegister("vaccPlan", $vaccPlan, $typePet);
                    $result = $this->petDAO->updateVacc($petCode, $arrayPaths["pathToDB"]);
                    if ($result == 1) {
                        move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                        if (unlink(IMG_PATH . $vaccPlanToDelete)) {
                            $error = "Plan de vacunación actualizado";
                        }
                    } else {
                        $error .= "Error actualizando su plan. Intente nuevamente";
                    }
                }
            }

            //If everything OK ,error takes value = 1
            if ($result == 1) {
                $error = $result;
            }

            if (isset($size) && !empty($size)) {
                $this->petDAO->updateSize($petCode, $size);
            }

            if (isset($age) && !empty($age)) {
                $this->petDAO->updateAge($petCode, $age);
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
        }
        return $error;
    }


    public function srv_getPet($petCode)
    {
        try {
            $pet = $this->petDAO->searchByCode($petCode);
        } catch (Exception $ex) {
            $pet = $ex->getMessage();
        }
        return $pet;
    }

    public function srv_deletePet($ownerCode, $petCode)
    {
        try {
            if (strpos($ownerCode, "OWN") !== false) {
                if ($this->srv_checkOwnerPet($petCode, $ownerCode) == 1) {
                    if($this->petDAO->checkPetBookings($petCode) == 0)
                    {
                        $resp = $this->petDAO->delete($petCode);
                    }else{
                        $resp = "No se puede eliminar. Tiene una reserva en curso";
                    }
                    
                } else {
                    $resp = "Esta mascota no pertenece a este usuario. Redireccionado";
                }
            } else {
                $resp = "Error en la sesión. Redireccionado";
            }
        } catch (Exception $ex) {
            $resp = $ex->getMessage();
        }
        return $resp;
    }

    public function srv_getProfilePet($petCode)
    {
        try {
            $pet = $this->petDAO->searchByCode($petCode);
        } catch (Exception $ex) {
            $pet = $ex->getMessage();
        }
        return $pet;
    }

    public function srv_getAllPetsPfp()
    {
        try {
            $images = $this->petDAO->getAllPfps();
        } catch (Exception $ex) {
            $images = $ex->getMessage();
        }
        return $images;
    }
	
	public function srv_getAllPets()
	{
		try{
			$petList = $this->petDAO->GetAll();
		}catch(Exception $ex)
		{
			$petList = $ex->getMessage();
		}
		return $petList;
		
	}
	
	public function listPetFiltered($code)
	{
        try {
        if (strpos($code, "PET") !== false || 
			strpos($code, "OWN") !== false)
			{
				$petList = $this->petDAO->getFilteredPetsAdm($code);
			}else {
				$petList = "No hubo coincidencias recuerde usar los valores OWN,PET,KEP,BOOK";
				}
        }catch(Exception $ex)
		{
			$petList = $ex->getMessage();
		}
		return $petList;
	}

    public function srv_editName($petCode,$name){
        try{
			$regexName = "/[a-zA-ZÀ-ÿ\s'-]/";
			if (preg_match($regexName, $name)) {

                    $name = trim($name);
					$resp = $this->petDAO->updateName($petCode,$name);
			}else{
				$resp = "Nombre de mascota no respeta los requisitos. Unicamente apostrofes y guiones permitidos.";
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
    }

    public function srv_editBreed($petCode, $typePet, $breed)
    {
        try {
            $dataJson = file_get_contents("DAOJson/$typePet"."Breeds.json");
            
            $decodedJson = json_decode($dataJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $resp = "Error en la obtencion de razas. Intente más tarde";
            } else {
                
                $resp = $this->petDAO->updateBreed($petCode, $breed);
            }
        } catch (Exception $ex) {
            $resp = "Error en la actualizacion de raza. Contactar a soporte";
        }
        return $resp;
    }

    public function srv_editSize($petCode,$size){
        try{
			if($size != Size::BIG && $size != Size::MEDIUM && $size != Size::SMALL)
            {
                $resp = "Tamaño no valido, utilice los valores de la lista.";
            }else{
                $resp = $this->petDAO->updateSize($petCode,$size);
            }
		}catch(Exception $ex)
		{
			$resp = "Error en la actualizacion de tamaño. Contactar a soporte";
		}
		return $resp;
    }

    public function srv_editAge($petCode,$age){
        try{
			if(ctype_digit($age))
            {
                $this->petDAO->updateAge($petCode,$age);
            }else{
                $resp = "Edad no valida.";
            }
		}catch(Exception $ex)
		{
			$resp = "Error en la actualizacion de edad. Contactar a soporte";
		}
		return $resp;
    }
}
