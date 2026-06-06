@extends('layouts.public')

@section('title', 'Kebijakan Pengembalian Dana')
@section('meta_description', 'Kebijakan refund Eldemy')

@section('content')

<div class="page-header">
    <div class="badge">Kebijakan</div>
    <h1>Kebijakan Pengembalian Dana</h1>
    <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
</div>

<div class="content-card">
    <h2><span class="icon icon-pink"><i class="fa-solid fa-hand-holding-dollar"></i></span> 1. Ketentuan Umum</h2>
    <p>Eldemy berkomitmen memberikan pengalaman belajar terbaik. Pembayaran diproses melalui <strong>iPaymu</strong>, payment gateway resmi terdaftar Bank Indonesia. Pengembalian dana dikembalikan melalui metode pembayaran yang sama.</p>
</div>

<div class="content-card">
    <h2><span class="icon icon-green"><i class="fa-solid fa-circle-check"></i></span> 2. Kondisi Refund Disetujui</h2>
    <ul>
        <li><strong>Pembayaran ganda:</strong> Dikenakan biaya lebih dari satu kali akibat kesalahan teknis.</li>
        <li><strong>Kesalahan teknis:</strong> Pembayaran berhasil namun akses tidak diberikan setelah 24 jam.</li>
        <li><strong>Konten tidak sesuai:</strong> Materi tidak sesuai deskripsi (lapor dalam 3 hari).</li>
        <li><strong>Kursus dihapus:</strong> Instruktur menghapus kursus sebelum siswa selesai 50% materi.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-amber"><i class="fa-solid fa-triangle-exclamation"></i></span> 3. Kondisi Refund Ditolak</h2>
    <ul>
        <li>Pengguna telah menyelesaikan lebih dari <strong>50% materi</strong>.</li>
        <li>Pengguna telah mengunduh modul/materi kursus.</li>
        <li>Pengguna telah menerima <strong>sertifikat kelulusan</strong>.</li>
        <li>Permintaan diajukan setelah <strong>7 hari kalender</strong> dari tanggal pembelian.</li>
        <li>Ketidakpuasan subjektif atau perubahan situasi pribadi.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-purple"><i class="fa-solid fa-list-check"></i></span> 4. Prosedur Pengajuan</h2>
    <ol>
        <li>Kirim email ke <a href="mailto:mewarrahman@gmail.com">mewarrahman&#64;gmail.com</a> dengan subjek "Pengajuan Refund - [Nama Kursus]".</li>
        <li>Sertakan: nama lengkap, email terdaftar, nama kursus, Order ID, alasan detail, dan bukti pendukung.</li>
        <li>Tim kami akan meninjau dalam <strong>3-5 hari kerja</strong>.</li>
        <li>Keputusan dikirim via email. Jika disetujui, dana dikembalikan dalam <strong>7-14 hari kerja</strong>.</li>
    </ol>
</div>

<div class="content-card">
    <h2><span class="icon icon-teal"><i class="fa-solid fa-money-bill-transfer"></i></span> 5. Jumlah Pengembalian</h2>
    <ul>
        <li><strong>Pembayaran ganda / kesalahan teknis:</strong> 100% dari nominal.</li>
        <li><strong>Konten tidak sesuai:</strong> 100% (jika diajukan dalam 3 hari).</li>
        <li>Biaya administrasi payment gateway mungkin tidak dapat dikembalikan.</li>
    </ul>
</div>

<div class="content-card">
    <h2><span class="icon icon-green"><i class="fa-solid fa-envelope"></i></span> 6. Kontak</h2>
    <p>Email: <a href="mailto:mewarrahman@gmail.com">mewarrahman&#64;gmail.com</a> | Website: <a href="https://eldemy.eltaimayu.my.id">eldemy.eltaimayu.my.id</a></p>
</div>

@endsection
