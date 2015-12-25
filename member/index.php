<?php 
session_start();
error_reporting(0);

include ('../inc/class_skin.php');
include ('../template/member_var.php');

$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'view';

$proses = filter_str($proses);

// cek user apakah sudah login atau belum
if (!cek_session('member'))
	$member = $not_login; // $nol_login ada di member_var.php
else
{

	switch ($proses)
	{
		case 'view':
		$judul = "<h2>Selamat Datang di Member Area</h2>\n";
		$member = "<p>Terima kasih karena anda sudah bersedia menjadi member dari website "
		."iklanUNPAM. karena hanya disinilah anda dapat memasang iklan "
		."secara efektif dan cepat. Anda dapat memasang iklan pada kategori sesuai "
		."dengan produk/jasa yang anda tawarkan.</p>\n"
		."<p>Selain dengan menggunakan iklan baris, anda dapat menggunakan fasilitas "
		."email untuk mengirim email ke semua member iklanUNPAM. Dengan "
		."demikian keefektidan iklan anda sangat tinggi. Namun untuk fasilitas "
		."email ini kami hanya memperbolehkan anda mengirim email hanya satu kali "
		."dalam 5 hari.</p>\n";
		break;

		case 'logout':

		if (!logout('member'))
			$member = "<p>Anda telah logout dari sistem. Klik "
		."<a href='../login.php'>disini</a> untuk login kembali.</p>\n";
		break;
	}
}

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
// ganti tag tertentu dengan variabel yang diinginkan
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $member);
$skin->ganti_tag('{MENU}', $mem_menu);
$skin->ganti_tag('{SISI1}', $iklanku);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>