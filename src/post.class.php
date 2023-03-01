<?php
class Post {
    static function upload(string $tempFilename){
        $uploadDir = "img/";
        $imgInfo = getimagesize($tempFilename);
        if(!is_array($imgInfo)){
            die("BŁĄD: Przekazany plik nie jest obrazem");
        }
    $randomSeed = rand(10000, 99999) . hrtime(true);
    $hash = hash("sha256", $randomSeed);
    $targetFileName = $hash . ".webp";
    if(file_exists($targetFileName)){
        die("BŁĄD: podany plik już istnieje");
    }
    $imageString = file_get_contents($tempFilename);
    $gdImage = @imagecreatefromstring($imageString);
    imagewebp($gdImage, $targetFileName);
    }
}

?>