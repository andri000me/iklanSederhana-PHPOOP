<?php 
// panggil file-file yang diperlukan
include ('inc/class_skin.php');
include ('inc/fungsi.php');
include ('template/var_utama.php');

// dapatkan data proses dari URL
$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'view';

$proses = filter_str($proses);

konek_db(); // koneksikan ke MySQL server

switch ($proses)
{
	case 'view':

	// dapatkan data page dari URL
	$page = $_GET['page'];
	if ($page == '')
		$page = 0;

	$page = filter_str($page);

	// panggil class halaman
	$hal = new halaman; // buat objek halaman
	$hal->set_tabel('tb_berita'); // tentukan tabel
	$hal->set_page($page); // data halaman yang dihandel
	$pph = $hal->set_pph($u_bph); // berita per halaman (konfig.php)

	// lakukan query
	$hal->query_SQL(1); // SELECT * FROM tb_berita
	$jml_berita = $hal->get_jml_data(); // dapatkan jumlah berita
	$hal->get_jml_hal(); // dapatkan jumlah halaman

	$record = $hal->get_record(); // dapatkan jumlah record
	$hasil = $hal->query_SQL("SELECT * FROM tb_berita ORDER BY id_berita DESC LIMIT $record, $pph");

	// variabel untuk menampilkan cuplikan berita
	$news = "<table border='0' cellpadding='4' width='100%'>\n"
	."<tr bgcolor='#7cb500'>\n"
	."	<td class='putih'><b>Berita Terbaru</b></td>\n</tr>\n";

	// tampilkan hasil dengan looping
	while ($data = mysql_fetch_array($hasil)) {
		$isi_berita = nl2br($data['isi_berita']);
		$tgl_berita = "[ $data[tgl_berita] ]";
		$jdl_berita = "$tgl_berita<br><b>$data[jdl_berita]</b><br><br>\n";

		$cuplikan = array(); // buat variabel array untuk cuplikan kata
		$pecah_kata = explode(" ", $isi_berita); // pecah setiap kata

		// lakukan looping untuk mendapatkan 25 kata pertama
		for ($i=0; $i < 25; $i++) 
			$cuplikan[$i] = $pecah_kata[$i];

		$cuplikan = implode(" ", $cuplikan); // gabung ke 25 kata tersebut
		$link = "<br><br><a href='news.php?proses=full_news&id=$data[id_berita]'>baca selangkapnya...</a><hr>";

		// susun tampilan
		$news .= "<tr><td>$jdl_berita $cuplikan... $link</td></tr>\n";
	}

	$hal->set_hal(); // tentukan nilai Last, Next, dan Before
	$news .= "</table>\n"
	.$hal->show_page("news.php?proses=view")."</font>\n";

	break;

	case 'full_news':
	// dapatkan id berita dari URL
	$id = $_GET['id'];
	if ($id == '')
		$id = 1;

	$id = filter_str($id);

	// lakukan query untuk menampilkan keseluruhan isi berita
	$hasil = mysql_query("SELECT * FROM tb_berita WHERE id_berita='$id'");
	$data = mysql_fetch_array($hasil); // pecah menjadi array
	$isi = htmlspecialchars($data['isi_berita']); // agar tag HTML tidak diproses

	// tampilkan isi berita
	$news = "$data[tgl_berita]<br>\n<b>$data[jdl_berita]</b>\n"
	."<p>".nl2br(stripslashes($data['isi_berita']))."</p>\n"
	."<a href='news.php?proses=view'>Berita Lainnya</a>\n";
	break;
}

mysql_close();

// panggil class skin
$skin = new skin; // buat objek skin
$skin->ganti_skin('template/skin_utama.php'); // tentukan file template
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{MENU}', $menu);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $news);
$skin->ganti_tag('{SISI1}', $iklan_sisi);
$skin->ganti_tag('{SISI2}', $daftar_berita);
$skin->ganti_tag('{CARI}', $cari);
$skin->ganti_tampilan();

?>