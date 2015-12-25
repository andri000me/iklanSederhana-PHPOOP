<?php 
error_reporting(0);
// cegah pengaksesan langsung dari browser
if (eregi('var_utama.php', $_SERVER['PHP_SELF'])) {
	header('Location: ../index.php'); // kembalikan ke halaman utama
	exit;
}

// panggil file class_halaman.php
include('inc/class_halaman.php');
// panggil file konfig.php
include('inc/konfig.php');

// buat variabel untuk tanggal sekarang
$tgl = "Hari ini: ".show_tgl(); // fungsi untuk menampilkan tanggal sekarang

// buat variabel untuk menampilkan menu
$menu = "<a title='Halaman Utama' href='index.php'>Home</a> &nbsp :: &nbsp \n"
."<a title='Halaman Login' href='login.php'>Member Login</a> &nbsp :: &nbsp \n"
."<a title='Form Pendaftaran' href='daftar.php'>Daftar</a> &nbsp :: &nbsp \n"
."<a title='Berita Terbaru' href='news.php'>News</a> &nbsp :: &nbsp \n"
."<a title='Daftar Iklan' href='iklan.php'>Iklan</a>\n";

// dapatkan data pege dari URL
$page = $_GET['page'];
if ($page == '')
	$page = 0;

$page = filter_str($page);

/* variabel untuk link daftar berita pada kiri halaman utama */
konek_db(); // koneksikan ke MySQL server
$hal = new halaman; // buat objek halaman
$hal->set_tabel('tb_berita'); // tentukan tabel
$hal->set_page($page); // data halaman yang dihandel
$pph = $hal->set_pph($u_jbph); // judul berita per halaman (konfig.php)

// lakukan query
$hal->query_SQL(1); // lakukan query
$jml_berita = $hal->get_jml_data(); // dapatkan jumlah berita
$hal->get_jml_hal(); // dapatkan jumlah halaman

$record = $hal->get_record(); // dapatkan jumlah record
$hasil = $hal->query_SQL("SELECT * FROM tb_berita ORDER BY id_berita DESC LIMIT $record, $pph");

// variabel untuk menampilkan cuplikan berita
$daftar_berita = "<table border='0' cellpadding='4' width='100%'>\n"
."<tr bgcolor='#003366'>\n"
."	<td class='putih'><b>Berita Terbaru</b></td>\n </tr> \n";

// tampilkan hasil dengan looping
while ($data = mysql_fetch_array($hasil)) {
	$link = "<a class='iklan' href='news.php?proses=full_news&id="
	.$data[id_berita]."'>$data[jdl_berita]</a>";

	$daftar_berita .= "<tr><td class='kecil'>$link</td></tr>\n";
}

$hal->set_hal(); // tentukan nilai Last, Next, dan Before
$daftar_berita .= "</table>\n<font size=-2>"
.$hal->show_page("index.php?proses=view")."</font>\n";

mysql_close(); // tutup koneksi ke MySQL

/* variabel untuk link iklan pada sebelah kiri halaman utama */
$iklan_sisi = "<table border='0' cellpadding='4' width='100%'>\n"
."<tr bgcolor='#003366'>\n"
." <td class='putih'>Iklan</td>\n</tr>\n"
."<tr>\n <td>\n"
."<p>Klik link iklan yang anda inginkan</p>\n"
."<p><a href='iklan.php?proses=view&hari=today'>Iklan hari ini</a></p>\n"
."<p><a href='iklan.php?proses=view&hari=kemarin'>Iklan kemarin</a></p>\n"
."<p>Ingin pasang iklan? <br>"
."Mohon <a href='daftar.php'>register</a> dulu.</p>\n"
." </td>\n</tr>\n</table>\n";

// variabel untuk form pencarian iklan
$cari = "<form action='cari.php' method='get'>\n"
."<table border='0' cellpadding='4'>\n"
."<tr> \n<td>\n"
."Kategori: <select name='kat'>\n";
		mysql_connect('localhost','root','');
		mysql_select_db('iklan');
	$a="select * from kategori";
		$b=mysql_query($a);
		while ($c=mysql_fetch_array($b)) {
		$cari.="<option value='$c[id_kategori]'>$c[kategori]</option>\n";
		}
/*."<option value='1'>Komputer</option>\n"
."<option value='2'>Internet</option>\n"
."<option value='3'>Elektronik</option>\n"
."<option value='4'>Lowongan</option>\n"
."<option value='5'>Otomotif</option>\n"
."<option value='6'>Properti</option>\n"
."<option value='7'>Lainnya</option>\n"
*/
$cari.="</select>\n<br>"
."Keyword: <input type='text' name='keyword' class='searchbox'>\n"
."<input type='submit' value='CARI'>\n"
."</td></tr></table>\n</form>\n";

// buat link kembali berguna jika ada error.
$kembali = "<br><a href='javascript: history.back()'><< Kembali</a>\n"
?>