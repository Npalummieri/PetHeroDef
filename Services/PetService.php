<?php

namespace Services;

use Models\Pet as Pet;
use \Exception as Exception;
use Services\OwnerService as OwnerService;
use DAO\PetDAO as PetDAO;

class PetService{

    private $ownerService;
    private $petDAO;

    public function __construct()
    {
        $this->petDAO = new PetDAO();
        $this->ownerService = new OwnerService();
    }

    public function generateCode() {
        // Genera un UUID único
        $uuid = uniqid('PET', true); // Utiliza 'KEP' como prefijo
    
        // Devuelve el ownerCode generado
        return $uuid;
    }

    public function getBreedsDog()
    {
        $content = file_get_contents(FRONT_ROOT."DAOJson\dogBreeds.json");

        $decodedContent = json_decode($content,true);

        $dogBreedArray = array();
        foreach($decodedContent as $id => $breed)
        {
            array_push($dogBreedArray,$breed);
        }

        return $dogBreedArray;
    }

    public function getBreedsCat()
    {
        $content = file_get_contents(FRONT_ROOT."DAOJson\catBreeds.json");

        $decodedContent = json_decode($content,true);

        $catBreedArray = array();
        foreach($decodedContent as $breed)
        {
            array_push($catBreedArray,$breed);
        }

        return $catBreedArray;
    }

    //Revisar si typeFile deberia ser el tipo verdaderamente del archivo y si es necesario agregar un
    //typeFile2 donde verifica si es una PFP,VaccPlan o Galeria
    private function getImgsPetRegister($typeFile, $fileInfo, $typePet)
    {

        $pathToBD = null;
        $pathToSave = null;
        $nameFile = $fileInfo["name"];
        $paths = array();
        $temp = null;
        echo "FILEINFO :";
        var_dump($fileInfo);
        echo "NAMEFILE :" . $nameFile;
        //Tipo de mime permitidos
        $imgTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];


