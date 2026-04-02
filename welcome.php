<?php
require_once 'config.php';

if (!isLoggedIn()) {
    $_SESSION['login_error'] = 'Vui lòng đăng nhập để tiếp tục.';
    redirect('index.php');
}

$username   = $_SESSION['username'];
$loginTime  = date('H:i:s d/m/Y', $_SESSION['login_time']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chào mừng – <?= htmlspecialchars($username) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:      #0a0a0f;
      --surface: #111118;
      --border:  #2a2a3a;
      --accent:  #c9a96e;
      --accent2: #e8c98a;
      --text:    #e8e8f0;
      --muted:   #888899;
      --success: #5ccea0;
    }

    body {
      min-height: 100vh;
      background: var(--bg);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      position: relative;
      overflow: hidden;
      padding: 32px 16px;
    }

    /* Animated background */
    .bg-orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(130px);
      pointer-events: none;
      z-index: 0;
      animation: drift 8s ease-in-out infinite alternate;
    }
    .bg-orb-1 {
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(201,169,110,.1), transparent 70%);
      top: -200px; left: -150px;
    }
    .bg-orb-2 {
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(92,206,160,.09), transparent 70%);
      bottom: -100px; right: -100px;
      animation-delay: -4s;
    }
    @keyframes drift {
      from { transform: translate(0, 0) scale(1); }
      to   { transform: translate(30px, 20px) scale(1.05); }
    }

    .card {
      position: relative;
      z-index: 1;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 60px 52px;
      width: 100%;
      max-width: 520px;
      text-align: center;
      box-shadow: 0 32px 80px rgba(0,0,0,.6);
      animation: popIn .7s cubic-bezier(.34,1.56,.64,1) both;
    }

    @keyframes popIn {
      from { opacity: 0; transform: scale(.9) translateY(20px); }
      to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .avatar {
      width: 88px; height: 88px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin-bottom: 24px;
      box-shadow: 0 12px 32px rgba(201,169,110,.3);
      animation: pulse 2.5s ease-in-out infinite;
    }

    @keyframes pulse {
      0%,100% { box-shadow: 0 12px 32px rgba(201,169,110,.3); }
      50%      { box-shadow: 0 12px 48px rgba(201,169,110,.5); }
    }

    .success-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(92,206,160,.12);
      border: 1px solid rgba(92,206,160,.3);
      color: var(--success);
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 5px 14px;
      border-radius: 20px;
      margin-bottom: 20px;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      line-height: 1.2;
      margin-bottom: 12px;
    }

    h1 span {
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .subtitle {
      font-size: 14.5px;
      color: var(--muted);
      font-weight: 300;
      margin-bottom: 36px;
      line-height: 1.6;
    }

    /* Info grid */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 36px;
    }

    .info-card {
      background: rgba(255,255,255,.03);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      text-align: left;
    }

    .info-label {
      font-size: 10.5px;
      font-weight: 500;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 6px;
    }

    .info-value {
      font-size: 14px;
      color: var(--text);
      font-weight: 400;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .info-value.accent { color: var(--accent); }

    .btn-logout {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 13px 32px;
      background: rgba(224,92,92,.1);
      border: 1px solid rgba(224,92,92,.3);
      border-radius: 10px;
      color: #e05c5c;
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 500;
      text-decoration: none;
      transition: all .25s;
    }

    .btn-logout:hover {
      background: rgba(224,92,92,.2);
      border-color: rgba(224,92,92,.5);
      transform: translateY(-2px);
    }

    .confetti {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      pointer-events: none;
      z-index: 0;
    }
  </style>
</head>
<body>
  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>
  <canvas class="confetti" id="confetti"></canvas>

  <div class="card">
    <div class="avatar">👑</div>

    <div class="success-badge">
      <span>✦</span> Đăng nhập thành công
    </div>

    <h1>Chào mừng, <span><?= htmlspecialchars($username) ?>!</span></h1>
    <p class="subtitle">
      Bạn đã đăng nhập thành công vào hệ thống.<br/>
      Phiên làm việc của bạn đang hoạt động.
    </p>

    <div class="info-grid">
      <div class="info-card">
        <div class="info-label">Tên người dùng</div>
        <div class="info-value accent"><?= htmlspecialchars($username) ?></div>
      </div>
      <div class="info-card">
        <div class="info-label">Trạng thái</div>
        <div class="info-value" style="color: var(--success);">🟢 Trực tuyến</div>
      </div>
      <div class="info-card" style="grid-column: 1/-1;">
        <div class="info-label">Thời gian đăng nhập</div>
        <div class="info-value"><?= $loginTime ?></div>
      </div>
    </div>

    <a href="logout.php" class="btn-logout">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Đăng xuất
    </a>
  </div>

  <script>
    // Simple confetti burst on load
    const canvas = document.getElementById('confetti');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const colors = ['#c9a96e','#e8c98a','#5ccea0','#e8e8f0','#c9a96e80'];
    const particles = Array.from({length: 80}, () => ({
      x: Math.random() * canvas.width,
      y: -20,
      r: Math.random() * 5 + 3,
      c: colors[Math.floor(Math.random() * colors.length)],
      vx: (Math.random() - .5) * 2,
      vy: Math.random() * 3 + 2,
      rot: Math.random() * 360,
      vr: (Math.random() - .5) * 4,
      opacity: 1
    }));

    let frame = 0;
    function animate() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      frame++;
      let alive = false;
      for (const p of particles) {
        p.x += p.vx; p.y += p.vy; p.rot += p.vr;
        p.opacity = Math.max(0, 1 - p.y / canvas.height * 1.5);
        if (p.y < canvas.height) alive = true;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.rot * Math.PI / 180);
        ctx.globalAlpha = p.opacity;
        ctx.fillStyle = p.c;
        ctx.fillRect(-p.r, -p.r/2, p.r*2, p.r);
        ctx.restore();
      }
      if (alive && frame < 200) requestAnimationFrame(animate);
    }
    setTimeout(animate, 300);
  </script>
</body>
</html>
