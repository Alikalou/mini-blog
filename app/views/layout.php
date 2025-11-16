<!DOCTYPE html>
<?php
require_once __DIR__.'/../../core/flash.php';
/*
A2) Layout integration

Goal: Use your existing app/views/layout.php as the single app shell.

Ensure your base Controller::render(...) buffers the child view into $content, then includes app/views/layout.php.

Standardize on $title (optional) and $content (required) for the layout.

Done when: All pages render inside the same layout without duplicating headers/footers in child views.*/
?>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Mini Blog') ?></title>
</head>
<body>

  <main>
    <?php if($flash=Flash::getFlash()):?>
      <div class="flash <?= htmlspecialchars($flash['level'], ENT_QUOTES) ?>">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES) ?>
      </div>
  <?php endif; ?>
  <?= $content ?>

</body>
</html>
