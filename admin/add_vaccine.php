<?php
include "../config/db_connection.php";
include "admin_auth.php";

$vaccineName = "";
$errorMessage = "";

if (isset($_POST["add_vaccine"])) {
    $vaccineName = trim($_POST["vaccine_name"] ?? "");

    if ($vaccineName === "") {
        $errorMessage = "Please enter the vaccine name.";
    } else {
        $checkSql = "SELECT vaccine_id FROM vaccines WHERE vaccine_name = ?";
        $checkStmt = mysqli_prepare($connect, $checkSql);
        if (!$checkStmt) {
            die("Error preparing duplicate check: " . mysqli_error($connect));
        }
        $checkStmt->bind_param("s", $vaccineName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $exists = mysqli_num_rows($checkResult) > 0;
        $checkStmt->close();

        if ($exists) {
            $errorMessage = "This vaccine already exists.";
        } else {
            $insertSql = "INSERT INTO vaccines (vaccine_name) VALUES (?)";
            $insertStmt = mysqli_prepare($connect, $insertSql);
            if (!$insertStmt) {
                die("Error preparing insert query: " . mysqli_error($connect));
            }
            $insertStmt->bind_param("s", $vaccineName);
            if ($insertStmt->execute()) {
                $insertStmt->close();
                header("Location: manage_vaccines.php?message=added");
                exit;
            }
            $errorMessage = "Unable to add vaccine. Please try again.";
            $insertStmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vaccine</title>
    <link rel="stylesheet" href="CSS/manage-vaccines.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="module-nav">
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="manage_hospitals.php"><i class="fa-solid fa-hospital"></i> Manage Hospitals</a>
        <a href="manage_vaccines.php" class="active"><i class="fa-solid fa-syringe"></i> Manage Vaccines</a>
    </nav>
<main class="page-container form-page">
    <header class="page-header"><div><p class="eyebrow">Admin Panel</p><h1>Add Vaccine</h1><p class="page-subtitle">Enter the name of the new vaccine.</p></div></header>
    <section class="content-card form-card">
        <?php if ($errorMessage !== ""): ?><div class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="vaccine_name">Vaccine Name</label>
                <input type="text" id="vaccine_name" name="vaccine_name" maxlength="150" placeholder="e.g. Polio Vaccine" value="<?php echo htmlspecialchars($vaccineName); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="add_vaccine" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Vaccine</button>
                <a href="manage_vaccines.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
