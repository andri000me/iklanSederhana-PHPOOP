<?php 
session_start();
error_reporting(0);

// panggil file-file yang diperlukan
include ('../inc/class_skin.php');
include ('../inc/class_halaman.php');
include ('../inc/konfig.php');
include ('../template/admin_var.php');

// dapatkan data dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'view';

$proses = filter_str($proses); // filter string

konek_db(); // koneksikan ke database

// cek apakah admin sudah login atau belum
if (!cek_session('admin'))
	$berita = $not_login;
else
{

	// handel setiap proses dengan switch dan case
	switch ($proses)
	{
		case 'view':
		// dapatkan data dari URL
		$page = filter_str($_GET['page']);
		if ($page == '')
			$page = 0;

		$judul = "<h2>Daftar Berita</h2>\n";

		// panggil class halaman
		$hal = new halaman; // buat objek halaman
		$hal->set_tabel('tb_berita'); // pilih tabel tb_iklan
		$hal->set_page($page); // data halaman yang dihandel
		$pph = $hal->set_pph($a_bph); // berita per halaman (konfig.php)

		// lakukan query
		$hal->query_SQL(1); // lakukan query
		$jml_berita = $hal->get_jml_data(); // dapatkan jumlah berita
		$hal->get_jml_hal(); // dapatkan jumlah halaman

		// cek jumlah member
		if ($jml_berita == 0)
			$berita = "<p>Total terdapat: $jml_berita berita. <br>"
		."<a href='berita.php?proses=tambah'>Tambah Berita</a></p>$kembali\n";
		else
		{
			$record = $hal->get_record(); // dapatkan jumlah record
			$hasil = $hal->query_SQL("SELECT * FROM tb_berita ORDER BY id_berita DESC LIMIT $record, $pph");

			// buat tabel dan form
			$berita = "
			<p>Total terdapat: $jml_berita berita.</p>
			<p><a href='berita.php?proses=tambah'>Tambah Berita</a></p>
			<form action='berita.php?proses=hapus' method='post'>
				<table border='0' cellpadding='4' width='100%'>
					<tr bgcolor='#7cb500' class='putih'>
						<td>Judul Berita</td><td>Tanggal Post</td><td>Edit?</td>
						<td align='center'>Hapus?</td>
					</tr>\n";

					// tampilkan berita dengan looping
					while ($data = mysql_fetch_array($hasil)) {
						// link untuk edit berita
						$link = "<a href='berita.php?proses=edit&id=$data[0]'>Edit</a>";
						$checkbox = "<input type='checkbox' name='hapus[]' value='$data[0]'>";

						$berita .= "
						<tr>
							<td>$data[1]</td><td>$data[3]</td><td>$link</td>
							<td align='center'>$checkbox</td>
						</tr>\n";
					}
					$hal->set_hal(); // beri nilai untuk Last, Before, Next

					// tutup tabel
					$berita .= "
					<tr bgcolor='#7cb500' height='20'><td colspan='3'></td>
						<td><input type='submit' value='H A P U S'></td></tr>
					</table>\n</form>\n\n"
					.$hal->show_page("berita.php?proses=view");
		}
		break;

		case 'hapus':

		$hapus = $_POST['hapus']; // hapus bertipe array

		if ($hapus == '')
			$berita ="<p>Error: Anda belum memilih item. <br>\n$kembali</p>\n";
		else
		{
			// gunakan looping untuk menghapus setiap item yang dicek
			foreach ($hapus as $sampah) {
				$hasil = mysql_query("DELETE FROM tb_berita WHERE id_berita=$sampah");
				// cek status
				if (!$hasil)
					$berita = "Error: Berita dengan id: $sampah tidak dapat dihapus.<br>$kembali\n";
				else
					$berita = "Berita dengan id: $sampah berhasil dihapus.<br>$kembali\n";
			}
		}
		break;

		case 'edit':

		$id = filter_str($_GET['id']);
		$judul = "<h2>Edit Berita</h2>\n";

		// lakukan query
		$hasil = mysql_query("SELECT * FROM tb_berita WHERE id_berita=$id");
		$data = mysql_fetch_array($hasil); // pecah menjadi array

		$jdl = stripslashes($data['jdl_berita']); // judul iklan
		// gunakan htmlspecialchars() agar tag-tag HTML tidak diproses
		$isi = htmlspecialchars(stripslashes($data['isi_berita'])); // isi berita
		$tgl = stripslashes($data['tgl_berita']); // tanggal

		// buat form dan tabel
		$berita = "
		<p>Silahkan edit berita lalu tekan EDIT untuk memasukan perubahan.</p>
		<form action='berita.php?proses=proses_edit' method='post'>
			<table border='0' cellpadding='4'>
				<tr bgcolor='#7cb500' align='center'>
					<td class='putih' colspan='2'>Form Edit Berita</td></tr>
				<tr>
					<td>Judul Berita: </td>
					<td><input type='text' name='jdl' size='50' value='$jdl'></td></tr>
				<tr>
					<td>Isi Berita: </td>
					<td><textarea name='isi' rows='10' cols='50'>$isi</textarea></td></tr>
				<tr>
					<td>Tanggal: </td>
					<td><input type='text' name='tgl' value='$tgl'></td></tr>
				<tr>
					<td></td><td><input type='submit' value='E D I T'></td></tr>
				<tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>
			</table>
			<input type='hidden' name='id' value='$id'>
		</form>\n\n";
		break;

		case 'proses_edit':
		// ambil data yang di-post
		$id = $_POST['id'];
		$jdl = $_POST['jdl'];
		$isi = $_POST['isi'];
		$tgl = $_POST['tgl'];

		// cek apakah masih ada field yang kosong
		if (!cek_field($_POST))
			$berita = "<p>Error: Masih ada field yang kosong.<br>\n$kembali</p>\n";
		else
		{
			// lakukan query untuk mengupdate berita
			$hasil = mysql_query("UPDATE tb_berita SET jdl_berita='$jdl',
				isi_berita='$isi', tgl_berita='$tgl' WHERE id_berita=$id");
			// cek status
			if (!$hasil)
				$berita = "<p>Error: Gagal mengupdate berita.<br>\n$kembali</p>\n";
			else
				$berita = "<p>Berita berhasil diupdate.<br>\n$kembali</p>\n";
		}
		break;

		case 'tambah':

		$judul = "<h2>Tambah Berita</h2>\n";
		$tgl = date('d-m-Y, H:i'); // tanggal sekarang

		// buat form dan tabel
		$berita = "
		<p>Isi semua field dibawah ini untuk menambah berita.</p>
		<form action='berita.php?proses=proses_tambah' method='post'>
			<table border='0' cellpadding='4'>
				<tr bgcolor='#7cb500' align='center'>
					<td colspan='2' class='putih'>Form Tambah Berita</td></tr>
				<tr>
					<td>Judul berita: </td>
					<td><input type='text' name='jdl' sizze='50'></td></tr>
				<tr>
					<td>Isi Berita: </td>
					<td><textarea name='isi' rows='10' cols='50'></textarea></td></tr>
				<tr>
					<td>Tanggal: </td>
					<td><input type='text' name='tgl' value='$tgl'></td></tr>
				<tr>
					<td></td><td><input type='submit' value='TAMBAH'></td></tr>
				<tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>
			</table>
		</form>\n\n";
		break;

		case 'proses_tambah':
		// ambil data yang dipost
		$jdl = addslashes($_POST['jdl']); // tambahkan addslashes()
		$isi = addslashes($_POST['isi']);
		$tgl = addslashes($_POST['tgl']);

		// cek apakah masih ada field yang kosong
		if (!cek_field($_POST))
			$berita = "<p>Error: Masih ada field yang kosong.<br>\n$kembali</p>\n";
		else
		{
			// lakukan query SQL untuk memasukan ke database
			$hasil = mysql_query("INSERT INTO tb_berita VALUES (0, '$jdl', '$isi', '$tgl')");
			// cek status
			if (!$hasil)
				$berita = "<p>Error: Gagal memasukan berita kedatabase.<br>\n$kembali</p>\n";
			else
				$berita = "<p>Berita berhasil dimasukan ke database.<br>\n$kembali</p>\n";
		}
		break;
	} // akhir dari switch
} // akhir dari else

mysql_close(); // tutup koneksi

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $berita);
$skin->ganti_tag('{MENU}', $anim_teks);
$skin->ganti_tag('{SISI1}', $admin_menu);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();

?>