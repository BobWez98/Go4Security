<!DOCTYPE html>
<html lang="en">
<?php
include "login.php";
include "includes/head.php";
?>
<body>
    <div id="navb">
    <?php
include "includes/topbar.php";
include "includes/navbar.php";

include "includes/scripts.php";

?>
    </div>
    <div id="all">
        <div id="content">
            <div id="stockchecker">
                <div class="container" style="height:48px">
                  <div>
                    <?php
// TODO: items below should be tidied!

if (isset($_GET['id']) && isset($_GET['number'])) {
    $idProduct      = $_GET['id'];
    $itemsRequested = $_GET['number'];

    $conn = setupDB($dbhost, $dbSelectUsername, $dbSelectPassword);
    $stmt = $conn->prepare("SELECT product_id as id, name as n
                                FROM product
                               WHERE nrInStock >= :items
                                 AND product_id =:product
                              order by name;");
    $stmt2 = $conn->prepare("SELECT product_id as id, name as n
                                FROM product
                               WHERE product_id = :product;");
    try {
        $stmt->bindParam(':product', $idProduct);
        $stmt->bindParam(':items', $itemsRequested);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row !== false) {
            echo "<div>Van het product " . $row['n'] . " is nog voldoende voorraad ($itemsRequested gevraagd) </div>";
        } else {
            $stmt2->bindParam(':product', $idProduct);
            $stmt2->execute();
            $row = $stmt2->fetch();
            echo "<div>Van het product " . $row['n'] . " is niet voldoende voorraad ($itemsRequested gevraagd) </div>";
        }

    } catch (Exception $e) {
        echo "<div>SQL Foutmelding: " . $e->getMessage();
    }
} //if $_POST parameters present
else {
    echo "<div>U heeft geen product en aantal opgegeven.</div>";
}

?>
                  </div>
                </div>
            </div>
            <?php
include "includes/index/advantages.php";
include "includes/index/hotproducts.php";
?>
        </div>
            <?php
include "includes/footer.php";
include "includes/copyright.php";
?>
    </div>
    <?php include "includes/scripts.php";?>
</body>
</html>
