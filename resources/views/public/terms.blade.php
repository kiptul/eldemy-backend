@extends('layouts.public')

@section('title', 'Syarat & Ketentuan')
@section('meta_description', 'Syarat dan Ketentuan penggunaan platform Eldemy - Platform kursus online Indonesia')

@section('content')

<div class="page-header">
    <div class="badge">Legal</div>
    <h1>Syarat & Ketentuan</h1>
    <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
</div>

<div class="content-card">
    <h2><span class="icon icon-pink"><i class="fa-solid fa-gavel"></i></span> 1. Ketentuan Umum</h2>
    <p>Dengan menggunakan layanan <strong>Eldemy</strong> (selanjutnya disebut "Platform"), Anda menyetujui dan terikat oleh syarat dan ketentuan berikut. Platform ini dioperasikan dan dikelola sebagai marketplace kursus online yang menghubungkan instruktur (penyedia konten) dengan siswa (pengguna).</p>
    <ul>
        <li>Platform ini menyediakan layanan jual-beli kursus online berbasis digital.</li>
        <li>Pengguna wajib berusia minimal 17 tahun atau memiliki persetujuan dari orang tua/wali.</li>
        <li>Setiap pengguna bertanggung jawab penuh atas keamanan akun dan kata sandi mereka.</li>
        <li>Eldemy berhak mengubah syarat dan ketentuan ini sewaktu-waktu dengan pemberitahuan melalui platform.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-purple"><i class="fa-solid fa-user-shield"></i></span> 2. Akun Pengguna</h2>
    <ul>
        <li>Setiap pengguna harus mendaftar menggunakan alamat email yang valid atau melalui akun Google.</li>
        <li>Informasi yang diberikan saat pendaftaran harus akurat dan terbaru.</li>
        <li>Pengguna dilarang membuat lebih dari satu akun atau menggunakan identitas palsu.</li>
        <li>Eldemy berhak menangguhkan atau menonaktifkan akun yang terindikasi melakukan pelanggaran.</li>
        <li>Pengguna bertanggung jawab atas semua aktivitas yang terjadi di akun mereka.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-teal"><i class="fa-solid fa-shopping-cart"></i></span> 3. Pembelian & Pembayaran</h2>
    <ul>
        <li>Semua harga kursus yang ditampilkan adalah dalam mata uang <strong>Rupiah (IDR)</strong>.</li>
        <li>Pembayaran diproses melalui <strong>iPaymu</strong>, payment gateway yang telah terdaftar dan diawasi oleh Bank Indonesia.</li>
        <li>Metode pembayaran yang tersedia meliputi: Transfer Bank, Virtual Account, QRIS, dan metode lain yang disediakan oleh iPaymu.</li>
        <li>Setelah pembayaran berhasil dikonfirmasi, pengguna akan mendapatkan akses permanen ke kursus yang dibeli.</li>
        <li>Bukti transaksi akan dikirimkan melalui email dan dapat dilihat di halaman riwayat pembelian.</li>
        <li>Eldemy tidak menyimpan informasi kartu kredit/debit pengguna secara langsung.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-amber"><i class="fa-solid fa-book-open"></i></span> 4. Konten & Hak Kekayaan Intelektual</h2>
    <ul>
        <li>Seluruh konten kursus (video, modul PDF, kuis) merupakan hak kekayaan intelektual masing-masing instruktur dan dilindungi oleh undang-undang hak cipta.</li>
        <li>Pengguna <strong>dilarang keras</strong> mendistribusikan ulang, menyalin, merekam, atau membagikan konten kursus kepada pihak ketiga tanpa izin tertulis.</li>
        <li>Pelanggaran hak cipta dapat mengakibatkan penangguhan akun dan tuntutan hukum.</li>
        <li>Instruktur menjamin bahwa konten yang diunggah adalah karya asli atau telah mendapatkan lisensi yang sah.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-green"><i class="fa-solid fa-shield-halved"></i></span> 5. Privasi & Keamanan Data</h2>
    <ul>
        <li>Eldemy berkomitmen untuk melindungi data pribadi pengguna sesuai dengan peraturan perundang-undangan yang berlaku.</li>
        <li>Data yang dikumpulkan meliputi: nama, email, foto profil, dan riwayat transaksi.</li>
        <li>Data pengguna <strong>tidak akan dijual</strong> atau dibagikan kepada pihak ketiga untuk kepentingan komersial.</li>
        <li>Penggunaan cookies dan teknologi pelacakan untuk meningkatkan pengalaman pengguna.</li>
        <li>Pengguna dapat mengajukan penghapusan data dengan menghubungi tim dukungan kami.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-pink"><i class="fa-solid fa-ban"></i></span> 6. Larangan</h2>
    <p>Pengguna <strong>dilarang</strong> melakukan hal-hal berikut:</p>
    <ul>
        <li>Menggunakan platform untuk tujuan ilegal atau melanggar hukum.</li>
        <li>Mengunggah konten yang mengandung unsur SARA, pornografi, atau kekerasan.</li>
        <li>Melakukan serangan siber, spam, atau aktivitas yang mengganggu layanan.</li>
        <li>Menyalahgunakan fitur pembayaran atau melakukan transaksi curang.</li>
        <li>Menggunakan bot atau scraper untuk mengakses konten secara otomatis.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-purple"><i class="fa-solid fa-scale-balanced"></i></span> 7. Batasan Tanggung Jawab</h2>
    <ul>
        <li>Eldemy berperan sebagai perantara (marketplace) antara instruktur dan siswa.</li>
        <li>Kualitas konten kursus sepenuhnya menjadi tanggung jawab masing-masing instruktur.</li>
        <li>Eldemy tidak bertanggung jawab atas kerugian tidak langsung yang timbul dari penggunaan platform.</li>
        <li>Platform disediakan "sebagaimana adanya" tanpa jaminan tersirat apapun.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-teal"><i class="fa-solid fa-envelope"></i></span> 8. Kontak</h2>
    <p>Jika Anda memiliki pertanyaan atau kekhawatiran mengenai Syarat & Ketentuan ini, silakan hubungi kami melalui:</p>
    <ul>
        <li><strong>Email:</strong> <a href="mailto:mewarrahman@gmail.com">mewarrahman&#64;gmail.com</a></li>
        <li><strong>Website:</strong> <a href="https://eldemy.eltaimayu.my.id">https://eldemy.eltaimayu.my.id</a></li>
    </ul>
</div>

@endsection
