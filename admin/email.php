<?php 
session_start();
error_reporting(0);

// panggil file-file yang diperlukan
include ('../inc/class_skin.php');
include ('../template/admin_var.php');

// dapatkan data dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses); // filter string

konek_db(); // koneksikan ke database

// handel setiap data dengan switch dan case

switch ($proses)
{
	case 'form':

	$judul = "<h2>Form Send Mail</h2>";
	// buat tabel dan form
	$send_mail = "
	<p>Silahkan isikan subject dan isi email. Tekan KIRIM untuk mulai mengirim email</p>
	<form action='email.php?proses=proses_form' method='post'>
	<table border='0' cellpadding='4'>
		<tr bgcolor='#7cb500' align='center'>
			<td class='putih' colspan='2'>Form Send Mail</td></tr>
		<tr><td>Subject: </td>
			<td><input type='text' name='subject' size='60'></td></tr>
		<tr><td>Isi/body: </td>
			<td><textarea name='isi' rows='10' cols='60'></textarea></td></tr>
		<tr><td></td><td><input type='submit' value='K I R I M'></td></tr>
		<tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>
	</table>
	</form>\n\n";
	break;

	case 'proses_form':

	// ambil data yang di-post
	$subject = $_POST['subject'];
	$isi = $_POST['isi'];
	$form = "Form: noreply@localhost"; // alamat email anda

	// cek field
	if (!cek_field($_POST))
		$send_mail = "<p>Error: Masih ada field yang kosong.<br>\n</p>\n";
	else
	{
		// lakukan query untuk mendaftar alamat email member
		$hasil = mysql_query("SELECT email FROM member");
		// lakukan looping untuk mengirim ke semua alamat
		while ($data = mysql_fetch_array($hasil)) {
			$to = $data[0]; // alamat email masing-masing member

			// jika anda tidak memiliki program mail server atau anda belum di server
			// sebenarnya beri komentar pada fungsi mail berikut
			mail($to, $subject, $isi, $form);
		}

		$send_mail = "<p>Email telah terkirim.</p>";
	} 
	break;
} // akhir dari switch

mysql_close(); // tutup koneksi

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $send_mail);
$skin->ganti_tag('{MENU}', $anim_teks);
$skin->ganti_tag('{SISI1}', $admin_menu);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();

?>