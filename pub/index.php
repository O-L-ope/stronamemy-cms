<?php
require("./../src/config.php");

use Steampixel\Route;

Route::add('/', function() {
    global $twig;
    $twig->display("index.html.twig");
});
route::add('/upload', function(){
    global $twig;
    $twig->display("upload.html.twig");
});

Route::add('/upload', function(){
    global $twig;

    $tempFileName = $_FILES['uploadedFile']['tmp_name'];
    Post::upload($tempFileName);
    
    
    $twig->display("index.html.twig");
}, 'post');


Route::run('/stronamemy-cms/pub');


?>

