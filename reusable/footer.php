<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<footer id="footer" class="py-4">
        <!-- Footer Bottom Section -->
        <div class="footer-bottom text-center mt-4">
            &copy; <?php echo date('Y'); ?> National Parks Ecology. All Rights Reserved.
        </div>
    </div>
</footer>