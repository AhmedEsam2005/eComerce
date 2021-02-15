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
        echo "Add Member <a href = '?do=Add'>Add</a>";
    }elseif ($do == 'Edit') {
        $img =  getInfo('users', 'image', 'UserID',$_SESSION['UserID'] ,$con);
        // If Isset Request Files
        if (isset($_FILES['img'])) {
            // Start Move File In Folder Uploads
            $filename = $_FILES['img']['name'];
            $filetemp = $_FILES['img']['tmp_name'];
            $folder   = "layout/upload/" . $filename;
            move_uploaded_file($filetemp , $folder);
            if ($_FILES['img']['error'] == 0) {
                $stmt = $con->prepare("UPDATE  users SET image = ?");
                $stmt->execute(array($filename));
                $count = $stmt->rowCount();
                $img =  getInfo('users', 'image', 'UserID',$_SESSION['UserID'] ,$con);
                $stmt2 = $con->prepare("INSERT INTO gallery (ImageName)VALUES(:zname)");
                $stmt2->execute(array(
                    'zname' => $filename
                ));
            }else {
                echo "Failed";
            }
        }
        if (isset($_POST['name'])) {
            $username = $_POST['name'];
            $Fname    = $_POST['fname'];
            $Email    = $_POST['email'];
            if ($_POST['new-password'] == "") {
                $Password = $_POST['old-password'];
            }else {
                $Password = sha1($_POST['new-password']);
            }
            $member = new User();
            $infoCount = $member->editMember($_GET['id'],$con,$username, $Fname, $Email, $Password);
            if ($infoCount > 0) {
                $success = '<div class = "alert alert-success text-center hidden-after-5"style = "margin: 20px auto;">All Data Is Updated</div>';
                header("refresh:6");
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
                                <form method = 'GET' enctype="multipart/form-data" name = 'img'>
                                    <input type="file" accept="image/png, image/jpeg" class = 'form-control' name= 'img'>
                                    <input class = 'btn btn-primary btn-lg' type = 'submit' value = 'upload' style = 'margin:20px auto'>
                                </form>
                            </div>
                            <div class="gallery" data-show="gallery">
                                <?php
                                    $stmt3 = $con->prepare("SELECT ImageName FROM gallery");
                                    $stmt3->execute();
                                    $rows = $stmt3->fetchAll();
                                    foreach ($rows as $row) {
                                        ?>
                                        <img src="layout/upload/<?php echo $row['ImageName']?>"  class = 'img-gallery' alt="">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <i class = 'fa fa-remove remove'></i>
                    </div>
                </div>
            </div>
        </div>
        <form method = 'POST' style = 'max-width:800px;margin:auto'>
            <div class="container">
                <?php if (isset($success)) {echo $success;}?>
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
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" require = 'required' name = 'name' value = "<?php echo getInfo('users', 'Username', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">FullName</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" require = 'required' name = 'fname'value = "<?php echo getInfo('users', 'FullName', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" require = 'required' name = 'email' value = "<?php echo getInfo('users', 'Email', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" require = 'required' name = 'new-password' placeholder = 'Your Password Is Hidden'>
                                <input type="hidden" class="form-control" require = 'required' name = 'old-password' value = "<?php echo getInfo('users', 'Password', 'UserID', $_GET['id'], $con)?>">
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
    }elseif ($do  == 'Add') {
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
                                <form method = 'POST' enctype="multipart/form-data" name = 'img'>
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
        <div class="form-add">
            <div class="container">
                <form action="" style = 'max-width:800px;margin:auto
                '>
                    <div class="img text-center">
                        <img src="layout/images/defualtAvatar.jpg"class = 'edit-image' alt="Your Image">
                        <i class = 'fa fa-edit'id = 'btnEditImg'></i>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" require = 'required' name = 'name'placeholder="Enter Username">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">FullName</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" require = 'required' name = 'fname'placeholder="Enter Your FullName">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" require = 'required' name = 'email 'placeholder="Enter Your Email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" require = 'required' name = 'password' placeholder = 'Enter Your Password'>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <input type="submit" class="btn btn-outline-primary btn-edit" value = 'Save Changes'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        echo "Hello In Add Page";
    }
?>
<?php
    include $tpl . "footer.php";
?>