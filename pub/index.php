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
    if(User::isAuth()) {
        $twigData['user'] = $_SESSION['user'];
        $twig->display("upload.html.twig", $twigData);
    }
    else {
        http_response_code(403);
    }    
});

// Route::add('/upload', function(){
//     global $twig;

//     $tempFileName = $_FILES['uploadedFile']['tmp_name'];
//     $title = $_POST['title'];
//     Post::upload($tempFileName, $title);
    
//     $twig->display("index.html.twig");
// }, 'post');

Route::add('/upload', function() {
    global $twig;
    if(isset($_POST['submit']))  {
        Post::upload($_FILES['uploadedFile']['tmp_name'], $_POST['title'], $_POST['userId']);
    }
    header("Location: http://localhost/stronamemy-cms/pub");
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
    if(isset($_POST['submit'])) {
        if(User::login($_POST['email'], $_POST['password'])) {
            //jeśli zalogowano poprawnie to wyświetl główną stronę
            header("Location: http://localhost/stronamemy-cms/pub");
        } else {
            //jeśli nie zalogowano poprawnie wyświetl ponownie stronę logowania z komunikatem
            $twigData = array("pageTitle" => "Zaloguj użytkownika",
                                "message" => "Niepoprawny użytkownik lub hasło");
            $twig->display("login.html.twig", $twigData);
        }
    }
    

}, 'post');

Route::add('/admin', function() {
    global $twig;
    if(User::isAuth()) {
        $postsList = Post::getPage(1, 100);
        $twigData = array("postList" => $postsList);
        $twig->display("admin.html.twig", $twigData);
    } else {
        http_response_code(403);
    }
});

Route::add('/admin/remove/([0-9]*)', function($id) {
    if(User::isAuth()) {
        Post::remove($id);
        header("Location: /stronamemy-cms/pub/admin");
    } else {
        http_response_code(403);
    }
});
Route::add('/like/([0-9]*)', function($post_id) {
    if(!User::isAuth()) {
        http_response_code(403);
    } else {
        $user_id = $_SESSION['user']->getId();
        $like = new Likes($post_id, $user_id, 1);
        header("Location: /stronamemy-cms/pub/");
    }
});
Route::add('/dislike/([0-9]*)', function($post_id) {
    if(!User::isAuth()) {
        http_response_code(403);
    } else {
        $user_id = $_SESSION['user']->getId();
        $like = new Likes($post_id, $user_id, -1);
        header("Location: /stronamemy-cms/pub/");
    }
});
Route::run('/stronamemy-cms/pub');


?>

