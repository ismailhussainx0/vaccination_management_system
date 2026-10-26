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

    <?php

    if(mysqli_num_rows($result) > 0){
    
    ?>
    <table border=1>
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Date of Birth</th>
                <th>Delete Child</th>
            </tr>
        </thead>

        <tbody>

            <?php

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                
                <tr>
                    <td><?php echo htmlspecialchars($row["child_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_of_birth"]); ?></td>

                    <td>
                        <a href="child_delete.php?child_id=<?php echo $row["child_id"]?>">Delete Child</a>
                    </td>
                </tr>

            <?php
            }

          
            ?>

        </tbody>
    </table>

<?php

}else{
    echo "No children added yet";
}

$selectStmt->close();


?>


</body>
</html>














