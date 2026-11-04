
<?php

include "../config/db_connection.php";
include "parent_auth.php";


$showRecordSql = "SELECT

                    vaccination_record.record_id,
                    child.child_name,
                    vaccines.vaccine_name,
                    hospital.hospital_name,
                    vaccination_record.vaccination_status,
                    vaccination_record.vaccination_date

                    FROM vaccination_record

                    JOIN appointments
                    ON vaccination_record.appointment_id = appointments.appointment_id

                    JOIN child
                    ON appointments.child_id = child.child_id

                    JOIN vaccines
                    ON appointments.vaccine_id = vaccines.vaccine_id

                    JOIN hospital
                    ON appointments.hospital_id = hospital.hospital_id

                    WHERE child.parent_id = ?";


$showRecordStmt = mysqli_prepare($connect, $showRecordSql);

$showRecordStmt->bind_param("i", $_SESSION["parent_id"]);

$showRecordStmt->execute();

$result = $showRecordStmt->get_result();

$totalRecords = mysqli_num_rows($result);


// Vaccination status badge
function getVaccinationStatusClass($status) {

    switch (strtolower($status)) {

        case "vaccinated":
            return "status-vaccinated";

        case "pending":
            return "status-pending";

        default:
            return "status-default";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vaccination History | VaxCare</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/vaccination-history.css">

</head>

<body>

<div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">

            <div class="brand-icon">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>

            <div class="brand-text">
                <h2>VaxCare</h2>
                <span>Parent Portal</span>
            </div>

        </div>


        <div class="sidebar-menu">

            <p class="menu-heading">MAIN MENU</p>

            <a href="dashboard.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <a href="add_child.php" class="nav-link">
                <i class="fa-solid fa-child"></i>
                <span>Add Child</span>
            </a>

            <a href="book_appointment.php" class="nav-link">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Book Appointment</span>
            </a>

            <a href="my_appointments.php" class="nav-link">
                <i class="fa-solid fa-calendar-check"></i>
                <span>My Appointments</span>
            </a>

            <a href="vaccination_history.php" class="nav-link active">
                <i class="fa-solid fa-syringe"></i>
                <span>Vaccination History</span>
            </a>

        </div>


        <div class="sidebar-bottom">

            <div class="sidebar-help">

                <div class="help-icon">
                    <i class="fa-solid fa-circle-question"></i>
                </div>

                <div>
                    <h4>Need Help?</h4>
                    <p>Contact support for assistance.</p>
                </div>

            </div>


            <a href="logout.php"
               class="nav-link logout-link"
               onclick="return confirm('Are you sure you want to logout?');">

                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>

            </a>

        </div>

    </aside>


    <!-- Main Content -->
    <main class="main-content">


        <!-- Topbar -->
        <header class="topbar">

            <div class="topbar-left">

                <button class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <p class="breadcrumb">Parent Portal / Vaccination History</p>
                    <h2>Vaccination History</h2>
                </div>

            </div>


            <div class="topbar-right">

                <div class="topbar-icon">
                    <i class="fa-regular fa-bell"></i>
                </div>

                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>

            </div>

        </header>


        <!-- Page Content -->
        <section class="page-content">


            <!-- Page Heading -->
            <div class="page-heading">

                <div>
                    <h1>Vaccination History</h1>

                    <p>
                        Track the vaccination records of all your children.
                    </p>
                </div>

                <a href="book_appointment.php" class="book-btn">

                    <i class="fa-solid fa-plus"></i>
                    Book Appointment

                </a>

            </div>


            <!-- Summary Card -->
            <div class="summary-card">

                <div class="summary-icon">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>

                <div class="summary-info">

                    <p>Total Vaccination Records</p>

                    <h2><?php echo $totalRecords; ?></h2>

                </div>

            </div>


            <!-- History Card -->
            <div class="history-card">


                <div class="card-header">

                    <div>
                        <h3>Vaccination Records</h3>

                        <p>
                            A record of vaccines administered to your children.
                        </p>
                    </div>

                    <span class="record-count">
                        <?php echo $totalRecords; ?> Records
                    </span>

                </div>


                <?php if ($totalRecords > 0) { ?>


                    <!-- Records Table -->
                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>
                                    <th>Record ID</th>
                                    <th>Child</th>
                                    <th>Vaccine</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Vaccination Date</th>
                                </tr>

                            </thead>


                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>

                                        <td>
                                            <span class="record-id">
                                                #<?php echo htmlspecialchars($row["record_id"]); ?>
                                            </span>
                                        </td>


                                        <td>

                                            <div class="child-info">

                                                <div class="child-icon">
                                                    <i class="fa-solid fa-child"></i>
                                                </div>

                                                <span>
                                                    <?php echo htmlspecialchars($row["child_name"]); ?>
                                                </span>

                                            </div>

                                        </td>


                                        <td>

                                            <div class="vaccine-info">

                                                <i class="fa-solid fa-syringe"></i>

                                                <?php echo htmlspecialchars($row["vaccine_name"]); ?>

                                            </div>

                                        </td>


                                        <td>

                                            <div class="hospital-info">

                                                <i class="fa-solid fa-hospital"></i>

                                                <?php echo htmlspecialchars($row["hospital_name"]); ?>

                                            </div>

                                        </td>


                                        <td>

                                            <span class="status-badge <?php echo getVaccinationStatusClass($row["vaccination_status"]); ?>">

                                                <span class="status-dot"></span>

                                                <?php echo htmlspecialchars($row["vaccination_status"]); ?>

                                            </span>

                                        </td>


                                        <td>

                                            <?php

                                            if (!empty($row["vaccination_date"])) {

                                                echo date(
                                                    "d M Y",
                                                    strtotime($row["vaccination_date"])
                                                );

                                            } else {

                                                echo "Not available";

                                            }

                                            ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>


                <?php } else { ?>


                    <!-- Empty State -->
                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-syringe"></i>
                        </div>

                        <h3>No Vaccination Records Yet</h3>

                        <p>
                            Vaccination records will appear here once
                            your children's vaccinations have been completed
                            and recorded by the hospital.
                        </p>

                        <a href="my_appointments.php" class="empty-btn">

                            <i class="fa-solid fa-calendar-check"></i>
                            View My Appointments

                        </a>

                    </div>


                <?php } ?>


            </div>

        </section>

    </main>

</div>


<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>


<!-- JavaScript -->
<script>

    const menuToggle = document.getElementById("menuToggle");

    const sidebar = document.getElementById("sidebar");

    const sidebarOverlay = document.getElementById("sidebarOverlay");


    menuToggle.addEventListener("click", function () {

        sidebar.classList.toggle("show");

        sidebarOverlay.classList.toggle("active");

    });


    sidebarOverlay.addEventListener("click", function () {

        sidebar.classList.remove("show");

        sidebarOverlay.classList.remove("active");

    });

</script>


</body>
</html>

<?php

$showRecordStmt->close();

?>
