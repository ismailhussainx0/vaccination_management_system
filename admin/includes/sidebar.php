<?php
/*
 * Shared sidebar for every admin page.
 * Before including, set:
 *   $activePage = "dashboard" | "hospitals" | "vaccines" | "appointments";
 *   $adminBase  = ""    (pages in /admin)   or   "../"  (pages in /admin/manage_appointments)
 * Presentation only - no database or auth logic lives here.
 */
$activePage = $activePage ?? "";
$adminBase = $adminBase ?? "";

$navItems = [
    "dashboard"    => ["dashboard.php",                              "fa-chart-line",     "Dashboard"],
    "hospitals"    => ["manage_hospitals.php",                       "fa-hospital",       "Manage Hospitals"],
    "vaccines"     => ["manage_vaccines.php",                        "fa-syringe",        "Manage Vaccines"],
    "appointments" => ["manage_appointments/manage_appointment.php", "fa-calendar-check", "Manage Appointments"],
];

$sidebarName = $_SESSION["adminName"] ?? "Admin";
$sidebarInitial = strtoupper(substr($sidebarName, 0, 1));
?>
<div class="sidebar-overlay" data-menu-close></div>

<aside class="sidebar" id="sidebar" aria-label="Main navigation">

    <div class="sidebar-head">
        <a href="<?php echo $adminBase; ?>dashboard.php" class="brand">
            <span class="brand-icon"><i class="fa-solid fa-shield-heart"></i></span>
            <span class="brand-text">
                <strong>VaccineCare</strong>
                <small>Admin Panel</small>
            </span>
        </a>

        <button type="button" class="icon-btn sidebar-close" data-menu-close aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">

        <p class="nav-heading">Menu</p>

        <?php foreach ($navItems as $key => $item): ?>
            <a href="<?php echo $adminBase . $item[0]; ?>"
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
                <small>Administrator</small>
            </div>
        </div>

        <a href="<?php echo $adminBase; ?>admin_logout.php" class="nav-link logout-link"
           onclick="return confirm('Are you sure you want to logout?');">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>

    </div>

</aside>
