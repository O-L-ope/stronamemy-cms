<?php
require("./../src/config.php");
session_start();

use Steampixel\Route;

// Route::add('/', function() {
//     global $twig;
//     $posts = Post::getPage();
//     $t = array("posts" => $posts);
//     $twig->display("index.html.twig", $t);
//     $postArray = Post::getPage();
//     $twigData = array("postArray" => $postArray,
//     "pageTitle" => "Strona główna",
//     );
// });

Route::add('/', function() {
    global $twig;
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray,
                        "pageTitle" => "Strona główna",
                        );
    if(isset($_SESSION['user']))
        $twigData['user'] = $_SESSION['user'];
    $twig->display("index.html.twig", $twigData);
});

// route::add('/upload', function(){
//     global $twig;
//     $twig->display("upload.html.twig");
// });

Route::add('/upload', function() {
    global $twig;
    $twigData = array("pageTitle" => "Wgraj mema");
    if(isset($_SESSION['user']))
        $twigData['user'] = $_SESSION['user'];
    $twig->display("upload.html.twig", $twigData);
});

Route::add('/upload', function(){
    global $twig;

    $tempFileName = $_FILES['uploadedFile']['tmp_name'];
    $title = $_POST['title'];
    Post::upload($tempFileName, $title);
    
    $twig->display("index.html.twig");
}, 'post');

Route::add('/register', function(){
    global $twig;
    $twigData = array("pageTitle" => "Zarejestruj użytkownika");
    $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function() {
    global $twig;
    $twigData = array("pageTitle" => "Zarejestruj użytkownika");
    $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function(){
    global $twig;
    if(isset($_POST['submit'])) {
        User::register($_POST['email'], $_POST['password']);
        header("Location: http://localhost/stronamemy-cms/pub");
    }
}, 'post');

Route::add('/login', function(){
    global $twig;
    $twigData = array("pageTitle" => "Zaloguj użytkownika");
    $twig->display("login.html.twig", $twigData);
});

Route::add('/login', function() {
    global $twig;
    if(isset($_POST['submit'])) User::login($_POST['email'], $_POST['password']);
    {
        header("Location: http://localhost/stronamemy-cms/pub");
    }
    

}, 'post');


Route::run('/stronamemy-cms/pub');


?>

