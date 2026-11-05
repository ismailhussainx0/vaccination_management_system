<?php
include "../config/db_connection.php";
include "admin_auth.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_vaccines.php");
    exit;
}
$vaccineId = (int) $_GET["id"];
$errorMessage = "";

$fetchSql = "SELECT vaccine_id, vaccine_name FROM vaccines WHERE vaccine_id = ?";
$fetchStmt = mysqli_prepare($connect, $fetchSql);
if (!$fetchStmt) {
    die("Error preparing fetch query: " . mysqli_error($connect));
}
$fetchStmt->bind_param("i", $vaccineId);
$fetchStmt->execute();
$fetchResult = $fetchStmt->get_result();
$vaccine = mysqli_fetch_assoc($fetchResult);
$fetchStmt->close();

if (!$vaccine) {
    header("Location: manage_vaccines.php");
    exit;
}

if (isset($_POST["update_vaccine"])) {
    $vaccineName = trim($_POST["vaccine_name"] ?? "");

    if ($vaccineName === "") {
        $errorMessage = "Please enter the vaccine name.";
    } else {
        $checkSql = "SELECT vaccine_id FROM vaccines WHERE vaccine_name = ? AND vaccine_id != ?";
        $checkStmt = mysqli_prepare($connect, $checkSql);
        if (!$checkStmt) {
            die("Error preparing duplicate check: " . mysqli_error($connect));
        }
        $checkStmt->bind_param("si", $vaccineName, $vaccineId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $duplicate = mysqli_num_rows($checkResult) > 0;
        $checkStmt->close();

        if ($duplicate) {
            $errorMessage = "Another vaccine already uses this name.";
        } else {
            $updateSql = "UPDATE vaccines SET vaccine_name = ? WHERE vaccine_id = ?";
            $updateStmt = mysqli_prepare($connect, $updateSql);
            if (!$updateStmt) {
                die("Error preparing update query: " . mysqli_error($connect));
            }
            $updateStmt->bind_param("si", $vaccineName, $vaccineId);
            if ($updateStmt->execute()) {
                $updateStmt->close();
                header("Location: manage_vaccines.php?message=updated");
                exit;
            }
            $errorMessage = "Unable to update vaccine. Please try again.";
            $updateStmt->close();
        }
    }
    $vaccine["vaccine_name"] = $vaccineName;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vaccine</title>
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
    <header class="page-header"><div><p class="eyebrow">Admin Panel</p><h1>Edit Vaccine</h1><p class="page-subtitle">Update the vaccine name.</p></div></header>
    <section class="content-card form-card">
        <?php if ($errorMessage !== ""): ?><div class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="vaccine_name">Vaccine Name</label>
                <input type="text" id="vaccine_name" name="vaccine_name" maxlength="150" value="<?php echo htmlspecialchars($vaccine["vaccine_name"]); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="update_vaccine" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Update Vaccine</button>
                <a href="manage_vaccines.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
