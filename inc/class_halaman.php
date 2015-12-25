<?php 
// cegah pengaksesan langsung dari browser
if (eregi('class_forum.php', $_SERVER['PHP_SELF'])) {
	// kembalikan ke halaman utama
	header('Location: ../index.php');
	exit; // keluar dari rutin script
}

class halaman
{
	// deklarasikan properti untuk class
	var $tabel, $page, $file, $per_halaman, $jml_data, $jml_hal, $hasil_query;
	var $record, $last_page, $sebelumnya, $berikutnya, $tampilkan_hal;

	// metode untuk memilih tabel yang digunakan
	function set_tabel($nama_tabel)
	{
		$this->tabel = $nama_tabel;
	}

	// metode untuk menentukan data yang di-post
	function set_page($halaman)
	{
		$this->page = $halaman;
	}

	// metode untuk menentukan banyaknya pesan per halaman
	function set_pph($angka)
	{
		$this->per_halaman = $angka;
		return $this->per_halaman;
	}

	// metode untuk mendapatkan jumlah record yang harus ditampilkan
	// pada query SQL. jumlah record = halaman x pesan per halaman
	function get_record()
	{
		$hasil = $this->page * $this->per_halaman;
		$this->record = $hasil;

		return $this->record;
	}

	// metode untuk melakukan query sql. digunakan untuk mendapatkan jumlah
	// data, jumlah record dan sebagainya
	function query_SQL($q = 1, $id='', $isi='', $lainnya='')
	{
		// jika parameter pertama berisi 1 lakukan query berikut
		if ($q == 1)
			$query = mysql_query("SELECT * FROM $this->tabel");
		else if ($q == 2) // jika 2 lakukan query berikut
			$query = mysql_query("SELECT * FROM $this->tabel WHERE $id='$isi'");
		else if ($q == 3) // jika 3 lakukan query berikut
			$query = mysql_query("SELECT * FROM $this->tabel WHERE $id='$isi' ORDER BY
				$lainnya DESC LIMIT $this->record, $this->per_halaman");
		else
			$query = mysql_query($q); // jika bukan 1,2 atau 3 lakukan query berikut

		$this->hasil_query = $query;

		return $this->hasil_query; // kembalikan hasil dari query
	}

	// metode untuk mendapatkan jumlah data pada database
	function get_jml_data()
	{
		$jumlah = mysql_num_rows($this->hasil_query);
		$this->jml_data = $jumlah;

		return $this->jml_data;
	}

	// metode untuk mendapatkan jumlah halaman
	function get_jml_hal()
	{
		// untuk menghitung jumlah halaman digunakan fungsi ceil
		// dimana >> Jumlah data : pesan per halaman
		// jika hasilnya koma, maka dibuatkan ke atas
		$jumlah = ceil($this->jml_data / $this->per_halaman);

		$this->jml_hal = $jumlah;

		return $this->jml_hal;
	}

	// metode untuk mendapatkan halaman
	// paling awal, sebelumnya, berikutnya dan paling akhir
	function set_hal()
	{
		// halaman terakhir kita kurangi satu karena pada nomor
		// karena nilai jml_hal besar 1 dari halaman terakhir
		$this->last_page = $this->jml_hal - 1;

		// link halaman sebelumnya didapat dengan mengurangi nilai
		// halaman sekarang(yang aktif) dengan satu
		$this->sebelumnya = $this->page - 1;

		// link halaman berikutnya didapat dengan menambahkan nilai
		// halaman sekarang dengan satu
		$this->berikutnya = $this->page + 1;
	}

	// metode untuk menampilkan halaman
	function show_page($URL)
	{
		// jika jumlah halaman lebih dari satu tampilkan selain itu jangan
		if ($this->jml_hal > 1) {
			// jika halaman sekarang - (paling awal) jangan tampilkan link
			// first dan before ganti dengan tulisan biasa(warna abu-abu)
			if ($this->page == 0) {
				$first = "<font color='#cccccc'><< First</font>";
				$back = "<font color='#cccccc'>< Before</font>";
			}
			else
			{
				$first = "<a href='$URL&page=0'><< First</a>";
				$back = "<a href='$URL&page=$this->sebelumnya'>< Before</a>";
			}

			// jika halaman sekarang sama dengan nilai terakhir
			// jangan tampilkan link last dan next
			if ($this->page == $this->last_page) {
				$last = "<font color='#cccccc'>Last >></font>";
				$next = "<font color='#cccccc'>Next ></font>";
			}
			else
			{
				$last = "<a href='$URL&page=$this->last_page'>Last >></a>";
				$next = "<a href='$URL&page=$this->berikutnya'>Next ></a>";
			}

			// tampilkan
			$halaman = "$first &nbsp $back &nbsp\n";

			// gunakan looping untuk menampilkan setiap nomor halaman
			for ($i=0; $i<$this->jml_hal; $i++) { 
				// jika nomor halaman sama dengan halaman yang sedang
				// dibuka tebalkan angka tersebut dan hapus link
				// nomor halaman kita tambah 1 agar nomor awal todak 0
				if ($i == $this->page)
					$halaman .= " <font color='#cccccc'><b>".intval($i + 1)."</b></font>";
				else
					$halaman .= " <a href='$URL&page=$i'>".intval($i + 1)."</a> \n";
			}

			$halaman .= "&nbsp $next &nbsp $last\n";

			// output dari halaman jika jumlah halamannya lebih dari satu
			// kurang lebih seperti berikut, dimana x adalah nomor halaman
			// << First < Before x x x Next > Last >>
		}
		else
			$halaman = ''; // artinya halaman yang ada hanya satu

		$this->tampilkan_hal = $halaman;

		// kembalikan nilai karena akan dicetak ke layar
		return $this->tampilkan_hal;
	}
} // akhir dari class halaman
?>