        //Seguramente se arme el arreglo $_FILES pero vacio 
        if ($fileInfo != null) {

            //Si nombre no tiene es pq no subió nada entonces ya el mime no se asigna y queda vacio
            if (!empty($fileInfo["name"])) {
                $mime = mime_content_type($fileInfo["tmp_name"]);
            } else {
                $mime = null;
            }

            echo "<br> MIME PRE IF :<br>" . $mime;
            if ($mime != null) {

                //Obtenemos la extension para concatenar luego
                $extension = explode(".", $nameFile);
                echo "<br> check Extension : <br>";
                var_dump($extension);

                //Si tiene cierto tamaño pasa
                if ($fileInfo["size"] < 5242880 && $fileInfo["size"] > 0) {
                    //Verifico que el archivo subido (sea de algun tipo $imgTypes)
                    if (in_array($mime, $imgTypes)) {
                        $imgSize = $fileInfo["size"];
                        $typeImg = $fileInfo["type"];
                        $dim = getimagesize($fileInfo["tmp_name"]);
                        $temp = $fileInfo["tmp_name"];
                        $width = $dim[0];
                        $height = $dim[1];
                        $hashedNameFile = hash_file('sha1', $temp);

                        echo "<br>FILE SIZE = <br>" . $imgSize;
                        echo "<br>FILE TYPE = <br>" . $typeImg;


                        echo "<br>HashednameFile : " . $hashedNameFile;
                        if ($typeFile == "pfp") {
                            if(!file_exists(PFP_PETS))
                            {
                                mkdir(PFP_PETS, 0777, true); // El tercer parámetro `true` crea directorios recursivamente,se crearán directorios padres si no existen
                            }
                            $pathToSave = PFP_PETS . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "PetImages/PFPets/" . $hashedNameFile . '.' . $extension[1];
                            //move_uploaded_file($temp, $pathToSave);

                            echo "PATH SAVE PFPETS";
                            echo "$pathToSave";
                        } else if ($typeFile == "vaccPlan") {
                            if(!file_exists(VACCS_PLAN))
                            {
                                mkdir(VACCS_PLAN, 0777, true);
                            }
                            $pathToSave = VACCS_PLAN . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "PetImages/Vaccplan/" . $hashedNameFile . '.' . $extension[1];
                            //move_uploaded_file($temp, $pathToSave);
                            echo "PATH SAVE VACCPETS";
                            echo "$pathToSave";
                            
                        }
                        //Si no es una imagen,verificamos si es un video y posteriormente su MIME
                    } else if ($typeFile == "video") {
                        echo "HOLA ENTRA ACA?";
                        var_dump($mime);
                        if ($mime == "video/mp4" || $mime = "application/octet-stream") {
                            
                            //Dir almacen temp
                            $temp = $fileInfo["tmp_name"];
                            $hashedNameFile = hash_file('sha1', $temp);

                            echo "SOY TEMP ";
                            var_dump($temp);
                            echo "SOYHASH";
                            var_dump($hashedNameFile);

                            //Estuve literalmente 1.30 hs tratando de ver si con las rutas relativas funcionaban y nada
                            //Quedara meter una constante al directorio y/o probar con la creacion de la carpeta si ve que el directorio especificado no existe
                            //Con lo ultimo mencionado deberia arreglarse el problema de las r.relativas
                            if(!file_exists(VIDEO_PATH))
                            {
                                mkdir(VIDEO_PATH,0777,true);
                            }
                            $pathToSave = VIDEO_PATH . $hashedNameFile . '.' . $extension[1];
                            $pathToBD = "Videos/" . $hashedNameFile . '.' . $extension[1];


                            
                            echo "<br>pathToBD = " . $pathToBD;
                            echo "<br>pathToSave = " . $pathToSave;
                            //move_uploaded_file($temp, $pathToSave);
                        } else {
                            $error = "Not supported type.Check it";
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
        echo "SOY PATHS :";
        var_dump($paths);
        return $paths;
    }


    public function validatePet($name, $typePet, $ownerCode, $size, $breed, $vaccPlan, $video, $pfp, $age)
    {

        try {
            $pet = new Pet();
            $error = null;
            //Validate pet's name
            if (strlen($name) > 1 && strlen($name) < 20) {
                $pet->setName($name);
            } else {
                $error .= " Error at length's name ";
            }

            //Validate type
            if ($typePet !== "dog" && $typePet !== "cat") 
            {
                $error .= " Error at typePet ";
            } else {
                $pet->setTypePet($typePet);
            }

            //If the user is active
            if ($this->ownerService->getByCode($ownerCode) != null) {
                trim($ownerCode);
                $pet->setOwnerCode($ownerCode);
            } else {
                $error .= " Error at ownerCode " ;
            }


            //Checking sizes
            if (
                $size != "big" && $size != "medium" && $size != "small"
            ) {
                $error .= " Not size allowed ";
            } else {
                $pet->setSize($size);
            }

            //Revalidate breed's name (depends typePet)
            if ($typePet == "cat") {
                $content = file_get_contents("DAOJson\catBreeds.json");
                $decodedContent = json_decode($content, true);
                //var_dump($decodedContent);
                if (in_array($breed, $decodedContent)) {
                    $pet->setBreed($breed);
                } else {
                    $error .= " Not valid breed ";
                }
            } else if($typePet == "dog") {
                $content = file_get_contents("DAOJson\dogBreeds.json");
                $decodedContent = json_decode($content, true);

                foreach ($decodedContent as $breedArray) {

                    if (
                        $breedArray["name"] === $breed
                    ) {
                        $pet->setBreed($breed);
                    }
                }
                if ($pet->getBreed() == null) {
                    $error = " Error in breed ";
                }
            }else
            {
                $error .=  " Error in breed ";
            }

            //Setting age -validate if it's a number
            $pet->setAge($age);

            if($error == null)
            {
                $pet->setPetCode($this->generateCode());
                $resultInsert = $this->petDAO->Add($pet);
            }else{
                $error .= " <br> We cant't add your pet.Check again!";
            }
            

            if($resultInsert != null && $resultInsert != " ")
            {
                if (isset($pfp)) {

                    $arrayPaths = $this->getImgsPetRegister("pfp", $pfp, $typePet);
                    move_uploaded_file($arrayPaths["file"],$arrayPaths["pathToSave"]);
                    $this->petDAO->updatePfp($resultInsert,$arrayPaths["pathToDB"]);
                    $pet->setPfp($arrayPaths["pathToDB"]);
                }
    
                if (isset($video)) {

                    $arrayPaths = $this->getImgsPetRegister("video", $video, $typePet);
                    if($arrayPaths["file"] == null)
                    {
                        $error =  "Not video uploaded.Update later!";
                    }else{
                        $this->petDAO->updateVideo($resultInsert,$arrayPaths["pathToDB"]);
                        move_uploaded_file($arrayPaths["file"],$arrayPaths["pathToSave"]);
                        $pet->setVideo($arrayPaths["pathToDB"]);
                    }
                }
                if (isset($vaccPlan)) {
                    
                    $arrayPaths = $this->getImgsPetRegister("vaccPlan", $vaccPlan, $typePet);
                    move_uploaded_file($arrayPaths["file"],$arrayPaths["pathToSave"]);
                    $this->petDAO->updatePfp($resultInsert,$arrayPaths["pathToDB"]);
                    $pet->setVaccPlan($arrayPaths["pathToDB"]);
                }
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
            $petAdded = $this->petDAO->getPet($resultInsert);
            if($petAdded->getPfp() == null || $petAdded->getVaccPlan() == null){
                $error = "Your pet was added.But upload as soon as you can the VaccPlan/Profile picture";
            }
        }

        return $error;
    }

    public function getAllByOwner($ownerCode)
    {
        return $this->petDAO->getAllByOwner($ownerCode);
    }

    public function petsByOwnAndType($ownerCode,$typePet)
    {
        $array = $this->petDAO->getAllByTypeAndOwner($ownerCode,$typePet);


        $arrayToEncode = array();
        foreach($array as $pet)
        {
            $valsToEncode["petCode"] = $pet->getPetCode();
            $valsToEncode["name"] = $pet->getName();
            //$encodedObj = json_encode($valsToEncode);
            array_push($arrayToEncode,$valsToEncode);
            
        }
        //var_dump($arrayToEncode);
        $encodedArray = json_encode($arrayToEncode);
        //Supuestamente si no existe el archivo lo crea,pero nop,lo tuve que hacer manual
        file_put_contents("DAOJson\petsByOwnAndType.json",$encodedArray);
        
        
        return $encodedArray;
    }

    public function srv_getPetsByOwnFilters($ownerCode,$typePet,$typeSize)
    {
        try{
            $array = $this->petDAO->getPetsFilteredOwner($ownerCode,$typePet,$typeSize);


        $arrayToEncode = array();
        foreach($array as $pet)
        {
            $valsToEncode["petCode"] = $pet->getPetCode();
            $valsToEncode["name"] = $pet->getName();
            //$encodedObj = json_encode($valsToEncode);
            array_push($arrayToEncode,$valsToEncode);
            
        }
        //var_dump($arrayToEncode);
        $encodedArray = json_encode($arrayToEncode);
        //Supuestamente si no existe el archivo lo crea,pero nop,lo tuve que hacer manual
        file_put_contents("DAOJson\petsByOwnAndType.json",$encodedArray);
        
        
        return $encodedArray;
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }

    //Valida que el codigo de pet este en relacion con el ownerCode en la BD que quiera modificar algo de sus mascotas
    public function srv_validatePetOwner($petCode, $ownerCode)
    {
        try {
            $result = $this->petDAO->checkOwnerByPet($petCode, $ownerCode);
        } catch (Exception $ex) {
            $ex->getMessage();
        }
        return $result;
    }

    public function srv_updateVacc($petCode, $ownerCode, $vaccPlan)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("This pet doesn't belong to you.Contact support for more information.");
            } else {
                $this->petDAO->updateVacc($petCode, $vaccPlan);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function srv_updatePfp($petCode, $ownerCode, $pfp)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("This pet doesn't belong to you.Contact support for more information.");
            } else {
                $this->petDAO->updatePfp($petCode, $pfp);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function srv_updateAge($petCode, $ownerCode, $age)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("This pet doesn't belong to you.Contact support for more information.");
            } else {
                $this->petDAO->updateAge($petCode, $age);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function srv_updateVideo($petCode, $ownerCode, $video)
    {
        try {
            if ($this->srv_validatePetOwner($petCode, $ownerCode) == null) {
                throw new Exception("This pet doesn't belong to you.Contact support for more information.");
            } else {
                $this->petDAO->updateVideo($petCode, $video);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function srv_checkOwnerPet($petCode,$ownerCode)
    {
        var_dump($ownerCode);
        try{
            $result = null;

                if(strpos($ownerCode,"OWN") !== false)
                {
                    echo "HOLA?";
                    $result = $this->petDAO->checkOwnerByPet($petCode,$ownerCode);
                }

            return $result;
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }



    public function srv_updatePetInfo($petCode, $ownerCode, $size, $vaccPlan, $video, $pfp, $age)
    {
        try {

            $petSearched = $this->petDAO->getPet($petCode);
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
                            $error = "Pfp deleted";
                        }
                    }else{
                        $error .= "Error at updating PFP";
                    }

                }


                if (isset($video["tmp_name"]) && $video["size"] > 0 && !empty($video["tmp_name"])) {

                    $arrayPathsVideo = $this->getImgsPetRegister("video", $video, $typePet);
                    if ($arrayPathsVideo["file"] == null) {
                        $error =  "Not video uploaded.Update later!";
                    } else {
                        $resultVideo = $this->petDAO->updateVideo($petCode, $arrayPathsVideo["pathToDB"]);
                        if ($resultVideo == 1) {
                            move_uploaded_file($arrayPathsVideo["file"], $arrayPathsVideo["pathToSave"]);

                            if (unlink(ROOT. $videoToDelete)) {
                                $error = "Video deleted";
                            } 
                        }else{
                            $error .= "Error at updating Video";
                        }                       
                    }
                }
                if (isset($vaccPlan["tmp_name"]) && $vaccPlan["size"] > 0 && !empty($vaccPlan["tmp_name"])) {

                    $arrayPaths = $this->getImgsPetRegister("vaccPlan", $vaccPlan, $typePet);
                    $result = $this->petDAO->updateVacc($petCode, $arrayPaths["pathToDB"]);
                    if ($result == 1) {
                        move_uploaded_file($arrayPaths["file"], $arrayPaths["pathToSave"]);
                        if (unlink(IMG_PATH . $vaccPlanToDelete)) {
                            $error = "Vaccplan deleted";
                        }
                    }else{
                        $error .= "Error at updating Vaccplan";
                    }
                }
            }

            //If everything OK ,error takes value = 1
            if($result == 1)
            {
                $error = $result;
            }

            if(isset($size) && !empty($size))
            {
                $this->petDAO->updateSize($petCode,$size);
            }

            if(isset($age) && !empty($age))
            {
                $this->petDAO->updateAge($petCode,$age);
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
        }
        return $error;
    }


    public function srv_getPet($petCode)
    {
        try{
                //Usar regex o modificar logica strpos
                return $this->petDAO->getPet($petCode);

            
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }
}

?>