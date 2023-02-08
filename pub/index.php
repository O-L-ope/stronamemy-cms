<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFileInput">
            Wybierz plik: 
        </label>
        <input type="file" name="uploadedFile" id="uploadedFileInput">
        <input type="submit" value="Wyślij plik" name="submit">
    </form>

    <?php
    if(isset($_POST['submit']))
    {
        $targetDir = "img/";
        $fileName = $_FILES['uploadedFile']['name'];
        $tempFileUrl = $_FILES['uploadedFile']['tmp_name'];
        $imageInfo = @getimagesize($tempFileUrl);
    //var_dump($imageInfo);
        
    
        if(!is_array($imageInfo)){
            die("Nieprawidłowy format obrazu");
    }

        $imgString = file_get_contents($tempFileUrl);
        $gdImage = imagecreatefromstring($imgString);
     
        $targetExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $targetExtension = strtolower($targetExtension);
        $targetFileName = $fileName . hrtime(true);
        $targetFileName = hash("sha256", $targetFileName);


        $targetUrl = $targetDir . $fileName . "." . $targetExtension;
        if(file_exists($targetUrl))
            die("Plik o tej samej nazwie już istnieje!");
    //move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetUrl);
    //var_dump($_FILES);
        $targetUrl = $targetDir . $targetFileName . ".webp";
        imagewebp($gdImage, $targetUrl);
    }

    
    ?>
</body>
</html>