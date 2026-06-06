<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eldemy - Platform Kursus Online Indonesia</title>
<meta name="description" content="Eldemy adalah platform kursus online terbaik di Indonesia.">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--primary:#1A1A1A;--secondary:#FF8E8E;--tertiary:#F0B429;--bg:#F3EDE4;--card:#FFFDF9;--text:#3a3a3a;--muted:#888;--border:rgba(0,0,0,.07)}
body{font-family:'Manrope',sans-serif;background:var(--bg);color:var(--text);line-height:1.7;overflow-x:hidden}
h1,h2,h3,h4,h5,h6{font-family:'Plus Jakarta Sans',sans-serif}
.container{max-width:1160px;margin:0 auto;padding:0 24px}
a{text-decoration:none;color:inherit}

/* Navbar */
.navbar{position:sticky;top:0;z-index:100;background:rgba(243,237,228,.92);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);padding:14px 0}
.nav-inner{display:flex;align-items:center;justify-content:space-between}
.brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;font-size:1.5rem;color:var(--primary)}
.brand span{color:var(--secondary)}
.nav-links{display:flex;gap:4px;align-items:center}
.nav-links a{color:var(--muted);font-size:.85rem;font-weight:600;padding:8px 14px;border-radius:10px;transition:.2s}
.nav-links a:hover{color:var(--primary);background:rgba(0,0,0,.04)}
.btn-nav{background:var(--primary)!important;color:#fff!important;border-radius:10px!important}
.btn-nav:hover{opacity:.85!important}
.mobile-menu{display:none;background:none;border:none;color:var(--primary);font-size:1.3rem;cursor:pointer}

/* Hero */
.hero{padding:80px 0 60px;text-align:center}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,142,142,.1);color:var(--secondary);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.75rem;letter-spacing:1px;text-transform:uppercase;padding:8px 20px;border-radius:100px;border:1px solid rgba(255,142,142,.2);margin-bottom:24px}
.hero h1{font-size:3.2rem;font-weight:900;color:var(--primary);letter-spacing:-1.5px;line-height:1.15;margin-bottom:18px}
.hero h1 span{color:var(--secondary)}
.hero-sub{color:var(--muted);font-size:1.05rem;max-width:580px;margin:0 auto 32px}
.hero-actions{display:flex;gap:14px;justify-content:center;margin-bottom:48px;flex-wrap:wrap}
.btn-primary-lg{display:inline-flex;align-items:center;gap:8px;background:var(--primary);color:#fff;padding:14px 32px;border-radius:14px;font-weight:700;font-size:.95rem;transition:.2s;box-shadow:0 6px 24px rgba(26,26,26,.15)}
.btn-primary-lg:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(26,26,26,.2)}
.btn-outline-lg{display:inline-flex;align-items:center;gap:8px;background:transparent;color:var(--primary);padding:14px 32px;border-radius:14px;font-weight:700;font-size:.95rem;border:2px solid var(--border);transition:.2s}
.btn-outline-lg:hover{border-color:var(--secondary);color:var(--secondary)}
.hero-stats{display:flex;gap:0;justify-content:center;align-items:center;flex-wrap:wrap;background:var(--card);border-radius:20px;padding:28px 0;border:1px solid var(--border);box-shadow:0 4px 20px rgba(0,0,0,.03);max-width:700px;margin:0 auto}
.stat{text-align:center;padding:0 32px}
.stat span{display:block;font-family:'Plus Jakarta Sans',sans-serif;font-size:1.8rem;font-weight:800;color:var(--primary);margin-bottom:2px}
.stat small{color:var(--muted);font-size:.82rem;font-weight:600}
.stat-divider{width:1px;height:36px;background:var(--border)}

/* Section Header */
.section-header{text-align:center;margin-bottom:44px}
.badge{display:inline-block;background:rgba(255,142,142,.1);color:var(--secondary);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.7rem;letter-spacing:1.5px;text-transform:uppercase;padding:6px 16px;border-radius:100px;border:1px solid rgba(255,142,142,.15);margin-bottom:14px}
.section-header h2{font-size:2rem;font-weight:800;color:var(--primary);letter-spacing:-.8px;margin-bottom:8px}
.section-header p{color:var(--muted);font-size:.95rem}

/* Features */
.features{padding:64px 0}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.feature-card{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:28px 24px;transition:.25s}
.feature-card:hover{transform:translateY(-4px);box-shadow:0 8px 28px rgba(0,0,0,.06);border-color:var(--secondary)}
.feature-icon{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;margin-bottom:16px}
.fi-pink{background:rgba(255,142,142,.12);color:var(--secondary)}
.fi-gold{background:rgba(240,180,41,.12);color:var(--tertiary)}
.fi-dark{background:rgba(26,26,26,.08);color:var(--primary)}
.feature-card h3{font-size:.98rem;font-weight:700;color:var(--primary);margin-bottom:6px}
.feature-card p{color:var(--muted);font-size:.85rem}

