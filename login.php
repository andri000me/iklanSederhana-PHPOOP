<?php 
session_start(); // karena berhubungan dengan session 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');

// dapatkan data proses dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses);

// handel setiap proses dengan case dan switch
switch ($proses)
{
	case 'form':
	$login = "<h2 align=center>Member Login</h2>\n"
	."<center>Bagi member silahkan login dibawah ini.</center>\n"
	."<form action='login.php?proses=proses_form' method='post'>\n"
	."<table align=center border='1' cellpadding='4'>\n"
	." <tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>\n"
	."<tr>\n <td>Username: </td>\n"
	." <td><input type='text' name='username'></td>\n </tr>\n"
	."<tr>\n <td>Password: </td>\n"
	." <td><input type='password' name='password'></td>\n </tr>"
	."<tr>\n <td></td>\n"
	." <td><input type='submit' value='LOGIN'></td>\n </tr>\n"
	."<tr bgcolor='#7cb500' height='20'><td colspan='2'>
	<p>Lupa Password? Klik <a href='lupa_pass.php'>disini</a>.</p>
	<p>Belum Menjadi Member? <a href='daftar.php'>Daftar</a> sekarang.</p></td></tr>\n"
	."</table>\n</form>\n";
	break;

	case 'proses_form':

	// ambil data yang dipost sekaligus filter
	$username = filter_str($_POST['username']);
	$password = filter_str($_POST['password']);
	// enkripsi password
	$password = balik_md5($password);

	konek_db(); // koneksikan ke MySQL server

	// gunakan fungsi login untuk mencocokan data
	if (!login('member', $username, $password))
		$login = "Username atau password salah.<br>\n $kembali";
	else
	{
		$_SESSION['member'] = $username; // buat session member
		$login = "Login berhasil. Klik <a href='member/index.php'>disini</a> "
		."untuk masuk ke member area.";
	}

	mysql_close();
}

// panggil class skin
$skin = new skin; // buat objek skin
$skin->ganti_skin('template/skin_utama.php'); // tentukan file template
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{MENU}', $menu);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $login);
$skin->ganti_tag('{SISI1}', $iklan_sisi);
$skin->ganti_tag('{SISI2}', $daftar_berita);
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();
?>