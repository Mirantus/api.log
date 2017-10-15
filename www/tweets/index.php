<?
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    ini_set('magic_quotes_gpc', 0);

    header("Access-Control-Allow-Origin: *");

    require '../../vendor/autoload.php';
    require '../../app/Tweets.php';

    Tweets::connect();
    $result = [];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $tweets = Tweets::sql('SELECT * FROM :table ORDER BY id DESC');

        foreach ($tweets as $tweet) {
            $result[] = $tweet->data;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'));

        $tweet = new Tweets();
        $tweet->text = $data->text;
        $tweet->date = date('Y-m-d');
        $tweet->save();
        $result[] = $tweet->data;
    }

    echo json_encode($result);