/* Course Catalog */
.catalog{padding:64px 0}
.course-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.course-card{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden;transition:.25s}
.course-card:hover{transform:translateY(-4px);box-shadow:0 8px 28px rgba(0,0,0,.06);border-color:var(--secondary)}
.course-thumb{height:170px;background-size:cover;background-position:center;position:relative}
.course-category{position:absolute;top:12px;left:12px;background:rgba(255,255,255,.9);backdrop-filter:blur(6px);color:var(--secondary);font-family:'Plus Jakarta Sans',sans-serif;font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:8px;text-transform:uppercase;letter-spacing:.5px}
.course-body{padding:18px 20px 20px}
.course-body h3{font-size:.95rem;font-weight:700;color:var(--primary);margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.course-desc{color:var(--muted);font-size:.82rem;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.course-meta{display:flex;gap:14px;margin-bottom:14px}
.course-meta span{color:#aaa;font-size:.76rem;display:flex;align-items:center;gap:4px}
.course-meta i{color:var(--secondary);font-size:.68rem}
.course-footer{display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid var(--border)}
.course-price{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.05rem;font-weight:800;color:var(--secondary)}
.course-level{font-size:.7rem;font-weight:700;color:var(--tertiary);background:rgba(240,180,41,.1);padding:4px 10px;border-radius:6px}
.empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
.empty-state i{font-size:2.5rem;margin-bottom:14px;color:#ccc}
.empty-state h3{color:var(--primary);margin-bottom:6px}

/* Payment */
.payment-section{padding:64px 0}
.payment-card{background:var(--card);border:1px solid var(--border);border-radius:24px;padding:48px;display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;box-shadow:0 4px 20px rgba(0,0,0,.03)}
.payment-left h2{font-size:1.6rem;font-weight:800;color:var(--primary);letter-spacing:-.5px;margin-bottom:12px}
.payment-left p{color:var(--muted);font-size:.9rem;margin-bottom:20px}
.payment-left strong{color:var(--secondary)}
.payment-methods{display:flex;gap:10px;flex-wrap:wrap}
.pm{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:10px 16px;font-size:.82rem;font-weight:600;color:var(--primary);display:flex;align-items:center;gap:8px}
.pm i{color:var(--secondary)}
.payment-flow{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:center}
.flow-step{text-align:center}
.flow-num{width:42px;height:42px;border-radius:50%;background:var(--primary);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:.95rem;display:flex;align-items:center;justify-content:center;margin:0 auto 6px}
.flow-step span{font-size:.76rem;font-weight:600;color:var(--text)}
.flow-arrow{color:#ccc;font-size:.85rem}
.flow-note{text-align:center;margin-top:20px;color:#4CAF50;font-size:.83rem;font-weight:600}
.flow-note i{margin-right:5px}

/* Buy Button */
.btn-buy{display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;padding:8px 18px;border-radius:10px;font-size:.82rem;font-weight:700;transition:.2s;white-space:nowrap}
.btn-buy:hover{background:var(--secondary);transform:translateY(-1px)}

/* Footer */
.footer{background:var(--primary);padding:56px 0 28px;color:#ccc}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:36px;margin-bottom:36px}
.footer-brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;font-size:1.3rem;color:#fff;margin-bottom:8px}
.footer-brand span{color:var(--secondary)}
.footer-col p{font-size:.85rem;color:#999}
.footer-col h4{color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;font-weight:700;margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px}
.footer-col a{display:block;color:#999;font-size:.84rem;margin-bottom:7px;transition:.2s}
.footer-col a:hover{color:var(--secondary)}
.footer-col a i{margin-right:6px;width:14px}
.footer-bottom{border-top:1px solid rgba(255,255,255,.1);padding-top:20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px}
.footer-bottom p{color:#666;font-size:.8rem}
.footer-powered{color:#888;font-size:.8rem}
.footer-powered strong{color:var(--secondary)}

@media(max-width:992px){
.features-grid,.course-grid{grid-template-columns:repeat(2,1fr)}
.payment-card{grid-template-columns:1fr;padding:32px}
.footer-grid{grid-template-columns:1fr 1fr}
.hero h1{font-size:2.4rem}
}
@media(max-width:640px){
.features-grid,.course-grid{grid-template-columns:1fr}
.hero h1{font-size:1.9rem}
.hero{padding:50px 0 40px}
.stat{padding:0 16px}
.stat span{font-size:1.3rem}
.nav-links{display:none;position:absolute;top:58px;left:0;right:0;background:rgba(253,251,247,.98);flex-direction:column;padding:16px;border-bottom:1px solid var(--border)}
.nav-links.open{display:flex}
.mobile-menu{display:block}
.footer-grid{grid-template-columns:1fr}
.footer-bottom{flex-direction:column;text-align:center}
.payment-flow{flex-direction:column}
.flow-arrow{transform:rotate(90deg)}
}
</style>
</head>
<body>

<nav class="navbar"><div class="container nav-inner">
<a href="/" class="brand">EL<span>DEMY</span></a>
<div class="nav-links">
<a href="/terms">Syarat & Ketentuan</a>
<a href="/refund-policy">Refund Policy</a>
<a href="/faq">FAQ</a>
<a href="/login" class="btn-nav">Masuk Instruktur</a>
</div>
<button class="mobile-menu" onclick="document.querySelector('.nav-links').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
</div></nav>

<section class="hero"><div class="container">
<div class="hero-badge"><i class="fa-solid fa-graduation-cap"></i> Platform E-Learning Terpercaya</div>
<h1>Belajar Tanpa Batas,<br><span>Raih Masa Depan</span></h1>
<p class="hero-sub">Temukan kursus berkualitas dari instruktur berpengalaman. Video, modul PDF, kuis interaktif, dan sertifikat digital — semua dalam satu platform.</p>
<div class="hero-actions">
<a href="#katalog" class="btn-primary-lg"><i class="fa-solid fa-rocket"></i> Jelajahi Kursus</a>
<a href="/register" class="btn-outline-lg"><i class="fa-solid fa-chalkboard-user"></i> Jadi Instruktur</a>
</div>
<div class="hero-stats">
<div class="stat"><span>{{ $stats['courses'] }}</span><small>Kursus</small></div>
<div class="stat-divider"></div>
<div class="stat"><span>{{ $stats['students'] }}</span><small>Siswa</small></div>
<div class="stat-divider"></div>
<div class="stat"><span>{{ $stats['instructors'] }}</span><small>Instruktur</small></div>
<div class="stat-divider"></div>
<div class="stat"><span>{{ $stats['transactions'] }}</span><small>Transaksi</small></div>
</div>
</div></section>

<section class="features"><div class="container">
<div class="section-header"><div class="badge">Fitur Unggulan</div><h2>Pengalaman Belajar Modern</h2></div>
<div class="features-grid">
<div class="feature-card"><div class="feature-icon fi-pink"><i class="fa-solid fa-play-circle"></i></div><h3>Video Pembelajaran</h3><p>Materi video HD yang dapat diakses kapan saja dari perangkat mobile Anda.</p></div>
<div class="feature-card"><div class="feature-icon fi-gold"><i class="fa-solid fa-file-pdf"></i></div><h3>Modul PDF</h3><p>Unduh modul materi dalam format PDF untuk belajar offline.</p></div>
<div class="feature-card"><div class="feature-icon fi-dark"><i class="fa-solid fa-brain"></i></div><h3>Kuis Interaktif</h3><p>Uji pemahaman Anda dengan kuis interaktif di setiap materi.</p></div>
<div class="feature-card"><div class="feature-icon fi-gold"><i class="fa-solid fa-certificate"></i></div><h3>Sertifikat Digital</h3><p>Dapatkan sertifikat resmi setelah menyelesaikan kursus.</p></div>
<div class="feature-card"><div class="feature-icon fi-pink"><i class="fa-solid fa-mobile-screen"></i></div><h3>Aplikasi Mobile</h3><p>Belajar dari mana saja dengan aplikasi mobile Android.</p></div>
<div class="feature-card"><div class="feature-icon fi-dark"><i class="fa-solid fa-shield-halved"></i></div><h3>Pembayaran Aman</h3><p>Transaksi aman melalui <strong>iPaymu</strong>, payment gateway resmi Bank Indonesia.</p></div>
</div>
</div></section>

<section class="catalog" id="katalog"><div class="container">
<div class="section-header"><div class="badge">Katalog Kursus</div><h2>Kursus Tersedia</h2><p>Pilih kursus sesuai kebutuhan dan mulai belajar hari ini</p></div>
@if(session('error'))
<div style="background:#FFF3F3;border:1px solid #FFD0D0;color:#D32F2F;padding:14px 20px;border-radius:12px;margin-bottom:24px;font-size:.9rem;font-weight:500"><i class="fa-solid fa-circle-exclamation" style="margin-right:8px"></i>{{ session('error') }}</div>
@endif
@if($courses->isEmpty())
<div class="empty-state"><i class="fa-solid fa-book-open"></i><h3>Kursus Segera Hadir</h3><p>Instruktur kami sedang menyiapkan kursus berkualitas.</p></div>
@else
<div class="course-grid">
@foreach($courses as $course)
<div class="course-card">
<div class="course-thumb" style="background-image:url('{{ $course->thumbnail ?? "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&fit=crop" }}')">
<div class="course-category">{{ $course->category ?? 'Umum' }}</div>
</div>
<div class="course-body">
<h3>{{ $course->title }}</h3>
<p class="course-desc">{{ Str::limit($course->description, 80) }}</p>
<div class="course-meta">
<span><i class="fa-solid fa-user-tie"></i> {{ $course->instructor->name ?? 'Instruktur' }}</span>
<span><i class="fa-solid fa-users"></i> {{ $course->purchases_count }} siswa</span>
</div>
<div class="course-footer">
<div class="course-price">Rp {{ number_format($course->base_price, 0, ',', '.') }}</div>
<span style="font-size: 0.75rem; font-weight: 700; color: var(--muted); background: rgba(0,0,0,0.05); padding: 6px 12px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">
    <i class="fa-solid fa-mobile-screen"></i> Beli di App
</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div></section>

<section class="payment-section"><div class="container">
<div class="payment-card">
<div class="payment-left">
<div class="badge">Pembayaran Aman</div>
<h2>Transaksi Terpercaya dengan iPaymu</h2>
<p>Semua pembayaran diproses melalui <strong>iPaymu</strong>, payment gateway resmi terdaftar Bank Indonesia dan bersertifikasi PCI DSS.</p>
<div class="payment-methods">
<div class="pm"><i class="fa-solid fa-building-columns"></i> Transfer Bank</div>
<div class="pm"><i class="fa-solid fa-credit-card"></i> Virtual Account</div>
<div class="pm"><i class="fa-solid fa-qrcode"></i> QRIS</div>
<div class="pm"><i class="fa-solid fa-wallet"></i> E-Wallet</div>
</div>
</div>
<div class="payment-right">
<div class="payment-flow">
<div class="flow-step"><div class="flow-num">1</div><span>Pilih Kursus</span></div>
<div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
<div class="flow-step"><div class="flow-num">2</div><span>Checkout iPaymu</span></div>
<div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
<div class="flow-step"><div class="flow-num">3</div><span>Bayar</span></div>
<div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
<div class="flow-step"><div class="flow-num">4</div><span>Akses Kursus</span></div>
</div>
<p class="flow-note"><i class="fa-solid fa-circle-check"></i> Akses otomatis setelah pembayaran dikonfirmasi</p>
</div>
</div>
</div></section>

<footer class="footer"><div class="container">
<div class="footer-grid">
<div class="footer-col">
<div class="footer-brand">EL<span>DEMY</span></div>
<p>Platform kursus online terbaik untuk pengembangan keterampilan digital Anda.</p>
<div style="margin-top:16px;padding:14px 18px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;display:inline-block">
<div style="font-size:.72rem;color:#999;font-weight:600;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px">Payment Gateway</div>
<div style="font-size:1.1rem;font-weight:800;color:#fff;font-family:'Plus Jakarta Sans',sans-serif">iPaymu <i class="fa-solid fa-shield-halved" style="color:#4CAF50;margin-left:4px"></i></div>
<div style="font-size:.7rem;color:#888;margin-top:2px">Terdaftar & Diawasi Bank Indonesia</div>
</div>
</div>
<div class="footer-col"><h4>Halaman</h4><a href="/">Beranda</a><a href="#katalog">Katalog Kursus</a><a href="/login">Masuk Instruktur</a><a href="/register">Daftar Instruktur</a></div>
<div class="footer-col"><h4>Kebijakan</h4><a href="/terms">Syarat & Ketentuan</a><a href="/refund-policy">Kebijakan Refund</a><a href="/faq">FAQ</a></div>
<div class="footer-col">
<h4>Kontak Kami</h4>
<a href="mailto:mewarrahman@gmail.com"><i class="fa-solid fa-envelope"></i> mewarrahman@gmail.com</a>
<a href="tel:085282777446"><i class="fa-solid fa-phone"></i> 0852-8277-7446</a>
<a href="https://eldemy.eltaimayu.my.id"><i class="fa-solid fa-globe"></i> eldemy.eltaimayu.my.id</a>
<div style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,.08)">
<h4 style="margin-bottom:8px">Alamat</h4>
<p style="font-size:.82rem;color:#999;line-height:1.6">
Perum Mustika Prakasa,<br>
Kel. Cibalongsari, Kec. Klari,<br>
Kabupaten Karawang, Jawa Barat 41371
</p>
</div>
</div>
</div>
<div class="footer-bottom">
<p>&copy; {{ date('Y') }} Eldemy. Hak cipta dilindungi undang-undang.</p>
<div class="footer-powered">Pembayaran diproses oleh <strong>iPaymu</strong> — Payment Gateway Resmi Indonesia <i class="fa-solid fa-shield-halved" style="color:#4CAF50"></i></div>
</div>
</div></footer>

</body>
</html>

