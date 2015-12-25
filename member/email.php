<?php 
session_start();
error_reporting(0);

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
	$email_ad = $not_login;
else
{

	switch ($proses)
	{
		case 'form':

		$judul = "<h2>Email iklan ke seluruh member</h2>\n";
		$email_ad = "<p>Silahkan isi subject dan isi iklan. Email ini akan dikirim ke semua member "
		."iklanUNPAM</p>\n"
		.$java."\n"
		."<form action='email.php?proses=kirim' method='post'>\n"
		."<table border='0' cellpadding='4'>\n"
		."<tr bgcolor='#7cb500' align='center'>\n"
		."	<td colspan='2' class='putih'>Form Email</td></tr>\n"
		."<tr> \n<td>Subject: </td>\n"
		."	<td><input type='text' name='subject' size='50' maxlength='100'></td></tr>\n"
		."<tr> \n<td>Isi Iklan: </td>\n"
		."	<td><textarea name='isi' rows='10' cols='50' onkeyup='batas_kar(this.form);'></textarea></td></tr>\n"
		."<tr> \n<td>Sisa karakter: </td>\n"
		."	<td><input type='text' name='sisa' size='3' value='500' readonly='yes'></td></tr>\n"
		."<tr><td></td><td><input type='submit' value='K I R I M'></td></tr>\n"
		."<tr bgcolor='#7cb500' height='20'><td colspan='2'></td></tr>\n"
		."</table>\n</form>\n\n";
		break;

		case 'kirim':
		$hasil = mysql_query("SELECT * FROM tb_email WHERE username='$_SESSION[member]'");
		$data = mysql_fetch_array($hasil);

		$waktu = new waktu; // buat objek waktu

		$waktu->set_date();
		$waktu->set_mode(2); // mode tetap
		$skr = $waktu->set_tgl(1); // sekarang dalam detik

		$user = $_SESSION['member'];

		// ambil data yang di-post
		$subject = strip_tags($_POST['subject'])." - iklanUNPAM";
		$isi = strip_tags($_POST['isi']);

		if (!cek_field($_POST))
			$email_ad = "Error: Masih ada field yang kosong.<br>\n$kembali";
		else
		{
			if ($skr >= $data['next_post']) // sudah melewati lama hari yang ditentukan
			{

				$isi = "POSTED BY: $user\n"
				."*******************************\n\n"
				.$isi."\n\n\n"
				.str_repeat("*", 70)."\n"
				."Anda menerima email ini karena anda adalah member dari \n"
				."iklanUNPAM\n"
				."(c) 2015 iklanUNPAM\n"
				.str_repeat("*", 70);

				$from = "From: $data[1]"; // email user

				// lakukan looping untuk mengirim email ke seluruh member
				$hasil = mysql_query("SELECT * FROM member WHERE username != '$user'");
				while ($data = mysql_fetch_array($hasil)) {
					$to = $data['email']; // alamat email masing2 member

					mail($to, $subject, $isi, $from); // kirim email
				}

				$waktu = new waktu; // buat objek waktu
				$waktu->set_date();
				$waktu->set_mode(1); // mode penambahan
				$next = $waktu->set_tgl(1, $lama_email); // dalam detik, $lama_email hari ke depan

				// update nilai next_post
				$query = mysql_query("UPDATE tb_email SET next_post=$next WHERE username='$user'");
				if (!$query)
					$email_ad = "<p>Gagal Mengupdate database.</p>\n";
				else
					$email_ad = "<p>Email berhasil dikirim.</p>\n";
			}
			else
			{
				// kalkulasi dari detik menjadi hari
				$nanti = floor(($data['next_post'] - $skr) / 60 / 60 / 24 );
				$email_ad = "<p><font color='red'>Maaf, saat ini anda belum bisa mengirim email. "
				."Anda dapat mengirim email $nanti hari lagi.</font></p>\n";
			}
		}
		break;
	}
}

$skin = new skin; // buat objek skin
$skin->ganti_skin('../template/skin_utama.php');
$skin->ganti_tag('{SEKARANG}', $tgl);
$skin->ganti_tag('{JUDUL}', $judul);
$skin->ganti_tag('{UTAMA}', $email_ad);
$skin->ganti_tag('{MENU}', $mem_menu);
$skin->ganti_tag('{SISI1}', $iklanku);
$skin->ganti_tag('{SISI2}', $login);
$skin->ganti_tag('{CARI}', '');
$skin->ganti_tampilan();
?>