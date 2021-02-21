<?php
    include "init.php";
    include $data . "user_opt.php";
    $url = $_SERVER['REQUEST_URI'];
    if (!isset($_SESSION['Username'])) {
        header("Location: login.php?url=$url");
        exit();
    }
    include $tpl . "header.php";
    include $tpl . "nav.php";
?>
<?php
    $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';
    // Start User Class
    $member = new User();
    if ($do == 'Mange') { // Start Mange Page
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class = 'text-center'>Mange Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="table main-table text-center " style = 'border:1px solid #CCC'>
                    <tr>
                        <td>#ID</td>
                        <td>Image</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>FullName</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>
                    <tr>
                        <?php

                            foreach ($rows as $row) {
                                if ($row['image'] == 'defualtAvatar.jpg') {
                                    $imageTable = 'layout/images/' . $row['image'];
                                }else {
                                    $imageTable = 'layout/upload/' . $row['image'];
                                }
                                echo    "<tr style = 'border:1px solid #CCC'>
                                            <td>" . $row['UserID'] . "</td>
                                            <td><img src = '" . $imageTable . "' class = 'avatar'></td>
                                            <td>" . $row['Username'] ."</td>
                                            <td>" . $row['Email'] ."</td>
                                            <td>" . $row['FullName'] ."</td>
                                            <td>" . $row['registerDate'] . "</td>
                                            <td>
                                                <a href = 'member.php?do=Edit&id=" . $row['UserID'] ."' type = 'button' class = 'btn btn-success'> <i class = 'fa fa-edit'></i>Edit</a>
                                                <a href = 'member.php?do=Delete&id=" . $row['UserID'] ."' type = 'button' class = 'btn btn-danger confirm'> <i class = 'fa fa-remove'></i>Delete</a>
                                            </td>
                                        </tr>";
                            }

                        ?>
                    </tr>
                </table>
            </div>
            <a href="member.php?do=Add" type = 'button' class = 'btn btn-primary'><i class = 'fa fa-plus'></i>Add New Member</a>        
        </div>
        <?php
    }elseif ($do == 'Edit') { //Edit Member Page
        // Userid Want Edited
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

        // Image preview
        $img =  getInfo('users', 'image', 'UserID',$id ,$con) == 'defualtAvatar.jpg' ? 
        'layout/images/defualtAvatar.jpg' : 'layout/upload/' . getInfo('users', 'image', 'UserID',$id ,$con);

        // If Isset Request Files
        if (isset($_FILES['img'])) {

            // Start Move File In Folder Uploads
            $filename = $_FILES['img']['name'];

            // File Temp
            $filetemp = $_FILES['img']['tmp_name'];

            // File Folder Uploded In
            $folder   = "layout/upload/" . $filename;

            // Move File
            move_uploaded_file($filetemp , $folder);

            // Check If Erors
            if ($_FILES['img']['error'] == 0) {
                $stmt = $con->prepare("UPDATE  users SET image = ? WHERE UserID = ?");
                $stmt->execute(array($filename, $id));
                $count = $stmt->rowCount();
                $img =  'layout/upload/' . getInfo('users', 'image', 'UserID',$id ,$con);
                $stmt2 = $con->prepare("INSERT INTO gallery (ImageName)VALUES(:zname)");
                $stmt2->execute(array(
                    'zname' => $filename
                ));
            }else {
                echo "Failed Upload Image";
            }
        }
        // Start Statement For User Info 

        if (isset($_POST['name'])) {
            $username = $_POST['name'];
            $Fname    = $_POST['fname'];
            $Email    = $_POST['email'];
            if ($_POST['new-password'] == getInfo('users', 'Password', 'UserID', $_GET['id'], $con)) {
                $Password = $_POST['new-password'];
            }else {
                $Password = sha1($_POST['new-password']);
            }
            // Count Recoerd Updated
            $infoCount = $member->editMember($id,$con,$username, $Fname, $Email, $Password);
            // Massge Success
            $success = '<div class = "alert alert-success text-center hidden-after-5"style = "margin: 20px auto;">' . $infoCount .' Record Updated</div>';
            header("refresh:1;member.php");
            exit();
        }
        if (isset($_GET['id'])) {

        if (getInfo('users', 'Username', 'UserID', $_GET['id'], $con)) {

        
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
                            <img src="<?php echo $img ?>"class = 'edit-image' alt="Your Image">
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
                                <input type="text" class="form-control" required = 'required' name = 'name' value = "<?php echo getInfo('users', 'Username', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">FullName</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" required = 'required' name = 'fname'value = "<?php echo getInfo('users', 'FullName', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" required = 'required' name = 'email' value = "<?php echo getInfo('users', 'Email', 'UserID',$_GET['id'] ,$con)?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" required = 'required' name = 'new-password' placeholder = 'Your Password Is Hidden' value = <?php echo getInfo('users', 'Password', 'UserID', $_GET['id'], $con)?>>
                                <input type="hidden" class="form-control" required = 'required' name = 'old-password' value = "<?php echo getInfo('users', 'Password', 'UserID', $_GET['id'], $con)?>">
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
            }else {
                redirect(5, "member.php", "Not Such ID For User");
            }
        }else {
            echo "<div class = 'alert alert-danger'>Sorry You Can not Open This Is Page Direct</div>";
            //header("location:member.php");
            redirect(5, "member.php","You Can Not Open This Page Direct");
        }
        ?>
        <?php
    }elseif ($do  == 'Add') { // Add Member
        $img = 'layout/images/defualtAvatar.jpg';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name       = $_POST['name'];
                $fname      = $_POST['fname'];
                $email      = $_POST['email'];
                $password   = sha1($_POST['password']);
                $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FUllName, ReqStutus)
                                                        VAlUES(:zuser, :zpass, :zemail, :zfname, :zreq)");
                $stmt->execute(array(
                    "zuser"   => $name,
                    "zpass"   => $password,
                    "zemail"  => $email,
                    "zfname"  => $fname,
                    "zreq"    => 1
                ));
                $count = $stmt->rowCount();
                if ($count > 0) {
                    $msg = '<div class = "alert alert-success text-center" style = "margin:20px auto">Member Added Successfully</div>';
                    header("refresh:1; member.php");
                    exit();
                }
            }
        ?>
        <div class="form-add">
            <div class="container">
                    <form action="" style = 'max-width:800px;margin:auto' method = 'POST' name = 'add-info'>
                    <?php if(isset($msg)) {echo $msg;}?>
                    <h1 class = 'text-center '>Add Member</h1>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" required = 'required' name = 'name'placeholder="Enter Username">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">FullName</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" required = 'required' name = 'fname'placeholder="Enter Your FullName">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" required = 'required' name = 'email' placeholder="Enter Your Email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" required = 'required' name = 'password' placeholder = 'Enter Your Password'>
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
    }elseif($do == 'Delete'){
        if (!isset($_GET['id'])) {
            redirect(5, "member.php", "You Can Not Open This Page Direct");
        }else {
            if (getCount('users', 'UserID', $_GET['id'], $con)) {
                echo "<h1 class = 'text-center'>Delete Member</h1>";
                echo "<div class = 'container'>";
                // Start Delete
                $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $count = $member->deleteMember("UserID", $id, 'users', $con);
                echo '<div class = "alert alert-success text-center">' . $count .' Record Deleted</div>';
                redirect(5, "member.php", "");
                echo "</container>";
            }else {
                redirect(5, "back", "No Such This ID");
            }
        }
    }//End ElseIf
?>
<?php
    include $tpl . "footer.php";
?>