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
	$iklan = $not_login; // $not_login ada di admin_var.php
else
{

	// handel setiap proses dengan switch dan case
	switch ($proses)
	{
		case 'view':
		$judul = "<h2>Pilih Kategiri Iklan</h2>\n";
		$iklan = "
		<p>Pilih kategori iklan yang ingin anda edit.</p>
		<ul>
			<p><li><a href='iklan.php?proses=edit&kat=komputer'>Komputer</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=internet'>Internet</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=elektronik'>Elektronik</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=lowongan'>Lowongan</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=otomotif'>Otomotif</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=properti'>Properti</a></li></p>
			<p><li><a href='iklan.php?proses=edit&kat=lainnya'>Lainnya</a></li></p>
		</ul>";
		break;

		case 'edit':

		// dapatkan data dari URL
		$kat = filter_str($_GET['kat']);
		$page = filter_str($_GET['page']);

		if ($page == '')
			$page = 0;

		$judul = "<h2>Daftar iklan untuk kategori: $kat</h2>\n";

		// panggil class halaman
		$hal = new halaman; // buat objek halaman
		$hal->set_tabel('tb_iklan'); // pilih tabel tb_iklan
		$hal->set_page($page); // data halaman yang dihandel
		$pph = $hal->set_pph($a_iph); // iklan per halaman (konfig.php)

		// lakukan query
		$hal->query_SQL(2, 'kategori', $kat); // lakukan query
		$jml_iklan = $hal->get_jml_data(); // dapatkan jumlah berita
		$hal->get_jml_hal(); // dapatkan jumlah halaman

		// cej jumlah iklan
		if ($jml_iklan == 0)
			$iklan = "<p>Tidak ada ilan untuk kategori: $kat<br>$kembali</p>\n";
		else
		{
			$record = $hal->get_record(); // dapatkan jumlah record
			$hasil = $hal->query_SQL(3, 'kategori', $kat, 'id_iklan');

			// buat tabel dan form
			$iklan = "
			<p>Jumlah iklan untuk kategori " .ucfirst($kat) .": $jml_iklan</p>
			<form action='iklan/php?proses=hapus' method='post'>
				<table border='0' cellpadding='4' width='post'>
					<tr bgcolor='#7cb500' class='putih'>
						<td>Judul Iklan</td><td>Posted By</td><td>Posted On</td>
						<td align='center'>Hapus?</td></tr>\n";

						// lakukan looping untuk menampilkan iklan
						while ($data = mysql_fetch_array($hasil)) {
							$checkbox = "<input type='checkbox' name='hapus[]' value='$data[id_iklan]'>";
							$iklan .= "
							<tr>
								<td>$data[jdl_iklan]</td><td>$data[username]</td><td>$data[tgl_post]</td>
								<td align='center'>$checkbox</td>
							</tr>\n";
						}

						$hal->set_hal(); // tentukan nilai next, before dan last

						// tutup tabel
						$iklan .= "
						<tr bgcolor='#7cb500' height='20'><td colspan='3'></td>
							<td><input type='submit' value='HAPUS'></td></tr>
						</table>\n</form>\n"
						// tampilkan link nomor halaman
						.$hal->show_page("iklan.php?proses=edit&kat=$kat");
		}
		break;

		case 'hapus':

		$hapus = $_POST['hapus']; // hapus bertipe array
		if ($hapus == '')
			$iklan = "<p>Error: Anda belum memilih item.<br>\n$kembali</p>\n";
		else
		{
			// gunakan looping untuk menghapus setiap item
			foreach ($hapus as $sampah) {
				$hasil = mysql_query("DELETE FROM tb_iklan WHERE id_iklan=$sampah");
				// cek status
				if (!$hasil)
					$iklan = "Error: gagal menghapus iklan dengan id: $sampah.<br>$kembali\n";
				else
					$iklan = "Iklan dengan id: $sampah berhasil dihapus.<br>$kembali\n";
			}
		}
		break;

	} // akhir dari switch
} // akhir dari else
mysql_close(); // tutup koneksi

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $iklan);
$skin->ganti_tag('{MENU}', $anim_teks);
$skin->ganti_tag('{SISI1}', $admin_menu);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();

?>