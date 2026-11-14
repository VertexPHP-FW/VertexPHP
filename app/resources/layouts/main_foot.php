<?php
function setFooter($args = null, ...$scripts) { ?>
</main>
<footer class="border-top py-3 mt-5"><div class="container text-muted small"><?= htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') ?></div></footer>
<script src="<?= JS ?>lib/jquery.min.js"></script>
<script src="<?= JS ?>lib/bootstrap.bundle.min.js"></script>
<script>window.APP_ROUTES = <?= json_encode([
    'home'           => route('home'),
    'login'          => route('login'),
    'loginSubmit'    => route('login.submit'),
    'register'       => route('register'),
    'registerSubmit' => route('register.submit'),
    'logout'         => route('logout'),
    'passwordChange' => route('password.change'),
    'passwordCheck'  => route('password.check'),
    'passwordUpdate' => route('password.update'),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;</script>
<script src="<?= JS ?>app.js"></script>
<script type="module" src="<?= htmlspecialchars(vite_asset('app/resources/js/main.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<?php }
function closeFooter() { ?>
</body>
</html>
<?php }
