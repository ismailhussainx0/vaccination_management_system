
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

    <link rel="stylesheet" href="CSS/dashboard.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <div class="dashboard-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>

                <span>VaccineCare</span>
            </div>

            <nav class="sidebar-nav">

                <p class="nav-heading">MENU</p>

                <a href="dashboard.php" class="nav-link active">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="add_child.php" class="nav-link">
                    <i class="fa-solid fa-child"></i>
                    <span>My Children</span>
                </a>

            </nav>

            <div class="sidebar-bottom">
                <div class="sidebar-info">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Your children's vaccination records in one place.</span>
                </div>
            </div>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">

                <div>
                    <p class="topbar-label">PARENT PORTAL</p>
                    <h2>Dashboard</h2>
                </div>

                <div class="profile-info">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <span>
                        <?php echo htmlspecialchars($userName); ?>
                    </span>
                </div>

            </header>

            <!-- PAGE CONTENT -->
            <section class="page-content">

                <!-- WELCOME SECTION -->
                <div class="welcome-banner">

                    <div class="welcome-text">
                        <p class="welcome-label">PARENT DASHBOARD</p>

                        <h1>
                            Welcome, <?php echo htmlspecialchars($userName); ?>!
                        </h1>

                        <p>
                            Manage your children's profiles and keep track of their vaccination journey.
                        </p>
                    </div>

                    <div class="welcome-icon">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>

                </div>

                <!-- CHILDREN SECTION -->
                <section class="children-section">

                    <div class="section-header">

                        <div>
                            <h2>My Children</h2>
                            <p>Manage your registered children's profiles.</p>
                        </div>

                        <a href="add_child.php" class="add-child-btn">
                            <i class="fa-solid fa-plus"></i>
                            Add Child
                        </a>

                    </div>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <div class="table-card">

                            <div class="table-heading">
                                <div>
                                    <h3>Children List</h3>
                                    <p>Your registered children</p>
                                </div>

                                <div class="children-count">
                                    <i class="fa-solid fa-users"></i>
                                    <?php echo mysqli_num_rows($result); ?>
                                    Children
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table class="children-table">

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

                                                <td>
                                                    <div class="child-name-cell">

                                                        <div class="child-avatar">
                                                            <i class="fa-solid fa-child"></i>
                                                        </div>

                                                        <span>
                                                            <?php echo htmlspecialchars($row["child_name"]); ?>
                                                        </span>

                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="dob-text">
                                                        <i class="fa-regular fa-calendar"></i>
                                                        <?php echo htmlspecialchars($row["date_of_birth"]); ?>
                                                    </span>
                                                </td>

                                                <td>

                                                    <div class="action-buttons">

                                                        <!-- EDIT -->
                                                        <a
                                                            href="edit_child.php?child_id=<?php echo $row["child_id"]; ?>"
                                                            class="action-btn edit-btn"
                                                        >
                                                            <i class="fa-solid fa-pen"></i>
                                                            Edit
                                                        </a>

                                                        <!-- DELETE -->
                                                        <form
                                                            action="delete_child.php"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this child?');"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="child_id"
                                                                value="<?php echo $row["child_id"]; ?>"
                                                            >

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

                            <p>
                                You haven't registered any children yet. Add your first child to get started.
                            </p>

                            <a href="add_child.php" class="add-child-btn">
                                <i class="fa-solid fa-plus"></i>
                                Add Your First Child
                            </a>

                        </div>

                    <?php endif; ?>

                </section>

                <footer class="dashboard-footer">
                    © 2026 VaccineCare. All rights reserved.
                </footer>

            </section>

        </main>

    </div>

</body>
</html>

<?php
$selectStmt->close();
?>
