<?php 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');

konek_db(); // koneksikan ke MySQL server

// dapatkan data dari URL
$page = $_GET['page'];
if ($page == '')
	$page = 0;

$kat = $_GET['kat'];
$keyword = $_GET['keyword'];

// filter data
$page = filter_str($page);
$kat = filter_str($kat, " ");
$keyword = filter_str($keyword, " "); // spasi diperbolehkan

// variabel untuk query
$query = "SELECT * FROM tb_iklan WHERE isi_iklan LIKE '%$keyword%' AND id_kategori='$kat'";

// panggil class halaman
$hal = new halaman; // buat objek halaman
$hal->set_tabel('tb_iklan'); // tentukan tabel
$hal->set_page($page); // data halaman yang dihandel
$pph = $hal->set_pph(2); // berita per halaman

// lakukan query
$hal->query_SQL($query); // lakukan query
$jml_iklan = $hal->get_jml_data(); // dapatkan jumlah iklan
$hal->get_jml_hal(); // dapatkan jumlah halaman

if ($jml_iklan == 0) // cek jumlah iklan
	$iklan = "<p><font color='red'>Iklan yang anda cari tidak ditemukan.</font></p>\n$kembali";
else
{

	$record = $hal->get_record(); // dapatkan jumlah record
	$hasil = $hal->query_SQL($query." ORDER BY id_kategori DESC LIMIT $record, $pph");

	if ($kat == 1) {
		$kategori = "Komputer";
	}elseif ($kat == 2) {
		$kategori = "Internet";
	}elseif ($kat == 3) {
		$kategori = "Elektronik";
	}elseif ($kat == 4) {
		$kategori = "Lowongan";
	}elseif ($kat == 5) {
		$kategori = "Otomotif";
	}elseif ($kat == 6) {
		$kategori = "Properti";
	}elseif ($kat == 7) {
		$kategori = "Lainnya";
	}
	// lakukan looping untuk menampilkan semua iklan

	$iklan = "<h2>Hasil pencarian untuk kategori: $kategori</h2>\n"
	."<p><a href='iklan.php?proses=view&hari=$hari'>"
	."<< Kembali ke Daftar Kategori</a></p>\n"
	."<table border='0' cellpadding='4' width='100%'>\n";

	while ($data = mysql_fetch_array($hasil)) {
		$iklan .= "<tr bgcolor='#7cb500'>\n"
		." <td class='jdl_iklan'>$data[jdl_iklan] &nbsp &nbsp $data[tgl_post]</td>\n</tr>\n"
		."<tr>\n <td class='kecil'>".nl2br($data['isi_iklan'])."</td>\n"
		."<td></td>\n</tr>\n";
	}

	$hal->set_hal();
	$iklan .= "<tr height='20' bgcolor='#7cb500'><td colspan='2'></td></tr>\n"
	."</table>\n"
	.$hal->show_page("cari.php?kat=$kat&keyword=$keyword");
}
mysql_close();

// panggil class skin
$skin = new skin; // buat objek skin
$skin->ganti_skin('template/skin_utama.php'); // tentukan file template
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{MENU}', $menu);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $iklan);
$skin->ganti_tag('{SISI1}', $iklan_sisi);
$skin->ganti_tag('{SISI2}', '');
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();
?>