<?
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    ini_set('magic_quotes_gpc', 0);

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

    require '../../vendor/autoload.php';
    require '../../app/Tweets.php';

    Tweets::connect();
    $result = [];