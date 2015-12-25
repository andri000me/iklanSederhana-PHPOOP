<?php 
// cegah pengaksesan langsung dari browser
if (eregi('var_utama.php', $_SERVER['PHP_SELF'])) {
	header('Location: ../index.php'); // kembalikan ke halaman utama
	exit;
}

include ('../inc/fungsi.php'); // panggil fungsi.php

$tgl = "Hari ini: ".show_tgl(); // tanggal sekarang

$login = "<p>Anda login sebagai: <b>$_SESSION[member]</b></p>\n"; // id member

$not_login = "<p>Anda belum login. <a href='../login.php'>Login</a> dulu.</p>\n";

$mem_menu = "<a href='index.php'>Home</a> &nbsp :: &nbsp \n"
."<a href='profil.php'>Update Profil</a> &nbsp :: &nbsp \n"
."<a href='pasang.php'>Pasang Iklan</a> &nbsp :: &nbsp \n"
."<a href='email.php'>Email</a> &nbsp :: &nbsp \n"
."<a href='index.php?proses=logout'>Log out</a>\n";

// variabel yang berisi javascript untuk membatasi karakter iklan
// isi = nama textarea untuk iklan
// sisa = nama textbox untuk sisa karakter
$java = "<script language='javascript'>
function batas_kar(form_ini)
{
	var max = 500;
	panj = max - form_ini.isi.value.length;
	if (panj < 0)
		{
			form_ini.isi.value = form_ini.isi.value.substring(0, max);
			panj = 0;
		}
		form_ini.sisa.value = panj;
	}
	</script>";

	// variabel untuk menampilkan daftar iklan yang di-post member tersebut
	konek_db(); // koneksikan ke mySQL Server

	$iklanku = "<table border='0' cellpadding='4'>\n"
	."<tr bgcolor='#003366'>\n"
	."	<td class='putih'>iklanKu</td></tr>\n"
	."<tr><td><p>Klik masing-masing link iklan untuk mengedit.</p></td></tr>\n";
	// lakukan query
	$hasil = mysql_query("SELECT * FROM tb_iklan WHERE username='$_SESSION[member]'");
	// cek baris
	if (mysql_num_rows($hasil) == 0)
		$iklanku .= "<tr><td><p>Anda belum memasang iklan. klik "
	."<a href='pasang.php'>pasang</a> untuk memasang iklan.</p></td></tr>\n";
	else
	{
		// tampilkan hasil dengan looping
		while ($data = mysql_fetch_array($hasil)) {
			$iklanku .= "<tr><td><p><a class='iklan' href='edit.php?kat=$data[kategori]&"
			."id=$data[id_iklan]'>$data[jdl_iklan]</a></p></td></tr>\n";
		}
	}

	$iklanku .= "</table>\n";

	// buat link kembali berguna jika ada error.
	$kembali = "<br><a href='javascript: history.back()'><< Kembali</a>\n";
?>