<?php
require('./../src/config.php');
?>

<form action="" method="post" enctype="multipart/form-data">
        <label for="titleInput">Tytuł: </label>
        <input type="text" id="titleInput" name="title"><br>
        <label for="uploadedFileInput">
            Wybierz plik: 
        </label>
        <input type="file" name="uploadedFile" id="uploadedFileInput">
        <input type="submit" value="Wyślij plik" name="submit">
    </form>

<?php 
require('./../src/config.php');
if(isset($_POST['submit']))
    post::upload($_FILES['uploadedFile']['name']['tmp_name'], $_POST['title']);

?>

<?php
var_dump(Post::getPage());

?>