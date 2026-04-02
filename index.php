<?php
require_once 'config.php';

// Nếu đã đăng nhập → chuyển tới trang chào mừng
if (isLoggedIn()) {
    redirect('welcome.php');
}

$error   = $_SESSION['login_error']   ?? '';
$success = $_SESSION['login_success'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_success']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng Nhập</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0a0a0f;
      --surface:   #111118;
      --border:    #2a2a3a;
      --accent:    #c9a96e;
      --accent2:   #e8c98a;
      --text:      #e8e8f0;
      --muted:     #888899;
      --error:     #e05c5c;
      --success:   #5ccea0;
      --input-bg:  #18181f;
    }

    body {
      min-height: 100vh;
      background: var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      position: relative;
      overflow: hidden;
    }

    /* Background orbs */
    body::before, body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      filter: blur(120px);
      pointer-events: none;
      z-index: 0;
    }
    body::before {
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(201,169,110,.12), transparent 70%);
      top: -100px; left: -100px;
    }
    body::after {
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(92,206,160,.08), transparent 70%);
      bottom: -80px; right: -80px;
    }

    .card {
      position: relative;
      z-index: 1;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 52px 48px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(201,169,110,.05);
      animation: fadeUp .6s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .brand {
      text-align: center;
      margin-bottom: 36px;
    }

    .brand-icon {
      width: 56px; height: 56px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      font-size: 26px;
      box-shadow: 0 8px 24px rgba(201,169,110,.3);
    }

    .brand h1 {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      letter-spacing: .5px;
      color: var(--text);
    }

    .brand p {
      font-size: 13.5px;
      color: var(--muted);
      margin-top: 6px;
      font-weight: 300;
    }

    .divider {
      height: 1px;
      background: var(--border);
      margin-bottom: 32px;
    }

    /* Alert messages */
    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: shake .4s ease;
    }
    @keyframes shake {
      0%,100%{transform:translateX(0)}
      25%{transform:translateX(-6px)}
      75%{transform:translateX(6px)}
    }
    .alert-error   { background: rgba(224,92,92,.12); border: 1px solid rgba(224,92,92,.3); color: var(--error); }
    .alert-success { background: rgba(92,206,160,.12); border: 1px solid rgba(92,206,160,.3); color: var(--success); animation: none; }

    /* Form */
    .field { margin-bottom: 20px; }

    label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 13px 14px 13px 42px;
      color: var(--text);
      font-size: 14.5px;
      font-family: 'DM Sans', sans-serif;
      transition: border-color .25s, box-shadow .25s;
      outline: none;
    }

    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(201,169,110,.12);
    }

    .toggle-pw {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: var(--muted);
      padding: 4px;
      transition: color .2s;
    }
    .toggle-pw:hover { color: var(--accent); }

    .btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 10px;
      font-size: 14.5px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 500;
      cursor: pointer;
      transition: all .25s;
      margin-top: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color: #0a0a0f;
      letter-spacing: .4px;
      box-shadow: 0 6px 20px rgba(201,169,110,.25);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(201,169,110,.35);
    }
    .btn-primary:active { transform: translateY(0); }

    .footer-link {
      text-align: center;
      margin-top: 24px;
      font-size: 13.5px;
      color: var(--muted);
    }

    .footer-link a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      transition: color .2s;
    }
    .footer-link a:hover { color: var(--accent2); }
  </style>
</head>
<body>
  <div class="card">
    <div class="brand">
      <div class="brand-icon">🔐</div>
      <h1>Đăng Nhập</h1>
      <p>Chào mừng trở lại! Vui lòng nhập thông tin của bạn.</p>
    </div>
    <div class="divider"></div>

    <?php if ($error): ?>
      <div class="alert alert-error">
        <span>⚠️</span> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success">
        <span>✅</span> <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST" autocomplete="off">
      <div class="field">
        <label for="username">Tên người dùng</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
          <input type="text" id="username" name="username"
                 placeholder="Nhập tên người dùng"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                 required autofocus />
        </div>
      </div>

      <div class="field">
        <label for="password">Mật khẩu</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input type="password" id="password" name="password"
                 placeholder="Nhập mật khẩu" required />
          <button type="button" class="toggle-pw" onclick="togglePw(this)" aria-label="Hiện/ẩn mật khẩu">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Đăng Nhập →</button>
    </form>

    <p class="footer-link">
      Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
    </p>
  </div>

  <script>
    function togglePw(btn) {
      const input = btn.closest('.input-wrap').querySelector('input');
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
