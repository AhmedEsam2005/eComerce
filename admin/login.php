<?php 
    include "init.php";
    include $tpl . "header.php";
    include $data . "user_opt.php";
    $member = new User();
    $username = @$_POST['username'];
    $password = @$_POST['password'];
    $hashed   = sha1($password);
    $url = isset($_GET['url']) ? $_GET['url'] : 'index.php';
    $link = $url;
    $member->loginUser($username, $hashed, $con, $link);
?>
<div class = 'login'>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method = 'POST' class = 'form-login'>
        <h2 class = 'text-center'>Login</h2>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class = 'fa fa-user'></i></span>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name = 'username'>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class = 'fa fa-lock'></i></span>
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" name = 'password'>
        </div>
        <div class="d-grid">
            <input type = 'submit' class = 'btn btn-outline-primary' value="Login">
        </div>
        </form>
    </div>
</div>
<?php
    include $tpl . "footer.php";
?>
