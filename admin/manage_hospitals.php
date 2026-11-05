
<?php

include "../config/db_connection.php";
include "admin_auth.php";

// FETCH ALL HOSPITALS
$sql = "SELECT hospital_id, hospital_name, hospital_address, hospital_email, hospital_phone
        FROM hospital
        ORDER BY hospital_id DESC";

$result = mysqli_query($connect, $sql);

if (!$result) {
    die("Error fetching hospitals: " . mysqli_error($connect));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hospitals | Admin Panel</title>

    <link rel="stylesheet" href="CSS/manage-hospitals.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-logo">
                <i class="fa-solid fa-shield-heart"></i>
                <h2>VaxCare Admin</h2>
            </div>

            <nav class="sidebar-nav">

                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>

                <a href="manage_hospitals.php" class="active">
                    <i class="fa-solid fa-hospital"></i>
                    Manage Hospitals
                </a>

                <a href="manage_vaccines.php">
                    <i class="fa-solid fa-syringe"></i>
                    Manage Vaccines
                </a>

                <a href="admin_logout.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </nav>

        </aside>


        <!-- MAIN CONTENT -->
        <main class="main-content">

            <header class="topbar">
                <div>
                    <h1>Manage Hospitals</h1>
                    <p>View and manage registered hospitals.</p>
                </div>

                <div class="admin-info">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>
                        <?php echo htmlspecialchars($_SESSION["adminName"]); ?>
                    </span>
                </div>
            </header>


            <!-- HOSPITALS SECTION -->
            <section class="content-section">

                <div class="section-header">
                    <div>
                        <h2>All Hospitals</h2>
                        <p>Registered hospitals in the system</p>
                    </div>

                    <a href="add_hospital.php" class="add-btn">
                        <i class="fa-solid fa-plus"></i>
                        Add Hospital
                    </a>
                </div>


                <div class="table-container">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hospital Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($result) > 0) { ?>

                                <?php while ($hospital = mysqli_fetch_assoc($result)) { ?>

                                    <tr>

                                        <td>
                                            <?php echo $hospital["hospital_id"]; ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($hospital["hospital_name"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($hospital["hospital_address"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($hospital["hospital_email"]); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($hospital["hospital_phone"]); ?>
                                        </td>

                                        <td class="action-buttons">

                                            <a href="edit_hospital.php?id=<?php echo $hospital["hospital_id"]; ?>" class="edit-btn">
                                                <i class="fa-solid fa-pen"></i>
                                                Edit
                                            </a>

                                        <form action="delete_hospital.php" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this hospital?');">

                                        <input type="hidden" name="hospital_id"
                                            value="<?php echo $hospital["hospital_id"]; ?>">

                                        <button type="submit" class="delete-btn">
                                            <i class="fa-solid fa-trash"></i>
                                            Delete
                                        </button>

                                    </form>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="6" class="empty-message">
                                        No hospitals registered yet.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</body>
</html>

