<?php
require("./../vendor/autoload.php");

$db = new mysqli('localhost', 'root', '', 'bazacms');

require('./../src/post.class.php');

$loader = new Twig\Loader\FilesystemLoader('./../src/templates');
$twig = new Twig\Environment($loader);
?>