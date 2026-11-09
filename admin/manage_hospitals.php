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

    <title>Manage Hospitals | VaccineCare Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/admin-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "hospitals"; $adminBase = ""; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Admin Panel</p>
                    <h2>Manage Hospitals</h2>
                </div>

            </div>

            <div class="profile-chip">
                <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr($_SESSION["adminName"], 0, 1))); ?></span>

                <span class="profile-text">
                    <strong><?php echo htmlspecialchars($_SESSION["adminName"]); ?></strong>
                    <small>Administrator</small>
                </span>
            </div>

        </header>

        <main class="page">

            <div class="page-head">

                <div>
                    <span class="eyebrow">Hospital Management</span>
                    <h1>Manage Hospitals</h1>
                    <p>View and manage registered hospitals.</p>
                </div>

                <a href="add_hospital.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Add Hospital
                </a>

            </div>


            <div class="card">

                <div class="card-head">

                    <div>
                        <h3>All Hospitals</h3>
                        <p>Registered hospitals in the system</p>
                    </div>

                    <span class="pill">
                        <i class="fa-solid fa-hospital"></i>
                        <?php echo mysqli_num_rows($result); ?> Hospitals
                    </span>

                </div>


                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <div class="table-wrap">

                        <table class="data-table">

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

                                <?php while ($hospital = mysqli_fetch_assoc($result)) { ?>

                                    <tr>

                                        <td data-label="ID">
                                            <span class="id-tag">#<?php echo $hospital["hospital_id"]; ?></span>
                                        </td>

                                        <td data-label="Hospital">
                                            <div class="cell-person">
                                                <span class="avatar-sm"><i class="fa-solid fa-hospital"></i></span>
                                                <span><?php echo htmlspecialchars($hospital["hospital_name"]); ?></span>
                                            </div>
                                        </td>

                                        <td data-label="Address" class="cell-wrap">
                                            <?php echo htmlspecialchars($hospital["hospital_address"]); ?>
                                        </td>

                                        <td data-label="Email" class="cell-wrap">
                                            <?php echo htmlspecialchars($hospital["hospital_email"]); ?>
                                        </td>

                                        <td data-label="Phone">
                                            <?php echo htmlspecialchars($hospital["hospital_phone"]); ?>
                                        </td>

                                        <td data-label="Actions">

                                            <div class="row-actions">

                                                <a href="edit_hospital.php?id=<?php echo $hospital["hospital_id"]; ?>" class="action-btn edit-btn">
                                                    <i class="fa-solid fa-pen"></i>
                                                    Edit
                                                </a>

                                                <form action="delete_hospital.php" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this hospital?');">

                                                    <input type="hidden" name="hospital_id"
                                                           value="<?php echo $hospital["hospital_id"]; ?>">

                                                    <button type="submit" class="action-btn delete-btn">
                                                        <i class="fa-solid fa-trash"></i>
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                <?php } else { ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-hospital"></i>
                        </div>

                        <h3>No Hospitals Yet</h3>

                        <p>No hospitals registered yet.</p>

                        <a href="add_hospital.php" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Hospital
                        </a>

                    </div>

                <?php } ?>

            </div>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. Vaccination Management System.
        </footer>

    </div>

</div>

<script src="JS/admin.js"></script>

</body>
</html>

