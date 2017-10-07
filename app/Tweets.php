<?php
    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/config.php';

    use ItvisionSy\SimpleORM\DataModel;

    class Tweets extends ItvisionSy\SimpleORM\DataModel {

        protected static $createdAtColumn = 'created_at';
        protected static $tableName = 'tweets';

        public static function connect() {
            Tweets::createConnection(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        }
    }
