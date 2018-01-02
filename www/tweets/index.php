<?
    require '../../app/init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $tweets = Tweets::sql('SELECT * FROM :table ORDER BY id DESC');

        foreach ($tweets as $tweet) {
            $result[] = $tweet->data;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        check_auth();
        $data = json_decode(file_get_contents('php://input'));

        $tweet = new Tweets();
        $tweet->text = $data->text;
        $tweet->date = date('Y-m-d');
        $tweet->save();
        $result = $tweet->data;
    }

    echo json_encode($result);
