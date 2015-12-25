<?php 
// cegah pengaksesan langsung dari browser
if (eregi('konfig.php', $_SERVER['PHP_SELF'])) {
	header('Location: ../index.php'); // kembalikan ke halaman utama
	exit;
}
/* KONFIGURASI UNTUK ADMIN */
// ganti jumlahnya sesuai keinginan anda
$a_bph = 2; // berita per halaman
$a_mph = 3; // member per halaman
$a_iph = 2; // iklan per halaman

$max_post = 10; // jumlah iklan maximal yang dipost user
$lama_iklan = 7; // lama hari iklan user akan dihapus
$lama_email = 7; // lama hari user boleh mengirim email kembali

/* KONFIGURASI UNTUK USER/MEMBER */
$u_bph = 2; // berita per halaman
$u_iph = 2;	// iklan per halaman
$u_jbph = 2; // judul berita per halaman
/* AKHIR KONFIGURASI */
?>