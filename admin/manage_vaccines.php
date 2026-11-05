<?php
include "../config/db_connection.php";
include "admin_auth.php";

$sql = "SELECT vaccine_id, vaccine_name FROM vaccines ORDER BY vaccine_id DESC";
$result = mysqli_query($connect, $sql);
if (!$result) {
    die("Error fetching vaccines: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vaccines</title>
    <link rel="stylesheet" href="CSS/manage-vaccines.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="module-nav">
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="manage_hospitals.php"><i class="fa-solid fa-hospital"></i> Manage Hospitals</a>
        <a href="manage_vaccines.php" class="active"><i class="fa-solid fa-syringe"></i> Manage Vaccines</a>
    </nav>
<main class="page-container">
    <header class="page-header">
        <div>
            <p class="eyebrow">Admin Panel</p>
            <h1>Manage Vaccines</h1>
            <p class="page-subtitle">Add, view, update and remove vaccines.</p>
        </div>
        <a href="add_vaccine.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Vaccine</a>
    </header>

    <section class="content-card">
        <div class="card-heading">
            <h2>Vaccine List</h2>
            <span class="count-badge"><?php echo mysqli_num_rows($result); ?> vaccines</span>
        </div>

        <?php if (isset($_GET["message"]) && $_GET["message"] === "added"): ?>
            <div class="alert alert-success">Vaccine added successfully.</div>
        <?php elseif (isset($_GET["message"]) && $_GET["message"] === "updated"): ?>
            <div class="alert alert-success">Vaccine updated successfully.</div>
        <?php elseif (isset($_GET["message"]) && $_GET["message"] === "deleted"): ?>
            <div class="alert alert-success">Vaccine deleted successfully.</div>
        <?php endif; ?>

        <div class="table-wrapper">
            <table>
                <thead><tr><th>Vaccine ID</th><th>Vaccine Name</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($vaccine = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo (int) $vaccine["vaccine_id"]; ?></td>
                            <td><?php echo htmlspecialchars($vaccine["vaccine_name"]); ?></td>
                            <td>
                                <div class="action-group">
                                    <a class="btn btn-edit" href="edit_vaccine.php?id=<?php echo (int) $vaccine["vaccine_id"]; ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <form action="delete_vaccine.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this vaccine?');">
                                        <input type="hidden" name="vaccine_id" value="<?php echo (int) $vaccine["vaccine_id"]; ?>">
                                        <button type="submit" class="btn btn-delete"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="empty-state">No vaccines have been added yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <a href="dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
</main>
</body>
</html>
<?php mysqli_free_result($result); ?>
