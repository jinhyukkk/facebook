<?php

function isValidUser($userEmail, $pwd){
    $pdo = pdoSqlConnect();
    $query = "SELECT userEmail, pwd as hash FROM User WHERE userEmail= ?;";


    $st = $pdo->prepare($query);
    $st->execute([$userEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return password_verify($pwd, $res[0]['hash']);

}
function getUserIdxByEmail($userEmail)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM User WHERE userEmail = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['userIdx'];
}