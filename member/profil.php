<?php 
error_reporting(0);
session_start();

include ('../inc/class_skin.php');
include ('../template/member_var.php');

$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses);

konek_db(); // koneksikan ke MySQL server

// cek user apakah sudah login atau belum
if (!cek_session('member'))
	$profil = $not_login;
else
{
	// handel setiap proses dengan switch dan case
	switch ($proses)
	{
		case 'form':
		// lakukan query untuk mendapatkan profil dari user tersebut
		$user = $_SESSION['member'];
		$hasil = mysql_query("SELECT * FROM member WHERE username='$user'");
		$data = mysql_fetch_array($hasil); // pecah menjadi array

		$judul = "<h2>Update Profil</h2>\n";
		$profil = "<p>Untuk mengupdate Klik tombol UPDATE. Pastikan semua field terisi.</p>\n"
		."<form action='profil.php?proses=proses_form' method='post'>\n"
		."<table border='0' cellpadding='4'>\n"
		."<tr bgcolor='#7cb500' align='center'>\n"
		."	<td colspan='2' class='putih'>Form Update Profil</td></tr>\n"
		."<tr> \n<td>Username: </td><td>$data[username]</td></tr>\n"
		."<tr> \n<td>Password: </td>\n"
		."	<td><input type='password' name='pass1' maxlength='16'></td></tr>\n"
		."<tr> \n<td>Ulangi: </td>\n"
		."	<td><input type='password' name='pass2' maxlength='16'></td></tr>\n"
		."<tr> \n<td>Nama Lengkap: </td>\n"
		."	<td><input type='text' name='nama' value='".stripslashes($data['nama'])."'></td></tr>\n"
		."<tr> \n<td>Email: </td>\n"
		."	<td><input type='text' name='email' value='".stripslashes($data['email'])."'></td></tr>\n"
		."<tr> \n<td>Alamat: </td>\n"
		."	<td><input type='text' name='alamat' value='".stripslashes($data['alamat'])."' size='50'></td></tr>\n"
		."<tr> \n<td>Kota: </td>\n"
		."	<td><input type='text' name='kota' value='".stripslashes($data['kota'])."'></td></tr>\n"
		."<tr> \n<td>Telp./HP: </td>\n"
		."	<td><input type='text' name='telpon' value='".stripslashes($data['telpon'])."'></td></tr>\n"
		."<tr><td></td><td><input type='submit' value='UPDATE'></td></tr>\n"
		."<tr bgcolor='#7cb500' height='20'>\n"
		."	<td colspan='2'></td></tr>\n"
		."<input type='hidden' name='username' value='$data[username]'>\n"
		."</table>\n</form>\n";
		break;

		case 'proses_form':
		// ambil data yang dipost
		$username = $_POST['username'];
		$pass1 = filter_str($_POST['pass1']);
		$pass2 = filter_str($_POST['pass2']);
		// tambahkan addslashes()
		$nama = addslashes($_POST['nama']);
		$email = addslashes($_POST['email']);
		$alamat = addslashes($_POST['alamat']);
		$kota = addslashes($_POST['kota']);
		$telpon = addslashes($_POST['telpon']);

		// buat variabel pesan_error
		$pesan_error = '';

		// cek semua field
		if (!cek_field($_POST))
			$pesan_error = "Error: Masih ada field yang kosong<br>\n";
		if (!cek_email($email))
			$pesan_error .= "Error: Email tidak valid.<br>\n";
		if ($pass1 != $pass2)
			$pesan_error .= "Error: Password tidak sama.<br>\n";

		$pass1 = balik_md5($pass1); // enkripsi password

		// cek isi dari pesan error jika tidak kosong maka ada error
		if ($pesan_error != '')
			$profil = $pesan_error.$kembali;
		else
		{
			// masukkan ke database
			$hasil = mysql_query("UPDATE member SET password='$pass1', email='$email', nama='$nama',
				alamat='$alamat', kota='$kota', telpon='$telpon' WHERE username='$username'");
			// cek status
			if (!$hasil)
				$profil = "Profil gagal diupdate. <br>\n $kembali";
			else
				$profil = "Profil berhasil diupdate. <br>\n $kembali";
		}
		break;
	} // akhir dari switch
} // akhir dari else
mysql_close(); // tutup koneksi MySQL Server

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $profil);
$skin->ganti_tag('{MENU}', $mem_menu);
$skin->ganti_tag('{SISI1}', $iklanku);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>