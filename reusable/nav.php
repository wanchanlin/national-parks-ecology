<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav id="navbar">
    <div class="logo"><a href="../index.php">National Parks Ecology</a></div>
    <div>
        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>

            <?php if (isset($_SESSION['id'])): // Only for logged-in users ?>
                <li><a href="../users/manageParks.php">Manage Parks</a></li>
                <li><a href="../users/manageAccount.php">Manage My Account</a></li>
                <li><a href="../users/logout.php">Logout</a></li>

                <?php if ($_SESSION['is_admin'] === 'Yes'): // Only for admins ?>
                    <li><a href="../admin/adminDashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>

            <?php else: // Public (not logged in) users ?>
                <li><a href="../index.php">Login</a></li>
                <li><a href="../public/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
