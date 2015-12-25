<?php 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');
include ('inc/class_waktu.php');

// dapatkan data proses dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'view';

	$proses = filter_str($proses);

	konek_db(); // koneksikan ke MySQL server

	// handel setiap proses dengan case switch
	switch ($proses)
	{
		case 'view':
		$hari = $_GET['hari'];
		if ($hari == '')
			$hari = 'today';

		$hari = filter_str($hari);

		$waktu = new waktu; // buat objek waktu
		$waktu->set_date();
		$waktu->set_mode(0); // mode pengurangan tanggal
		$kemarin = $waktu->set_tgl(0, 1); // dikurangi 1
		// cek isi dari $hari untuk menentukan judul heading
		if ($hari == 'today')
			$heading = "<h2>Iklan Untuk Hari Ini</h2>\n";
		else if ($hari == 'kemarin') {
			$heading = "<h2>Iklan kemarin, $kemarin</h2>\n";
		}
		else
			$heading = "<h2>Iklan sebelum tanggal $kemarin</h2>\n";

		$iklan = "<p>Silahkan pilih kategori iklan yang ingin anda tampilkan. \n"
		."Klik masing-masing link menampilkan daftar iklan.<br><br>\n"
		."Daftar Kategori iklan: </p>\n"
		."<ul>\n";
		$a="select * from kategori";
		$b=mysql_query($a);
		while ($c=mysql_fetch_array($b)) {
		$iklan.="<li><p><a href='iklan.php?proses=show&hari=$hari&kat=$c[id_kategori]'>$c[kategori]</a></p></li>\n";
		}
		/*."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=komputer'>Komputer</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=internet'>Internet</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=elektronik'>Elektronik</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=lowongan'>Lowongan</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=otomotif'>Otomotif</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=properti'>Properti</a></p></li>\n"
		."<li><p><a href='iklan.php?proses=show&hari=$hari&kat=lainnya'>lainnya</a></p></li>\n"*/
		$iklan.="</ul>\n"
		."<p><a href='iklan.php?proses=view&hari=today'>Hari ini</a> &nbsp \n"
		."<a href='iklan.php?proses=view&hari=kemarin'>Kemarin</a> &nbsp \n"
		."<a href='iklan.php?proses=view&hari=lama'>Sebelum $kemarin</a></p>\n";
		break;

		case 'show':
		// dapatkan data page dari URL
		$page = filter_str($_GET['page']);
		if ($page == '')
			$page = 0;

		$hari = filter_str($_GET['hari']);

		$kat = filter_str($_GET['kat']);
		// if ($kat == '')
		// 	$kat = 'komputer';

		$waktu = new waktu; // buat objek waktu
		$waktu->set_date();

		// cek kondisi hari untuk menentukan timestamp
		if ($hari == 'today') {
			$waktu->set_mode(2); // tanggal tetap
			$tstamp = $waktu->set_tgl(1); // sekarang dalam detik
		}
		elseif ($hari == 'kemarin' || $hari == 'lama') {
			$waktu->set_mode(0); // dikurangi
			$tstamp = $waktu->set_tgl(1, 1); // kemarin dalam detik
		}
		// variabel untuk melakukan query
		$query = "SELECT * FROM tb_iklan,kategori WHERE tb_iklan.id_kategori=kategori.id_kategori AND tb_iklan.id_kategori='$kat'";
		if ($hari == 'lama')
			$query = "SELECT * FROM tb_iklan,kategori WHERE tb_iklan.id_kategori=kategori.id_kategori AND tb_iklan.id_kategori='$kat'";

		$page = filter_str($page);
		$a = mysql_query($query);
		$b=mysql_fetch_array($a);
		// $kat = filter_str($kat);

		// panggil class halaman
		$hal = new halaman; // buat objek halaman
		$hal->set_tabel('tb_iklan'); // tentukan tabel
		$hal->set_page($page); // data halaman yang dihandel
		$pph = $hal->set_pph(2); // berita per halaman

		// lakukan query
		$hal->query_SQL($query); // lakukan query
		$jml_iklan = $hal->get_jml_data(); // dapatkan jumlah iklan
		$hal->get_jml_hal(); // dapatkan jumlah halaman

		if ($jml_iklan == 0)
			$iklan = "<p><font color='red'>Tidak ada iklan untuk kategori $b[kategori].</font></p>\n$kembali";
		else
		{

			$record = $hal->get_record(); // dapatkan jumlah record
			$hasil = $hal->query_SQL($query." ORDER BY id_iklan DESC LIMIT $record, $pph");

			// lakukan looping untuk menampilkan semua iklan
			$iklan = "<h2>Daftar Iklan untuk kategori: $kat</h2>\n"
			."<p><a href='iklan.php?proses=view&hari=$hari'>"
			."<< Kembali ke Daftar Kategori</a></p>\n"
			."<table border='0' cellpadding='4' width='100%'>\n";

			while ($data = mysql_fetch_array($hasil)) {
				$isi = nl2br(stripslashes($data['isi_iklan']));
				$jdl = stripslashes($data['jdl_iklan']);
				$iklan .= "<tr bgcolor='#7cb500'>\n"
				." <td class='jdl_iklan'>$jdl &nbsp &nbsp $data[tgl_post]"
				."</td>\n</tr>\n"
				."<tr>\n <td class='kecil'>$isi</td>\n"
				."<td></td>\n</tr>\n";
			}

			$hal->set_hal();
			$iklan .= "<tr height='20' bgcolor='#7cb500'><td colspan='2'></td></tr>\n"
			."</table>\n"
			.$hal->show_page("iklan.php?proses=show&hari=$hari&kat=$kat");
		}
		break;

	} // akhir dari switch
	mysql_close();

	// panggil class skin
	$skin = new skin; // buat objek skin
	$skin->ganti_skin('template/skin_utama.php'); // tentukan dile template
	$skin->ganti_tag('{SEKARANG}', $tgl);
	$skin->ganti_tag('{MENU}', $menu);
	$skin->ganti_tag('{JUDUL}', $heading);
	$skin->ganti_tag('{UTAMA}', $iklan);
	$skin->ganti_tag('{SISI1}', $iklan_sisi);
	$skin->ganti_tag('{SISI2}', $daftar_berita);
	$skin->ganti_tag('{CARI}', $cari);
	$skin->ganti_tampilan();
?>