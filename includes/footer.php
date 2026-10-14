<?php
if (!function_exists('getBibleVerse')) require_once __DIR__ . '/../config/database.php';
if (!function_exists('getBibleVerse')) require_once __DIR__ . '/functions.php';
$verse = getBibleVerse();
?>
    </main>
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <span class="logo-icon">✧</span>
                    <strong><?php echo e(SITE_NAME); ?></strong>
                    <p>Faith-centered education for tomorrow's leaders.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/academics.php">Academics</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/admissions.php">Admissions</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/enroll.php">Enroll Online</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p><?php echo e(getSetting('address', defined('DEFAULT_ADDRESS') ? DEFAULT_ADDRESS : 'Rizal Ave. Ext. Hiway Batingan, Binangonan, Philippines, 1940')); ?></p>
                    <p>Tel: <a href="tel:09976199710"><?php echo e(getSetting('phone', defined('DEFAULT_PHONE') ? DEFAULT_PHONE : '0997 619 9710')); ?></a></p>
                    <p>Email: <a href="mailto:<?php echo e(ADMIN_EMAIL); ?>"><?php echo e(ADMIN_EMAIL); ?></a></p>
                    <?php if (defined('FACEBOOK_URL') && FACEBOOK_URL): ?>
                    <p><a href="<?php echo e(FACEBOOK_URL); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">Facebook</a></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-verse">
                <blockquote>&ldquo;<?php echo e($verse['verse_text']); ?>&rdquo;</blockquote>
                <cite>— <?php echo e($verse['reference']); ?></cite>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo e(SITE_NAME); ?> All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
