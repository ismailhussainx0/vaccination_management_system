<?php

include "../config/db_connection.php";
include "parent_auth.php";

//USERNAME
$userName = $_SESSION['parent_name'];

// SELECTED DATA
$selectQuery = "SELECT * FROM child WHERE parent_id = ?";
$selectStmt = mysqli_prepare($connect, $selectQuery);
$selectStmt->bind_param("i", $_SESSION["parent_id"]);
$selectStmt->execute();

$result = $selectStmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    
<h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
<p>Welcome to your Parent Dashboard.</p>
<button>
    <a href="child.php">+ Add Child</a>
</button>

    <h2>My Children</h2>

    <table>
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Date of Birth</th>
            </tr>
        </thead>

        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <td>
                    <tr><?php echo $row["child_name"]; ?></tr>
                </td>

                 <td>
                    <tr><?php echo $row["date_of_birth"]; ?></tr>
                </td>

        </tbody>
    </table>

</body>
</html>


<?php
}

?>











