<?
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    ini_set('magic_quotes_gpc', 0);

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Accept, Content-Type, Cache, Authorization');

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit();
    }

    require __DIR__ . '/config.php';

    require '../../vendor/autoload.php';
    require '../../app/Tweets.php';

    Tweets::connect();
    $result = [];

    function encode($text, $key) {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($text, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    function decode($ciphertext, $key) {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
        return false;
    }

    function check_auth() {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        }

        error_reporting(E_ERROR);
        $success = false;

        try {
            if (isset($token)) {
                $token = decode($token, SECRET_KEY);
                if ($token) {
                    $token = json_decode($token, true);
                    $now = time();

                    if ($token && ($token['date'] + TOKEN_LIFETIME > $now)) {
                        $success = true;
                    }
                }
            }
        } catch (Exception $e) {}

        if (!$success) {
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
    }
