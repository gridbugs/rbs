<?
include_once('settings.php');

function db_connect_pdo() {
    try {
        $link = new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=utf8", DB_HOSTNAME, DB_DATABASE),
            DB_USERNAME,
            DB_PASSWORD);
        return $link;
    } catch (PDOException $e) {
        die('Could not connect to the database.');
    }
}

function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
