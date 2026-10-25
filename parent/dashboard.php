<?php

include "../config/db_connection.php";
include "parent_auth.php";

$userName = $_SESSION['parent_name'];


?>


<h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
<p>Welcome to your Parent Dashboard.</p>
<button>
    <a href="child.php">+ Add Child</a>
</button>