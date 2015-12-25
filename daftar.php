<?php 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');

// dapatkan data proses dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses);

// handel setiap proses dengan switch dan case
switch ($proses)
{
	case 'form':
	$judul = "<h2>Form Registrasi - iklan UNPAM</h2>\n";
	// buat form dan tabel untuk registrasi
	$reg = "<p>Mohon isi semua field dibawah ini untuk mendaftar menjadi \n"
	."member iklanUnpam. Klik DAFTAR untuk melanjutkan proses registrasi.</p>\n"
	."<form action='daftar.php?proses=proses_form' method='post'>\n"
	."<table border='0' cellpadding='4' width='100%'>\n"
	."<tr bgcolor='#003366'>\n"
	." <td class='putih' colspan='2' align='center'>Form Registrasi</td></tr>\n"
	."<tr>\n <td>Username: </td>\n"
	."	<td><input type='text' name='username' maxlength='16'>"
	." max. 16 karakter. </td>\n</tr>\n"
	."<tr>\n <td>Password: </td>\n"
	." <td><input type='password' name='password' maxlength='16'>"
	." max. 16 karakter. \n</td></tr>\n"
	."<tr>\n <td>Nama Lengkap: </td>\n"
	." <td><input type='text' name='nama'></td>\n </tr>\n"
	."<tr>\n <td>Email: </td>\n"
	."	<td><input type='text' name='email'></td>\n </tr>\n"
	."<tr>\n <td>Alamat: </td>\n"
	."	<td><input type='text' name='alamat' size='50'></td>\n</tr>"
	."<tr>\n <td>Kota: </td>\n"
	." <td><input type='text' name='kota'></td>\n </tr>\n"
	."<tr>\n <td>Telp./HP</td>\n"
	."	<td><input type='text' name='telpon'></td>\n </tr>\n"
	."<tr>\n <td></td>\n"
	." <td><input type='submit' value='DAFTAR'></td>\n </tr>"
	."<tr bgcolor='#003366' height='20'><td colspan='2'></td></tr>\n"
	."</table>\n</form>";

	break;

	case 'proses_form':
	// ambil data yang di-post dari form registrasi
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	// tambah addslashes()
	$nama = addslashes($_POST['nama']);
	$alamat = addslashes($_POST['alamat']);
	$kota = addslashes($_POST['kota']);
	$telpon = addslashes($_POST['telpon']);

	$pesan_error = ''; // variabel untuk pesan error

	// cek setiap field
	if (!cek_field($_POST)) 
		$pesan_error = "Error: Masih ada field yang kosong.<br>\n";

	if (!cek_email($email))
		$pesan_error .= "Error: Email tidak valid.<br>\n";

	$format = '[^a-zA-Z0-9_]'; // username dan password hanya alpanumerik dan _
	if (ereg($format, $username) || ereg($format, $password))
		$pesan_error .= "Error: Username attau password hanya boleh terdiri dari "
	."alpabet, numerik dan _.<br>\n";

	$password = balik_md5($password); // enkripsi password

	// cek isi $pesan_error jika tidak kosong maka ada error
	if ($pesan_error != '')
		$reg = $pesan_error.$kembali;
	else
	{
		konek_db(); // koneksikan ke MySQL server
		// lakukan query
		$hasil = mysql_query("INSERT INTO member VALUES('$username', '$password', '$nama',
			'$email', '$alamat', '$kota', '$telpon', 'user')");
		// cek status
		if (!$hasil)
			$reg = "Error: Gagal memasukan data ke database. Kontak admin.<br>\n".$kembali;
		else
			$reg = "proses registrasi sukses. Klik <a href='login.php'>Login</a> untuk "
		."masuk ke member area.";

		// masukkan juga ke tb_email
		$hasil2 = mysql_query("INSERT INTO tb_email VALUES('$username', '$email',0)");
		if (!$hasil2)
			$reg .= "<p>Error: Gagal memasukkan ke tb_email.</p>";

		mysql_close();
	}
	break;

} // akhir dari switch

// panggil class skin
$skin = new skin; // buat objek skin
$skin->ganti_skin('template/skin_utama.php'); // tentukan file template
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{MENU}', $menu);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $reg);
$skin->ganti_tag('{SISI1}', $iklan_sisi);
$skin->ganti_tag('{SISI2}', $daftar_berita);
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();
?>