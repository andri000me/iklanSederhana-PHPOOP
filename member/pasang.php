<?php 
session_start();
error_reporting(0);

// panggil file-file yang diperlukan
include ('../inc/class_skin.php');
include ('../template/member_var.php');
include ('../inc/class_waktu.php');
include ('../inc/konfig.php');

$proses = $_GET['proses'];
if ($proses == '')
	$proses = 'form';

$proses = filter_str($proses);

konek_db(); // koneksikan ke MySQL server

// cek user apa sudah login atau belum
if (!cek_session('member'))
	$pasang = $not_login;
else
{
	switch ($proses)
	{
		case 'form':

		$judul = "<h2>Pasang Iklan</h2>\n";
		// cek jumlah iklan user ini jika sudah 10; tidak bpleh post
			$hasil = mysql_query("SELECT * FROM tb_iklan WHERE username='$_SESSION[member]'");
		$jml_iklan = mysql_num_rows($hasil); // jumlah iklan
				
					
		// tentukan kondisi, $max_post ada di konfig.php
		if ($jml_iklan > $max_post)
			$pasang = "<p><font color='red'>Jumlah iklan anda sudah melebihi "
		."quota maksimal ($max_post iklan). Hapus salah satu iklan "
		."anda dan coba lagi.</font></p>\n";
		else
		{
					
			$judul = "<h2>Pasang Iklan</h2>\n";
			$pasang = "
			<p>Pada halaman ini anda dapat memasang iklan sesuai dengan jasa/produk yang anda tawarkan.</p>
			$java
			<form action='pasang.php?proses=proses_form' method='post'>
				<table border='0' cellpadding='4'>
					<tr bgcolor='#7cb500' align='center'>
						<td colspan='2' class='putih'>Form Pasang Iklan</td></tr>
					<tr> \n<td>Pilih kategori: </td>
						<td><select name='kategori'>";
						 
						$a="SELECT * FROM kategori";
					$b=mysql_query($a);
					while ($c=mysql_fetch_array($b)) {						
							$pasang .="<option value='$c[id_kategori]'>$c[kategori]</option>";
					}
							$pasang.="
							</select>\n</td></tr>
						<tr> \n<td>Judul Iklan:</td>
							<td><input type='text' name='jdl_iklan' size='50' maxlength='100'></td></tr>
						<tr> \n<td>Isi Iklan: </td>
							<td><textarea name='isi' rows='10' cols='50' onkeyup='batas_kar(this.form);'></textarea></td></tr>
						<tr> \n<td>Sisa Karakter: </td>
							<td><input type='text' name='sisa' value='500' size='3' readonly='yes'></td></tr>
						<tr> \n<td></td><td><input type='submit' value='P O S T'></td></tr>
						<tr height='20' bgcolor='#7cb500'><td colspan='2'></td></tr>
					</table>\n</form>\n\n";
		}
		break;

		case 'proses_form':
		// ambil data yang di-post
		$kategori = $_POST['kategori'];
		$jdl_iklan = addslashes($_POST['jdl_iklan']);
		$isi = strip_tags(addslashes($_POST['isi']));

		$pesan_error = ''; // nilai awal kosong

		// cek setiap field
		if (!cek_field($_POST))
			$pesan_error = "Error: Masih ada field yang kosong.<br>\n";

		if (strlen($isi) < 10)
			$pesan_error .= "Error: Kelihatannya iklan anda terlalu pendek.<br>\n";

		// jika $pesan_error tidak kosong maka ada error
		if ($pesan_error != '')
			$pasang = $pesan_error.$kembali;
		else
		{
			// buat variabel untuk dimasukan ke database
			$tgl = date('d-m-Y, H:i');
			$user = $_SESSION['member'];

			$waktu = new waktu;
			$waktu->set_date();
			$waktu->set_mode(2); // mode tetap
			$skr = $waktu->set_tgl(1); // sekarang dalam detik
			$hasil = mysql_query("INSERT INTO tb_iklan VALUES (0, '$kategori', '$user', '$jdl_iklan',
				'$isi', '$tgl', '$skr')");
			// cek status
			if (!$hasil)
				$pasang = "Error: Tidak dapat mamasukan data ke database.<br>\n"
			."<a href='mailto: root@localhost'>Kontak Admin</a>\n";
			else
				$pasang = "<p>Iklan berhasil di-post.</p>\n";
		}
		break;
	}
} // akhir dari else

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
// ganti tag tertentu dengan variabel yang diinginkan
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $pasang);
$skin->ganti_tag('{MENU}', $mem_menu);
$skin->ganti_tag('{SISI1}', $iklanku);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>