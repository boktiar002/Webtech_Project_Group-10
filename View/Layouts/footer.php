<footer style="text-align:center; padding:30px; margin-top:40px;

    background:#1a1a2e; color:#aaa; font-size:13px;">
    &copy; <?= date('Y') ?>

    <?= htmlspecialchars($config['site_name'] ?? 'BlogNews Platform') ?> -
    <?= htmlspecialchars($config['site_description'] ?? 'Group 10') ?> |

    <a href="mailto:<?= htmlspecialchars($config['contact_email'] ?? '') ?>" style="color:#aaa;">
        <?= htmlspecialchars($config['contact_email'] ?? '') ?>
    </a>


</footer>
</body>
</html>
