<?php
    include "init.php";
    include $data . "user_opt.php";
    $url = $_SERVER['REQUEST_URI'];
    if (!isset($_SESSION['Username'])) {
        header("Location: login.php?redirect=$url");
    }
    include $tpl . "header.php";
    include $tpl . "nav.php";
?>
<?php
    echo "Hello In Member Page";
?>
<?php
    include $tpl . "footer.php";
?>