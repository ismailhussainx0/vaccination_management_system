<?php

include "../config/db_connection.php";
include "parent_auth.php";

// USERNAME
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

    <title>Parent Dashboard | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "dashboard"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>Dashboard</h2>
                </div>

            </div>

            <div class="profile-chip">
                <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr($_SESSION["parent_name"], 0, 1))); ?></span>

                <span class="profile-text">
                    <strong><?php echo htmlspecialchars($_SESSION["parent_name"]); ?></strong>
                    <small>Parent Account</small>
                </span>
            </div>

        </header>

        <main class="page">

            <!-- WELCOME -->
            <div class="welcome">

                <div>
                    <span class="eyebrow">Parent Dashboard</span>

                    <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>

                    <p>Manage your children's profiles and keep track of their vaccination journey.</p>
                </div>

                <div class="welcome-icon">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>

            </div>


            <!-- QUICK ACTIONS -->
            <div class="quick-grid">

                <a href="add_child.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-child-reaching"></i></span>
                    <strong>Add Child</strong>
                    <span class="sub">Register a new child</span>
                </a>

                <a href="book_appointment.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-calendar-plus"></i></span>
                    <strong>Book Appointment</strong>
                    <span class="sub">Schedule a vaccination</span>
                </a>

                <a href="my_appointments.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-calendar-check"></i></span>
                    <strong>My Appointments</strong>
                    <span class="sub">Track booking status</span>
                </a>

                <a href="vaccination_record.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-syringe"></i></span>
                    <strong>Vaccination Records</strong>
                    <span class="sub">View completed vaccines</span>
                </a>

            </div>


            <!-- CHILDREN -->
            <section>

                <div class="section-head">

                    <div>
                        <h2>My Children</h2>
                        <p>Manage your registered children's profiles.</p>
                    </div>

                    <a href="add_child.php" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Add Child
                    </a>

                </div>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <div class="card">

                        <div class="card-head">

                            <div>
                                <h3>Children List</h3>
                                <p>Your registered children</p>
                            </div>

                            <span class="pill">
                                <i class="fa-solid fa-users"></i>
                                <?php echo mysqli_num_rows($result); ?>
                                Children
                            </span>

                        </div>

                        <div class="table-wrap">

                            <table class="data-table">

                                <thead>
                                    <tr>
                                        <th>Child Name</th>
                                        <th>Date of Birth</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                        <tr>

                                            <td data-label="Child">
                                                <div class="cell-person">
                                                    <span class="avatar-sm"><i class="fa-solid fa-child"></i></span>
                                                    <span><?php echo htmlspecialchars($row["child_name"]); ?></span>
                                                </div>
                                            </td>

                                            <td data-label="Date of Birth">
                                                <span class="cell-icon">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    <?php echo htmlspecialchars($row["date_of_birth"]); ?>
                                                </span>
                                            </td>

                                            <td data-label="Actions">

                                                <div class="row-actions">

                                                    <!-- EDIT -->
                                                    <a href="edit_child.php?child_id=<?php echo $row["child_id"]; ?>"
                                                       class="action-btn edit-btn">
                                                        <i class="fa-solid fa-pen"></i>
                                                        Edit
                                                    </a>

                                                    <!-- DELETE -->
                                                    <form action="delete_child.php"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this child?');">

                                                        <input type="hidden"
                                                               name="child_id"
                                                               value="<?php echo $row["child_id"]; ?>">

                                                        <button type="submit" class="action-btn delete-btn">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete
                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-children"></i>
                        </div>

                        <h3>No Children Added Yet</h3>

                        <p>You haven't registered any children yet. Add your first child to get started.</p>

                        <a href="add_child.php" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Your First Child
                        </a>

                    </div>

                <?php endif; ?>

            </section>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. All rights reserved.
        </footer>

    </div>

</div>

<script src="JS/parent.js"></script>

</body>
</html>

<?php
$selectStmt->close();
?>
