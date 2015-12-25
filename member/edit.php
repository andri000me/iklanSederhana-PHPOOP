<?php 
session_start();
error_reporting(0);

include ('../inc/class_skin.php');
include ('../template/member_var.php');

$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses);

konek_db(); // koneksikan ke MySQL server

// cek user apakah sudah login atau belum
if (!cek_session('member'))
	$edit = $not_login;
else
{

	// handel setiap proses dengan case dan switch
	switch ($proses)
	{
		case 'form':
		// ambil data dari URL
		$kat = filter_str($_GET['kat']);
		$id = filter_str($_GET['id']);

		// lakukan query untuk mendapatkan informasi iklan
		$hasil = mysql_query("SELECT * FROM tb_iklan WHERE id_iklan='$id'");
		$data = mysql_fetch_array($hasil);

		$jdl = stripslashes($data['jdl_iklan']); // judul iklan
		$isi = stripslashes($data['isi_iklan']); // isi iklan

		$judul = "<h2>Edit Iklan</h2>\n"; // header judul

		// variabel untuk menampilkan form edit
		$edit = "
		<p>Pada halaman ini anda dapat melakukan pengeditan pada iklan anda.
		Disini anda juga dapat menghapus iklan anda. Untuk memasukan perubahan
		klik UPDATE, sedangkan untuk menghapus klik HAPUS.</p>

		$java
		<form action='edit.php?proses=proses_form' method='post'>
		<input type='hidden' name='id' value='$id'>
		<table border='0' cellpadding='4'>
		<tr bgcolor='#7cb500' align='center'>
			<td class='putih' colspan='2'>Form Edit</td></tr>
		<tr> \n<td>Kategori: </td><td>$kat</td></tr>
		<tr> \n<td>Judul Iklan: </td>
			<td><input type='text' name='jdl_iklan' value='$jdl' size='50'></td></tr>
		<tr> \n<td>Isi Iklan: </td>
			<td><textarea name='isi' rows='10' cols='50' onkeyup='batas_kar(this.form)'>$isi</textarea></td></tr>
		<tr> \n<td>Sisa Karakter: </td>
			<td><input type='text' name='sisa' value='500' readonly='yes' size='3'></td></tr>
		<tr><td><input type='submit' value='UPDATE'></form></td>
			<td><form action='edit.php?proses=hapus' method='post'>
			<input type='hidden' name='id' value='$id'>
			<input type='submit' value='HAPUS'></td></tr>
		<tr height='20' bgcolor='#7cb500'><td colspan='2'></td></tr>
		</table>
		"; // akhir dari variabel $edit
		break;

		case 'proses_form':

		$jdl = addslashes($_POST['jdl_iklan']);
		$isi = addslashes($_POST['isi']);

		$id = $_POST['id'];

		// cek field
		if (!cek_field($_POST))
			$edit = "Error: Masih ada field yang kosong.<br>\n$kembali";
		else
		{
			// update isi iklan
			$hasil = mysql_query("UPDATE tb_iklan SET jdl_iklan='$jdl', isi_iklan='$isi' WHERE id_iklan=$id");
			// cek status
			if (!$hasil)
				$edit = "Error: Gagal mengupdate iklan.<br>\n$kembali";
			else
				$edit = "Iklan berhasil diupdate. <br>\n$kembali";
		}
		break;

		case 'hapus':
		$id = $_POST['id']; // dapatkan id iklan yang akan dihapus

		// lakukan query DELETE untuk menghapus
		$hasil = mysql_query("DELETE FROM tb_iklan WHERE id_iklan=$id");
		// cek status
		if (!$hasil)
			$edit = "<p>Error: Gagal menghapus iklan yang ber-id: $id.<br>$kembali</p>\n";
		else
			$edit = "<p>Iklan dengan id: $id berhasil dihapus<br>$kembali<br></p>\n";
	} // akhir dari switch
} // akhir dari else
$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $edit);
$skin->ganti_tag('{MENU}', $mem_menu);
$skin->ganti_tag('{SISI1}', $iklanku);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();
?>