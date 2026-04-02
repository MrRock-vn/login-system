<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('welcome.php');
}

$error   = $_SESSION['reg_error']   ?? '';
$success = $_SESSION['reg_success'] ?? '';
unset($_SESSION['reg_error'], $_SESSION['reg_success']);
$old = $_SESSION['reg_old'] ?? [];
unset($_SESSION['reg_old']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng Ký</title>
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
      padding: 32px 16px;
    }

    body::before {
      content: '';
      position: fixed;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(92,206,160,.1), transparent 70%);
      top: -100px; right: -100px;
      border-radius: 50%;
      filter: blur(120px);
      pointer-events: none;
    }

    .card {
      position: relative;
      z-index: 1;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 52px 48px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 32px 80px rgba(0,0,0,.6);
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
      background: linear-gradient(135deg, #5ccea0, #3db88a);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      font-size: 26px;
      box-shadow: 0 8px 24px rgba(92,206,160,.3);
    }

    .brand h1 {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      color: var(--text);
    }

    .brand p {
      font-size: 13.5px;
      color: var(--muted);
      margin-top: 6px;
      font-weight: 300;
    }

    .divider { height: 1px; background: var(--border); margin-bottom: 32px; }

    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 24px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }
    .alert-error   { background: rgba(224,92,92,.12); border: 1px solid rgba(224,92,92,.3); color: var(--error); animation: shake .4s ease; }
    .alert-success { background: rgba(92,206,160,.12); border: 1px solid rgba(92,206,160,.3); color: var(--success); }
    @keyframes shake {
      0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)}
    }

    .field { margin-bottom: 18px; }

    label {
      display: block;
      font-size: 11.5px;
      font-weight: 500;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }

    .input-wrap { position: relative; }

    .input-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    input[type="text"],
    input[type="email"],
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
      border-color: #5ccea0;
      box-shadow: 0 0 0 3px rgba(92,206,160,.12);
    }

    /* Password strength */
    .strength-bar {
      height: 3px;
      border-radius: 3px;
      background: var(--border);
      margin-top: 8px;
      overflow: hidden;
    }
    .strength-fill {
      height: 100%;
      width: 0;
      border-radius: 3px;
      transition: width .3s, background .3s;
    }
    .strength-label {
      font-size: 11px;
      color: var(--muted);
      margin-top: 4px;
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
    .toggle-pw:hover { color: #5ccea0; }

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

    .btn-success {
      background: linear-gradient(135deg, #5ccea0, #3db88a);
      color: #0a0a0f;
      box-shadow: 0 6px 20px rgba(92,206,160,.25);
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(92,206,160,.35);
    }

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

    .hint {
      font-size: 11.5px;
      color: var(--muted);
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="brand">
      <div class="brand-icon">✨</div>
      <h1>Tạo Tài Khoản</h1>
      <p>Điền thông tin để đăng ký tài khoản mới.</p>
    </div>
    <div class="divider"></div>

    <?php if ($error): ?>
      <div class="alert alert-error">
        <span>⚠️</span>
        <div><?= nl2br(htmlspecialchars($error)) ?></div>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success">
        <span>✅</span> <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form action="register_handler.php" method="POST" autocomplete="off">
      <div class="field">
        <label for="username">Tên người dùng</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
          <input type="text" id="username" name="username"
                 placeholder="3–30 ký tự, chữ và số"
                 value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                 required autofocus />
        </div>
        <p class="hint">Chỉ dùng chữ cái, số và dấu gạch dưới (_)</p>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          <input type="email" id="email" name="email"
                 placeholder="example@email.com"
                 value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                 required />
        </div>
      </div>

      <div class="field">
        <label for="password">Mật khẩu</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input type="password" id="password" name="password"
                 placeholder="Tối thiểu 8 ký tự"
                 oninput="checkStrength(this.value)"
                 required />
          <button type="button" class="toggle-pw" onclick="togglePw('password')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
        <p class="strength-label" id="strengthLabel">Nhập mật khẩu để kiểm tra độ mạnh</p>
      </div>

      <div class="field">
        <label for="confirm_password">Xác nhận mật khẩu</label>
        <div class="input-wrap">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="9 11 12 14 22 4"/>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
          </svg>
          <input type="password" id="confirm_password" name="confirm_password"
                 placeholder="Nhập lại mật khẩu"
                 required />
          <button type="button" class="toggle-pw" onclick="togglePw('confirm_password')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-success">Tạo Tài Khoản →</button>
    </form>

    <p class="footer-link">
      Đã có tài khoản? <a href="index.php">Đăng nhập</a>
    </p>
  </div>

  <script>
    function togglePw(id) {
      const i = document.getElementById(id);
      i.type = i.type === 'password' ? 'text' : 'password';
    }

    function checkStrength(pw) {
      const fill  = document.getElementById('strengthFill');
      const label = document.getElementById('strengthLabel');
      let score = 0;
      if (pw.length >= 8)  score++;
      if (/[A-Z]/.test(pw)) score++;
      if (/[0-9]/.test(pw)) score++;
      if (/[^A-Za-z0-9]/.test(pw)) score++;

      const levels = [
        { pct: '0%',   color: '',          text: 'Nhập mật khẩu để kiểm tra độ mạnh' },
        { pct: '25%',  color: '#e05c5c',   text: '⚠️ Yếu' },
        { pct: '50%',  color: '#e08c3c',   text: '🔶 Trung bình' },
        { pct: '75%',  color: '#c9a96e',   text: '🔷 Khá mạnh' },
        { pct: '100%', color: '#5ccea0',   text: '✅ Mạnh' },
      ];
      const lv = levels[score];
      fill.style.width = lv.pct;
      fill.style.background = lv.color;
      label.textContent = lv.text;
    }
  </script>
</body>
</html>
