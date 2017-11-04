<?
    /** @var array $result */
    require '../../app/init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $result = Tweets::retrieveByPK($_GET['id'])->data;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'));
        $tweet = Tweets::retrieveByPK($_GET['id']);
        $tweet->text = $data->text;
        $tweet->save();
        $result = $tweet->data;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $tweet = Tweets::retrieveByPK($_GET['id']);
        $tweet->delete();
        $result = $tweet->data;
    }

    echo json_encode($result);
