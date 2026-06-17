<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Eldemy') - Eldemy</title>
<meta name="description" content="@yield('meta_description', 'Eldemy - Platform kursus online terbaik di Indonesia')">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--primary:#1A1A1A;--secondary:#FF8E8E;--tertiary:#F0B429;--bg:#F3EDE4;--card:#FFFDF9;--text:#3a3a3a;--muted:#888;--border:rgba(0,0,0,.07)}
body{font-family:'Manrope',sans-serif;background:var(--bg);color:var(--text);line-height:1.7;min-height:100vh}
h1,h2,h3,h4,h5,h6{font-family:'Plus Jakarta Sans',sans-serif}
a{text-decoration:none;color:inherit}

/* Navbar */
.navbar{position:sticky;top:0;z-index:100;background:rgba(243,237,228,.92);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);padding:14px 0}
.nav-inner{max-width:1100px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;font-size:1.5rem;color:var(--primary)}
.brand span{color:var(--secondary)}
.nav-links{display:flex;gap:4px;align-items:center}
.nav-links a{color:var(--muted);font-size:.85rem;font-weight:600;padding:8px 14px;border-radius:10px;transition:.2s}
.nav-links a:hover,.nav-links a.active{color:var(--primary);background:rgba(0,0,0,.05)}
.btn-nav{background:var(--primary)!important;color:#fff!important;border-radius:10px!important}
.mobile-menu{display:none;background:none;border:none;color:var(--primary);font-size:1.3rem;cursor:pointer}

/* Page Content */
.page-container{max-width:880px;margin:0 auto;padding:48px 24px 80px}
.page-header{margin-bottom:44px;text-align:center}
.page-header .badge{display:inline-block;background:rgba(255,142,142,.1);color:var(--secondary);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.7rem;letter-spacing:1.5px;text-transform:uppercase;padding:6px 16px;border-radius:100px;border:1px solid rgba(255,142,142,.15);margin-bottom:16px}
.page-header h1{font-size:2.3rem;font-weight:800;color:var(--primary);letter-spacing:-.8px;margin-bottom:10px}
.page-header p{color:var(--muted);font-size:.95rem}

/* Content Cards */
.content-card{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:32px 36px;margin-bottom:20px}
.content-card h2{font-size:1.15rem;font-weight:700;color:var(--primary);margin-bottom:14px;display:flex;align-items:center;gap:10px}
.content-card h2 .icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0}
.icon-pink{background:rgba(255,142,142,.12);color:var(--secondary)}
.icon-purple{background:rgba(26,26,26,.08);color:var(--primary)}
.icon-teal{background:rgba(240,180,41,.12);color:var(--tertiary)}
.icon-amber{background:rgba(240,180,41,.12);color:var(--tertiary)}
.icon-green{background:rgba(76,175,80,.1);color:#4CAF50}
.content-card p,.content-card li{color:var(--text);font-size:.92rem;line-height:1.8}
.content-card ul,.content-card ol{padding-left:20px;margin:10px 0}
.content-card li{margin-bottom:6px}
.content-card li::marker{color:var(--secondary)}
.content-card a{color:var(--secondary);font-weight:600}
.content-card a:hover{text-decoration:underline}
.content-card strong{color:var(--primary)}

/* FAQ */
.faq-item{border:1px solid var(--border);border-radius:12px;margin-bottom:10px;overflow:hidden;transition:.25s}
.faq-item:hover{border-color:rgba(255,142,142,.25)}
.faq-question{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;cursor:pointer;background:rgba(0,0,0,.01);transition:.2s;user-select:none}
.faq-question:hover{background:rgba(0,0,0,.025)}
.faq-question h3{font-size:.92rem;font-weight:600;color:var(--primary);margin:0}
.faq-question .arrow{color:var(--secondary);font-size:.8rem;transition:transform .3s}
.faq-item.open .faq-question .arrow{transform:rotate(180deg)}
.faq-answer{max-height:0;overflow:hidden;transition:max-height .35s ease}
.faq-item.open .faq-answer{max-height:500px}
.faq-answer-inner{padding:0 20px 16px;color:var(--text);font-size:.88rem;line-height:1.8}
.faq-answer-inner a{color:var(--secondary);font-weight:600}

/* Footer */
.footer{background:var(--primary);padding:40px 24px;text-align:center;color:#999}
.footer-inner{max-width:1100px;margin:0 auto}
.footer-brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;font-size:1.3rem;color:#fff;margin-bottom:8px}
.footer-brand span{color:var(--secondary)}
.footer-text{color:#666;font-size:.82rem;margin-bottom:14px}
.footer-links{display:flex;gap:20px;justify-content:center;flex-wrap:wrap}
.footer-links a{color:#999;font-size:.82rem;font-weight:500;transition:.2s}
.footer-links a:hover{color:var(--secondary)}

@media(max-width:768px){
.page-header h1{font-size:1.7rem}
.content-card{padding:24px 20px}
.nav-links a{padding:8px 10px;font-size:.8rem}
}
@media(max-width:480px){
.nav-links{display:none;position:absolute;top:58px;left:0;right:0;background:rgba(243,237,228,.98);flex-direction:column;padding:16px;border-bottom:1px solid var(--border)}
.nav-links.open{display:flex}
.mobile-menu{display:block}
}
</style>
@stack('styles')
</head>
<body>

<nav class="navbar"><div class="nav-inner">
<a href="/" class="brand">EL<span>DEMY</span></a>
<div class="nav-links">
<a href="/terms" class="{{ request()->is('terms') ? 'active' : '' }}">Syarat & Ketentuan</a>
<a href="/refund-policy" class="{{ request()->is('refund-policy') ? 'active' : '' }}">Refund Policy</a>
<a href="/faq" class="{{ request()->is('faq') ? 'active' : '' }}">FAQ</a>
<a href="/login" class="btn-nav">Masuk Instruktur</a>
</div>
<button class="mobile-menu" onclick="document.querySelector('.nav-links').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
</div></nav>

<div class="page-container">
@yield('content')
</div>

<footer class="footer"><div class="footer-inner">
<div class="footer-brand">EL<span>DEMY</span></div>
<p class="footer-text">Platform kursus online terbaik untuk pengembangan keterampilan digital Anda.</p>
<div class="footer-links">
<a href="/">Beranda</a>
<a href="/terms">Syarat & Ketentuan</a>
<a href="/refund-policy">Kebijakan Refund</a>
<a href="/faq">FAQ</a>
</div>
<div style="margin-top:20px;font-size:.82rem;color:#999;line-height:1.7">
<p><i class="fa-solid fa-envelope" style="margin-right:4px;color:var(--secondary)"></i> eldemycourses@gmail.com &nbsp;|&nbsp; <i class="fa-solid fa-phone" style="margin-right:4px;color:var(--secondary)"></i> 0852-8277-7446</p>
<p style="margin-top:4px"><i class="fa-solid fa-location-dot" style="margin-right:4px;color:var(--secondary)"></i> Perum Mustika Prakasa, Kel. Cibalongsari, Kec. Klari, Kab. Karawang, Jawa Barat 41371</p>
</div>
<p class="footer-text" style="margin-top:16px">&copy; {{ date('Y') }} Eldemy. Pembayaran diproses oleh <strong style="color:var(--secondary)">iPaymu</strong> — Payment Gateway Resmi Indonesia <i class="fa-solid fa-shield-halved" style="color:#4CAF50"></i></p>
</div></footer>

@stack('scripts')
</body>
</html>

