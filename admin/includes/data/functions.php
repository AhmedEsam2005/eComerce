<?php
/**
 * Start Redirect Function In php
 * Name Of Function redirect v 1.0
 * @param $location Is A location Want To Go 
 */
function redirect( $location) {
    header("Loction : $location");
}
/**
 * Start Function GetInfo
 * getInfo v1.0
 * @Added By @Ahmed
 */
function getInfo($table, $needle, $condition,$answer, $database, $option = "one") {
    $stmt2 = $database->prepare("SELECT $needle FROM $table WHERE $condition = ?");
    $stmt2->execute(array($answer));
    if ($option == 'all') {
        $rows = $stmt2->fetchAll();
        return $rows;
    }else{
        $row = $stmt2->fetch();
        return $row[$needle];
    }

}
/**
 * Start Function Get Count
 * getCount v1.0
 * @Added By @Ahmed
 */
function getCount($table, $needle, $condition,$database) {
    $stmt2 = $database->prepare("SELECT $needle FROM $table WHERE $needle = ?");
    $stmt2->execute(array($condition));
    return $stmt2->rowCount();
}
?>