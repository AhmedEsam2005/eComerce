<?php
    session_start();
    class User {
        public $id;
        public $name;
        public $password;
        public $email;
        public $fullName;
        public $regStutus;
        public $trustStutus;
        public function loginUser($username, $hashedPass, $database) {
            if (!isset($_SESSION['Username'])) {
                if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                    $stmt = $database->prepare("SELECT * FROM users WHERE Username = ? AND Password = ?");
                    $stmt->execute(array($username, $hashedPass));
                    $count =  $stmt->rowCount();
                    $row   = $stmt->fetch();
                    if ($count === 1 ) {
                        $_SESSION["Username"] = $username;
                        $_SESSION['UserID'] = $row['UserID'];
                        if (isset($_GET['redirect'])) {
                            $url = $_GET["redirect"];
                            header("Location: $url");
                        }else {
                            header("Location: index.php");
                        }
                    }
                    return $count;
                }
            }else {
                header("Location: index.php");
                return true;
            }
        }
        public function editMember($id) {}
    }

?>