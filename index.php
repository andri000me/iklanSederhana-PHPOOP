<?php 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');

$judul = "<h2>Selamat Datang di iklan Unpam</h2>\n";
$utama = "<p>Website Iklan UNPAM merupakan website penyedia layanan iklan \n"
."yang memberikan layanan terbaik bagi membernya. Kami sama sekali tidak memungut \n"
."sepeserpun dari anda dalam pemasangan iklan. Artinya anda dapat mamasang iklan di website \n"
."ini secara gratis!. Kami juga menyediakan sarana iklan lewat email. Jadi anda dapat \n"
."mengirim email ke seluruh member. Namun untuk email anda hanya dapat mengirimnya satu \n"
."kali dalam seminggu. Ini untuk menghindari terjadinya SPAM.</p>\n"
."<p>Anda dapat memilih kategori yang menurut anda cocok dengan produk \n"
."yang ingin anda pasarkan. Kategori-kategori tersebut antara lain \n"
."<ul>\n<li>Internet</li>\n<li>Komputer</li>\n<li>Elektronik</li>\n<li>Lowongan</li>\n"
."<li>Otomotif</li><li>Properti</li>\n<li>Lainnya</li>\n</ul>\n</p>\n"
."<p>Untuk melihat iklan yang ada di website iklanUNPAM anda tidak perlu menjadi \n"
."kami. Akan tetapi juka anda ingin memasang iklan, maka anda harus registrasi dulu menjadi \n"
."member kami. Untuk menjadi member sama sekali gratis!.</p>"
."Klik link dibawah ini untuk registrasi<br><br>\n"
."<a href='daftar.php'>Registrasi</a>\n";

// panggil class skin
$skin = new skin; // buat objek skin
$skin->ganti_skin('template/skin_utama.php');  // tentukan file template
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{MENU}', $menu);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $utama);
$skin->ganti_tag('{SISI1}', $iklan_sisi);
$skin->ganti_tag('{SISI2}', $daftar_berita);
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();
?>