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
        $img =  getInfo('users', 'image', 'UserID',$_SESSION['UserID'] ,$con);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Start Move File In Folder Uploads
            $filename = $_FILES['img']['name'];
            $filetemp = $_FILES['img']['tmp_name'];
            $folder   = "layout/upload/" . $filename;
            move_uploaded_file($filetemp , $folder);
            if ($_FILES['img']['error'] == 0) {
                echo "Success";
                $stmt = $con->prepare("UPDATE  users SET image = ?");
                $stmt->execute(array($filename));
                $count = $stmt->rowCount();
                echo $count;
                $img =  getInfo('users', 'image', 'UserID',$_SESSION['UserID'] ,$con);
                header("refresh:1");
            }else {
                echo "Failed";
            }
        }
        ?>
        <div class="conatiner">
            <div class="pop-box-edit-img text-center">
                <div class="row">
                    <div class="col-md-2">
                        <div class="options">
                            <p class = 'lead ' data-vue = 'gallery'>Gallery</p>
                            <p class = 'lead'data-vue = 'upload'>Upload</p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="vues">
                            <div class ='form' data-show = 'upload'>
                                <form method = 'POST' enctype="multipart/form-data">
                                    <input type="file" accept="image/png, image/jpeg" class = 'form-control' name= 'img'>
                                    <input class = 'btn btn-primary btn-lg' type = 'submit' value = 'upload' style = 'margin:20px auto'>
                                </form>
                            </div>
                            <div class="gallery" data-show="gallery">This Is Gallery</div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <i class = 'fa fa-remove remove'></i>
                    </div>
                </div>
            </div>
        </div>
        <form>
            <div class="container">
                <div class="img text-center">
                    <?php
                        if ($img !== "") {
                            
                            ?>
                            <img src="layout/upload/<?php echo $img ?>"class = 'edit-image' alt="Your Image">
                            <i class = 'fa fa-edit'id = 'btnEditImg'></i>
                            <?php
                        }else {
                            ?>
                            <img src="layout/images/defualtAvatar.jpg"class = 'edit-image' alt="Your Image">
                            <i class = 'fa fa-edit'id = 'btnEditImg'></i>
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