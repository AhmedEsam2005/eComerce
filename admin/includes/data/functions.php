<?php
/**
 * Start Redirect Function In php
 * Name Of Function redirect v 1.0
 * @param $location Is A location Want To Go 
 */
/**
 * Start Function GetInfo
 * getInfo v1.0
 * @Added By @Ahmed
 */
function getInfo($table, $needle, $condition = '',$answer = '', $database) {
    $stmt2 = $database->prepare("SELECT $needle FROM $table WHERE $condition = ?");
    $stmt2->execute(array($answer));
    $row = $stmt2->fetch();
    return $row[$needle];
}
/**
 * Start Function Get Count
 * getCount v1.0
 * @Added By @Ahmed
 * @param table Is A Table You Want Cheked
 * @param needle Is A Needle In The Table
 * @param condition Is Acondition Must Be True To Get Data
 * @param database Is A Virable Connected Database
 */
function getCount($table, $needle, $condition,$database) {
    $stmt2 = $database->prepare("SELECT $needle FROM $table WHERE $needle = ?");
    $stmt2->execute(array($condition));
    return $stmt2->rowCount();
}
/**
 * Function redirect
 * @param second A Number Of The Second Wait Before Redirect
 * @param location  A Location Want to Go
 * @param msg  The Msg Preview Before Redirect
 * Added By @Ahmed
*/
function redirect($second = 3, $location = null,$msg) {
    if ($location == null) {
        $location = 'index.php';
    }else {

        $url = $_SERVER['HTTP_REFERER'];

    }
    echo "<div class = 'alert alert-info text-center'>$msg You Will Redirected After <span class = 'incress' style = 'font-weight:bold'>$second</span></div>";
    header("refresh:$second;$location");
    exit();
}
?>