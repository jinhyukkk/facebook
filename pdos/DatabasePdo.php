<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "server-c-rds.cydgqzlktblk.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "Facebook_DB";
        $DB_USER = "jhjh";
        $DB_PW = "Qkrwlsgur1!";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}