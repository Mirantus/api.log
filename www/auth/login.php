<?
    require '../../app/init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'));

        $login = $data->login;
        $password = $data->password;

        if ($login != USER_LOGIN || $password != USER_PASSWORD) {
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }

        $token = [
            'login' => $login,
            'date' => time(),
        ];

        echo json_encode(encode(json_encode($token), SECRET_KEY));
    }
