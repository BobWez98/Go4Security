<html>
    <?php
include "dbConfig.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn == null) {
        $conn = setupDB($dbhost, $dbInsertUsername, $dbInsertPassword);
    }
    $newAccount = newAccount($conn, $_POST["user"], $_POST["pass"]);
    if ($newAccount == 1) {
        echo "Succesfull account creation";
    } elseif ($newAccount == 0) {
        echo "failed to create account";
    } elseif ($newAccount == 2) {
        echo "Username already exists.";
    }
}

function newAccount($conn, $u, $p)
{
    try {
        $smtp = $conn->prepare("SELECT username FROM user WHERE username=:username");
        $smtp->bindParam(":username", $u);
        if ($smtp->execute()) {
            if (!$smtp->fetch()) {
                $prep = $conn->prepare("INSERT INTO user (username,pass) VALUES(:user,:pass)");
                $prep->bindParam(':user', $u);
                $prep->bindParam(':pass', hash("sha256", $p)); //simpele hash zonder salt
                $prep->execute();
                $prep = null;
                $conn = null;
                return 1;
            } else {
                return 2;
            }
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
        return 0;
    }
}
?>
    <body>
        <h1>Sign Up</h1>
        <h4> Create an account</h4>
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                User: <input type="text" name="user"><br>
                Password: <input type="password" name="pass"><br>
                <input type="submit">
            </form>
    </body>
</html>
