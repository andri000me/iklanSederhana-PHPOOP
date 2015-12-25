<?php 
// cegah pengaksesan langsung dari browser
if (eregi('admin_var.php', $_SERVER['PHP_SELF'])) {
	header('Location: ../index.php');
	exit;
}

include ('../inc/fungsi.php'); // panggil file fungsi.php

$tgl = "Hari ini: ".show_tgl(); // tanggal sekarang

$login = "<p>Anda login sebagai: <b>$_SESSION[admin]</b></p>\n"; // id member

// variabel untuk menu admin
$admin_menu = "
<table border='0' cellpadding='4' width='100%'>
<tr bgcolor='#7cb500'>
	<td class='putih'>Admin Menu</td></tr>
<tr><td>
	<p><a href='iklan.php'>Manaje Iklan</a></p>
	<p><a href='member.php'>Manaje Member</a></p>
	<p><a href='berita.php'>Manaje Berita</a></p>
	<p><a href='email.php'>Email Member</a></p>
	<p><a href='index.php?proses=logout'>Logout</a></p>
</td></tr>
</table>\n";

// variabel untuk menampilkan pesan belum login
$not_login = "<p>Anda belum login. <a href='index.php'>Login</a> dulu</p>\n";

// variabel untuk menampilkan teks berjalan
$anim_teks = "
<marquee scrolldelay='50' scrollamount='2'><<< Control panel iklanUNPAM</marquee>\n";

// variabel untuk menampilkan link kembali
$kembali = "<br><a href='javascript: history.back();'><< kembali</a>\n";
?>