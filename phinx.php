<?php
if (file_exists(__DIR__."/../../autoload.php")) {
    $pathAutoload = __DIR__."/../../autoload.php"; // consumido como lib
} else {
    $pathAutoload = __DIR__."/vendor/autoload.php";
}

include_once $pathAutoload;

if(is_file(__DIR__.'/.env')){
    if (method_exists('\Dotenv\Dotenv', 'create')){
        $instalador = Dotenv\Dotenv::create(__DIR__, '.env');
    } else {
        $instalador = new Dotenv\Dotenv(__DIR__, '.env');
    }
    $instalador->load();
}

return array(
    "paths" => array(
        "migrations" => __DIR__."/db/migrations"
    ),
    "environments" => array(
        "default_migration_table" => "phinxlog",
        "default_database" => "develop",
        "develop" => [
            "host" => getenv('DB_HOST'),
            "port" => getenv('DB_PORT'),
            "name" => getenv('DB_DBNAME'),
            "adapter" => 'mysql',
            "user" => getenv('DB_USERNAME'),
            "pass" => getenv('DB_PASSWORD'),
        ]
    )
);