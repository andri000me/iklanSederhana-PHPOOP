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
	$member = $not_login;
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

		$judul = "<h2>Daftar Member iklanUNPAM</h2>\n";

		// panggil class halaman
		$hal = new halaman; // buat objek halaman
		$hal->set_tabel('member'); // pilih tabel tb_iklan
		$hal->set_page($page); // data halaman yang dihandel
		$pph = $hal->set_pph($a_mph); // member per halaman konfig.php

		// lakukan query
		$hal->query_SQL(1); // lakukan query
		$jml_member = $hal->get_jml_data(); // dapatkan jumlah berita
		$hal->get_jml_hal(); // dapatkan jumlah halaman

		// cek jumlah member
		if ($jml_member == 0)
			$iklan = "<p>Tidak ada member yang register. <br></p>$kembali";
		else
		{
			$record = $hal->get_record(); // dapatkan jumlah record
			$hasil = $hal->query_SQL("SELECT * FROM member LIMIT $record, $pph");

			// buat tabel dan form
			$member = "
			<p>Total member yang bergabung: $jml_member orang.</p>
			<form action='member.php?proses=hapus' method='post'>
				<table border='0' cellpadding='4' width='100%'>
					<tr bgcolor='#7cb500' class='putih'>
						<td>Username</td><td>Nama</td><td>Email</td><td>Kota</td>
						<td>Telpon</td><td>Level</td><td align='center'>Hapus?</td></tr>\n";

						// gunakan looping untuk menampikan member
						while ($data = mysql_fetch_array($hasil)) {
							$checkbox = "<input type='checkbox' name='hapus[]' value='$data[username]'>";
							$member .= "
							<tr>
								<td>$data[0]</td><td>$data[2]</td><td>$data[3]</td><td>$data[5]</td>
								<td>$data[6]</td><td>$data[7]</td><td align='center'>$checkbox</td>
							</tr>\n";
						}

						$hal->set_hal(); // beri nilai untuk next, before dan last

						// tutup tabel
						$member .= "
						<tr bgcolor='#7cb500' height='20'><td colspan='6'></td>
							<td><input type='submit' value='H A P U S'></td></tr>
						</table> \n</form>\n\n"
						// tampilkan link nomor halaman
						.$hal->show_page("member.php?proses=view");
		}
		break;

		case 'hapus':

		$hapus = $_POST['hapus']; // hapus bertipe array

		if ($hapus == '')
			$member = "<p>Error: Anda belum memilih item.<br>\n$kembali</p>\n";
		else
		{
			// gunakan looping untuk menghapus setiap item
			foreach ($hapus as $sampah) {
				$hasil = mysql_query("DELETE FROM member WHERE username='$sampah'");
				// cek status
				if (!$hasil)
					$member = "Error: USername $sampah tidak dapat dihapus.<br>$kembali\n";
				else
					$member = "Username $sampah berhasil dihapus.<br>$kembali\n";
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
$skin->ganti_tag('{UTAMA}', $member);
$skin->ganti_tag('{MENU}', $anim_teks);
$skin->ganti_tag('{SISI1}', $admin_menu);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>