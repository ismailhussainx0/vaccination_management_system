<?php
/*
 * Shared sidebar for all logged-in parent pages.
 * Set $activePage before including: dashboard | add_child | book | appointments | records
 * Presentation only - no database or auth logic lives here.
 */
$activePage = $activePage ?? "";

$navItems = [
    "dashboard"    => ["dashboard.php",          "fa-house",           "Dashboard"],
    "add_child"    => ["add_child.php",          "fa-child-reaching",  "Add Child"],
    "book"         => ["book_appointment.php",   "fa-calendar-plus",   "Book Appointment"],
    "appointments" => ["my_appointments.php",    "fa-calendar-check",  "My Appointments"],
    "records"      => ["vaccination_record.php", "fa-syringe",         "Vaccination Records"],
];

$sidebarName = $_SESSION["parent_name"] ?? "Parent";
$sidebarInitial = strtoupper(substr($sidebarName, 0, 1));
?>
<div class="sidebar-overlay" data-menu-close></div>

<aside class="sidebar" id="sidebar" aria-label="Main navigation">

    <div class="sidebar-head">
        <a href="dashboard.php" class="brand">
            <span class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></span>
            <span class="brand-text">
                <strong>VaccineCare</strong>
                <small>Parent Portal</small>
            </span>
        </a>

        <button type="button" class="icon-btn sidebar-close" data-menu-close aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">

        <p class="nav-heading">Menu</p>

        <?php foreach ($navItems as $key => $item): ?>
            <a href="<?php echo $item[0]; ?>"
               class="nav-link<?php echo $activePage === $key ? ' active' : ''; ?>"
               <?php echo $activePage === $key ? 'aria-current="page"' : ''; ?>>
                <i class="fa-solid <?php echo $item[1]; ?>"></i>
                <span><?php echo $item[2]; ?></span>
            </a>
        <?php endforeach; ?>

    </nav>

    <div class="sidebar-foot">

        <div class="sidebar-user">
            <span class="avatar"><?php echo htmlspecialchars($sidebarInitial); ?></span>
            <div>
                <strong><?php echo htmlspecialchars($sidebarName); ?></strong>
                <small>Parent Account</small>
            </div>
        </div>

        <a href="logout.php" class="nav-link logout-link"
           onclick="return confirm('Are you sure you want to logout?');">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>

    </div>

</aside>
