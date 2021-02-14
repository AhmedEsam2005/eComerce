<?php
    include "init.php";
    include $data . "user_opt.php";
    $url = $_SERVER['REQUEST_URI'];
    if (!isset($_SESSION['Username'])) {
        header("Location: login.php?url=$url");
        
    }
    include $tpl . "header.php";
    include $tpl . "nav.php";
?>
<?php
    $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';
    
    if ($do == 'Mange') {
        echo "Hello In Mange Page";
        echo "Edit Member <a href = '?do=Edit'>Edit</a>";
    }elseif ($do == 'Edit') {
        $img =  getInfo('users', 'image', '', $con);
        ?>
        <form>
            <div class="container">
                <div class="img text-center">
                    <?php
                        if ($img !== "") {
                            
                            ?>
                            <img src="layout/upload/<?php echo $img ?>"class = 'edit-image' alt="Your Image">
                            <i class = 'fa fa-edit'></i>
                            <?php
                        }else {
                            ?>
                            <img src="layout/images/defualtAvatar.jpg"class = 'edit-image' alt="Your Image">
                            <i class = 'fa fa-edit'></i>
                            <?php
                        }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" require = 'required' name = 'name'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">FullName</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" require = 'required' name = 'fname'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" require = 'required' name = 'email'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" require = 'required' name = 'password'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <input type="submit" class="btn btn-outline-primary btn-edit" value = 'Save Changes'>
                    </div>
                </div>
            </div>
        </form>
        <?php
    }elseif ($do  == 'Insert') {
        echo "Hello In Insert Page";
    }
?>
<?php
    include $tpl . "footer.php";
?>