<?
    require '../../app/init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        error_reporting(E_ERROR);
        $success = false;

        try {
            $token = explode('=', $_SERVER['QUERY_STRING'])[1];
            $token = decode($token, SECRET_KEY);
            if ($token) {
                $token = json_decode($token, true);
                $now = time();
                if ($token && ($token['date'] + TOKEN_LIFETIME > $now)) {
                    $token['date'] = $now;
                    echo json_encode(encode(json_encode($token), SECRET_KEY));
                    $success = true;
                }
            }
        } catch (Exception $e) {}

        if (!$success) {
            header('HTTP/1.0 401 Unauthorized');
        }
    }
