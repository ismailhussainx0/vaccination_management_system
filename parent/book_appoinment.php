<?php
include "parent_auth.php";
include "../config/db_connection.php";


$parentId = $_SESSION["parent_id"];

$childSql = "SELECT child_id, child_name FROM child WHERE parent_id = ?";
$childStmt = mysqli_prepare($connect, $childSql);
$childStmt->bind_param("i", $parentId);
$childStmt->execute();

$childResult = $childStmt->get_result();


?>



<select name="child_id" required>
    
    <?php
    while($row = mysqli_fetch_assoc($childResult)){
    ?>

    <option value="<?php echo $row["child_id"]?>">
        <?php echo $row["child_name"]?> 
    </option>

    <?php
    }
    ?>

</select>