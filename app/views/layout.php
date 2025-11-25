<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Mini Blog', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>

  <nav>
    <?php if ($user !== null): ?>
      <span><?= htmlspecialchars('Logged in as ' . ($user['name'] ?? 'User'), ENT_QUOTES, 'UTF-8') ?></span>
      <a href="/posts/create">Write a post</a>
      <a href="/logout">Log out</a>
    <?php else: ?>
      <span>You are a guest</span>
      <a href="/register">Create an account</a>
      <a href="/login">Log in</a>
    <?php endif; ?>
  </nav>

  <main>
    <?php if ($flash = Flash::getFlash()): ?>
      <div class="flash <?= htmlspecialchars($flash['level'], ENT_QUOTES, 'UTF-8') ?>">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?= $content ?>
  </main>

</body>
</html>
