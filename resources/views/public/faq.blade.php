@extends('layouts.public')

@section('title', 'FAQ')
@section('meta_description', 'Pertanyaan yang sering diajukan tentang Eldemy')

@section('content')

<div class="page-header">
    <div class="badge">Bantuan</div>
    <h1>Pertanyaan Umum (FAQ)</h1>
    <p>Temukan jawaban untuk pertanyaan yang sering ditanyakan</p>
</div>

<div class="content-card">
    <h2><span class="icon icon-pink"><i class="fa-solid fa-circle-info"></i></span> Tentang Eldemy</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apa itu Eldemy?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Eldemy adalah platform kursus online (e-learning marketplace) yang menghubungkan instruktur dengan siswa. Instruktur dapat membuat dan menjual kursus berupa video, modul PDF, dan kuis interaktif, sementara siswa dapat membeli dan mengakses kursus kapan saja.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah Eldemy aman digunakan?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Ya, Eldemy menggunakan enkripsi SSL untuk melindungi data pengguna. Pembayaran diproses melalui iPaymu, payment gateway resmi yang terdaftar di Bank Indonesia dan bersertifikasi PCI DSS.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah Eldemy tersedia di mobile?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Ya, Eldemy tersedia sebagai aplikasi mobile (Android) yang dapat diunduh. Siswa dapat mengakses kursus, menonton video, membaca modul, dan mengerjakan kuis langsung dari perangkat mobile.
        </div></div>
    </div>
</div>

<div class="content-card">
    <h2><span class="icon icon-purple"><i class="fa-solid fa-credit-card"></i></span> Pembayaran</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Metode pembayaran apa saja yang tersedia?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Eldemy menggunakan iPaymu sebagai payment gateway. Metode yang tersedia meliputi Transfer Bank, Virtual Account (BCA, BNI, BRI, Mandiri, dll), QRIS, dan metode lainnya yang disediakan iPaymu.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Bagaimana cara membeli kursus?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Pilih kursus yang diinginkan, klik tombol "Beli Kursus", lalu Anda akan diarahkan ke halaman pembayaran iPaymu. Pilih metode pembayaran, selesaikan pembayaran, dan akses kursus akan otomatis diberikan setelah pembayaran dikonfirmasi.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah pembayaran saya aman?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Ya, seluruh transaksi diproses melalui iPaymu yang menggunakan enkripsi SSL 256-bit dan telah bersertifikasi PCI DSS. Eldemy tidak menyimpan data kartu kredit/debit Anda secara langsung.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Berapa lama akses kursus setelah pembayaran?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Akses kursus diberikan secara otomatis setelah pembayaran dikonfirmasi oleh sistem. Untuk Transfer Bank manual, konfirmasi dapat memakan waktu 1-24 jam. Untuk Virtual Account dan QRIS, akses biasanya diberikan dalam hitungan menit.
        </div></div>
    </div>
</div>

<div class="content-card">
    <h2><span class="icon icon-teal"><i class="fa-solid fa-rotate-left"></i></span> Pengembalian Dana</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah bisa mengajukan refund?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Ya, Anda dapat mengajukan refund dalam kondisi tertentu seperti pembayaran ganda, kesalahan teknis, atau konten tidak sesuai deskripsi. Silakan baca halaman <a href="/refund-policy">Kebijakan Pengembalian Dana</a> untuk detail lengkap.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Berapa lama proses refund?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Review pengajuan membutuhkan 3-5 hari kerja. Jika disetujui, pengembalian dana diproses dalam 7-14 hari kerja tergantung metode pembayaran dan bank terkait.
        </div></div>
    </div>
</div>

<div class="content-card">
    <h2><span class="icon icon-amber"><i class="fa-solid fa-graduation-cap"></i></span> Kursus & Sertifikat</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah saya mendapat sertifikat setelah menyelesaikan kursus?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Ya, setelah Anda menyelesaikan seluruh materi dan lulus kuis dengan nilai minimum yang ditentukan, sertifikat digital akan otomatis diterbitkan dan dapat diunduh dalam format PDF.
        </div></div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <h3>Apakah akses kursus memiliki batas waktu?</h3>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </div>
        <div class="faq-answer"><div class="faq-answer-inner">
            Tidak, setelah membeli kursus, Anda mendapatkan akses selamanya (lifetime access). Anda dapat mengulang materi kapan saja tanpa biaya tambahan.
        </div></div>
    </div>
</div>

<div class="content-card">
    <h2><span class="icon icon-green"><i class="fa-solid fa-headset"></i></span> Butuh Bantuan?</h2>
    <p>Jika pertanyaan Anda belum terjawab, silakan hubungi tim dukungan kami:</p>
    <ul>
        <li><strong>Email:</strong> <a href="mailto:eldemycourses@gmail.com">eldemycourses&#64;gmail.com</a></li>
        <li><strong>Website:</strong> <a href="https://eldemy.eltaimayu.my.id">eldemy.eltaimayu.my.id</a></li>
    </ul>
</div>

@endsection

@push('scripts')
<script>
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const wasOpen = item.classList.contains('open');
    item.closest('.content-card').querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
}
</script>
@endpush
