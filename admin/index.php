<?php 
session_start();
error_reporting(0);

// panggil file-file yang diperlukan
include ('../inc/class_skin.php');
include ('../inc/class_waktu.php');
include ('../template/admin_var.php');

// dapatkan data dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'login';

$proses = filter_str($proses); // filter string

konek_db(); // koneksikan ke database
// handel setiap proses dengan switch dan case
switch ($proses)
{
	case 'login':
	// cek apakah admin sudah login atau belum
	if (!cek_session('admin')) {
		$judul = "<h2 align=center>iklanUNPAM - login</h2>\n";
		$admin = "
		<form action='index.php?proses=proses_login' method='post'>
		<table align=center border='0' cellpadding='4'>
			<tr bgcolor='#7cb500' align='center'>
				<td colspan='2' class='putih'>Admin Login</td></tr>
			<tr><td>Username: </td><td><input type='text' name='username'></td></tr>
			<tr><td>Password: </td><td><input type='password' name='password'></td></tr>
			<tr><td></td><td><input type='submit' value='L O G I N'></td></tr>
			<tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>
		</table>
		</form>\n\n";

		// kosongkan nilai variabel $admin_menu
		$admin_menu = '';
		$login = '';
	}
	else
	{
		$admin = "
		<h2>Selamat datang di control panel - iklanUNPAM</h2>
		<p>Silahkan pilih menu disamping untuk memanaje website iklanUNPAM</p>\n";

		// panggil class waktu
		$waktu = new waktu;
		$waktu->set_date();
		$waktu->set_mode(0); // mode pengurangan
		$waktu_itu = $waktu->set_tgl(1, $lama_iklan); // $lama_iklan di konfig.php

		// hapus iklan yang dippost lebih dari $lama_iklan
		$hasil = mysql_query("DELETE FROM tb_iklan WHERE timestamp<$waktu_itu");
		// cek status
		if (!$hasil)
			$admin .= "<p>Error: gagal menghapus iklan pada database.<br>\n$kembali</p>\n";
	}
	break;

	case 'proses_login':

	$username = filter_str($_POST['username']);
	$password = filter_str($_POST['password']);

	$password = balik_md5($password); // enkripsi password

	// kosongkan variabel $admin_menu
	$admin_menu = '';

	// cek kecocokan data dengan fungsi login
	if (!login_admin('member', $username, $password))
		$admin = "<p>Username atau password salah.<br>\n$kembali</p>";
	else
	{
		$admin = "<p>Login berhasil. klik <a href='index.php'>disini</a>"
		." untuk masuk admin area</p>\n";
		// buatkan session karena berhasil login
		$_SESSION['admin'] = $username;
	}
	break;

	case 'logout':

	if (!logout('admin')) {
		$admin_menu = ''; // kosongkan menu
		$admin = "<p>Tidak bisa logout. <a href='index.php'>Login</a> dulu.</p>\n";
	}
	else
	{
		$admin_menu = ''; // kosongkan menu
		$admin = "<p>Anda telah logout dari sistem. <a href='index.php'>Login</a>"
		." kembali.</p>\n";
	}
	break;

	case '__add_admin_to_database__':
	/*
	case ini berfungsi untuk memasukan account administrator ke database
	ini dikarenakan fungsi yang kita gunakan login adalah balik_md5()
	dan untuk menghasilkan string chiper ini hanya bisa dilakukan lewat
	script PHP bukan pada MySQL

	untuk memanggil fungsi ini harus diketikan langsung pada address bar
	index.php?proses=__add_admin_to_database__ lalu ENTER

	untuk mencegah eksploitasi sistem, kita tidak menyediakan form untuk
	menambahkan account admin ke database melainkan langsung melakukan query
	*/

	// kosongkan nilai $admin_menu
	$admin_menu = '';
	// tentukan username dan password yang diinginkan
	$username = 'admin';
	$password = balik_md5('__pas123__');

	// lakukan query INSERT untuk memasukan acount ke database
	$hasil = mysql_query("INSERT INTO admin VALUES('$username', '$password')");
	if (!$hasil)
		$admin = "Error: Gagal memasukan ke database. Mungkin account "
	."sudah dimasukan. <br>\n$kembali";
	else
		$admin = "Acount untuk administrator berhasil dimasukan ke database. <br>"
	."<a href='index.php'>Login</a>\n";
	break;

} // akhir dari switch
mysql_close(); // tutup koneksi

$skin = new skin; // buat object skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $admin);
$skin->ganti_tag('{MENU}', $anim_teks);
$skin->ganti_tag('{SISI1}', $admin_menu);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>