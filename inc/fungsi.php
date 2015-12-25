<?php 
// cegah pengaksesan langsung dari berowser
if (eregi('fungsi.php', $_SERVER['PHP_SELF']))
	exit('Error: Akses ditolak.');
	// fungsi untuk mengkoneksikan ke MySQL server
	function konek_db()
	{
		// untuk username = secure dan password = secure123
		@ $koneksi = mysql_connect('localhost', 'root', '');
		// jika gagal melakukan koneksi tampilkan pesan kesalahannya
		if (!$koneksi) {
			echo "Error: ".mysql_errno()."<br>\n";
			echo "Keterangan: ".mysql_errno()."<br>\n";
			exit;
		}
		else
		{
			// pilih database yang digunakan
			mysql_select_db('iklan');
			return true;
		}
	}

	// cek setiap field apa ada yang kosong
	function cek_field($var)
	{
		foreach ($var as $field) {
			if ($field == '' || !isset($field))
				return false;
		}
		return true;
	}

	// cek kevalidan email
	function cek_email($email)
	{
		// fungsi untuk mengecek kevalidan email
		if (ereg('^[a-zA-Z0-9_\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $email))
			return true;
		else
			return false;
	}

	// fungsi untuk menyaring string selain alpabet, numerik dan _
	function filter_str($string, $lainnya='')
	{
		if ($lainnya == '')
			$filter = ereg_replace('[^a-zA-Z0-9_]', '', $string);
		else
			$filter = ereg_replace("[^a-zA-Z0-9_$lainnya]", '', $string);
		return $filter;
	}

	// fungsi untuk mengenkripsi string dengan metode MD5
	// dan membalik urutannya
	function balik_md5($string)
	{
		// untuk membalik urutan string digunakan fungsi strrev()
		$chiper_text = strrev( md5( $string ) );
		return $chiper_text;
	}

	// fungsi untuk mengecek session
	function cek_session($nama_ses)
	{
		// jika session kosong
		if (!isset($_SESSION[$nama_ses]))
			return false; // kembalikan nilai false
		else
			// jika tidak kosong
			return true; // kembalikan nilai true
	}

	// fungsi untuk login
	function login($tabel, $username, $password)
	{
		// lakukan query untuk mencocokkan data
		$hasil = mysql_query("SELECT * FROM $tabel WHERE username='$username' AND password='$password'");

		// cek baris yang dikembalikan
		if (mysql_num_rows($hasil) == 0)
			return false; // data tidak cocok
		else
			return true; // kembalikan nilai true
	}

	// fungsi untuk login_admin
	function login_admin($tabel, $username, $password)
	{
		// lakukan query untuk mencocokkan data
		$hasil = mysql_query("SELECT * FROM $tabel WHERE username='$username' AND password='$password'");
		$q = mysql_fetch_array($hasil);
			$_SESSION['level'] = $q['level'];	 // buat session level

		// cek baris yang dikembalikan
		if (mysql_num_rows($hasil) == 0)
			return false; // data tidak cocok
		elseif	($_SESSION['level']=='admin')
			return true; // kembalikan nilai true
	}

	// fungsi untuk membuat password secara acak
	// digunakan untuk mengirim password pada form lupa password
	function pass_acak($panjang=8)
	{
		$kar = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		// acak karakter
		srand((double)microtime() * 1000000);
		// lakukan looping sebanyak $panjang
		for ($i=0; $i<$panjang; $i++)  // default diulang sebanyak 8x
		{ 
			$nom_acak = rand() % 53; // untuk mendapatkan nomor acak, pada substr()
			$pass .= substr($kar, $nom_acak, 1); // ambil satu karakter
		}

		return $pass; // kembalikan hasil
	}

	// fungsi untuk menampilkan tanggal selarang
	function show_tgl()
	{
		// buat array nama hari
		$nama_hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		// buat array nama bulan
		$nama_bulan = array('Januari', ' Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
			'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');

		$tanggal = date('j'); // tanggal sekarang 01-31
		$hari = date('w'); // kode hari 0=minggu, 1=senin dst.
		$bulan = date('n') - 1; // dikurangi satu agar index awal 0
		$tahun = date('Y'); // tahun format 4 digit

		$hari_ini = $nama_hari[$hari]; // string hari ini
		$bulan_ini = $nama_bulan[$bulan]; // string bulan ini

		// gabungkan hasil
		$today = $hari_ini. ', '.$tanggal.' '.$bulan_ini.' '.$tahun;

		return $today; // kembali hasil
	}

	// fungsi untuk logout (menghapus session)
	function logout($nama_ses)
	{
		// jika session kosong
		if (!isset($_SESSION[$nama_ses]))
			return false; // kembalikan false
		else
		{
			// jika tidak kosong hancurkan session tersebut
			unset($_SESSION[$nama_ses]);
			session_destroy();
			return true; // kembalikan nilai true
		}
	}

?>