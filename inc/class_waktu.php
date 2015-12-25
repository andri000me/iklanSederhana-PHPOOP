<?php 
class waktu
{
	// deklarasikan properti
	var $date, $bulan, $year, $jml_hari, $is_kabisat;

	// metode untuk memberi nilai tanggal, bulan, tahun, jumlah
	// hari dalam bulan sekarang
	function set_date()
	{
		$this->date = date('d'); // tanggal 01-28/29/30/31
		$this->bulan = date('m'); // bulan 01-12
		$this->year = date('Y'); // tahun 4 digit
		$this->jml_hari = date('t'); // jumlah hari
		$this->is_kabisat = date('Y') % 4; // jika 0 = kabisat selain itu tidak
	}

	// metode untuk menentukan tanggal dikurangi, ditambah atau tetap
	// 0 = mengurangi, 1 = menambah, 2 = tetap
	function set_mode($pilihan)
	{
		$this->mode = $pilihan;
	}

	// metode untuk menentukan output tampilan dan angka manipulasi
	// format 0 = tanggal biasa, 1 = detik(timestamp)
	function set_tgl($format = 0, $angka = 1)
	{
		// jika lebih dari 31 keluar dari rutin program
		if ($angka > 31)
			return "Error: Angka terlalu tinggi(max. 31), keluar dari fungsi.";

		$bulan = $this->bulan;
		$thn = $this->year;

		if ($this->mode == 0) // jika mode pengurangan
		{
			$tgl = $this->date - $angka; // tanggal dikurangi

			if ($tgl <= 0) // jika kurang/sama dengan nol, maka bulan sebelumnya
			{
				$bulan = $this->bulan - 1; // kurangi bulan dengan 1

				if ($this->jml_hari == 30) // bulan yang tanggalnya sampai 30
					$tgl = (31 + $this->date) - $angka; // tanggal pada bulan yang tanggalnya 31
				elseif ($this->bulan == 1) // januari
				{
					$tgl = (31 + $this->date) - $angka; // tanggal pada bulan DESEMBER
					$bulan = 12; // desember
					$thn = $this->year - 1; // tahun dikurangi 1
				}
				elseif ($this->bulan == 3) // maret
				{
					if ($this->is_kabisat == 0) // jika kabisat
						$tgl = (29 + $this->date) - $angka; // tanggal pada februari
					else
						$tgl = (28 + $this->date) - $angka; // tanggal pada februari non kabisat
				}
				elseif ($this->bulan == 8) // Agustus
					$tgl = (31 + $this->date) - $angka; // tanggal pada bulan juli
				elseif ($this->bulan == 2) // februari
					$tgl = (31 + $this->date) - $angka; // tanggal pada februari
				else
					$tgl = (30 + $this->date) - $angka; // tanggal pada bulan yang tanggalnya 30
			}
		}
		elseif ($this->mode == 1) 
		{
			$tgl = $this->date + $angka; // angka ditambah

			if ($this->jml_hari == 31 && $bulan != 12) // bulan bertanggal 31 selain desember
			{
				if ($tgl > 31 ) // jika melebihi tanggal 31
				{
					$tgl = ($this->date - 31) + $angka; // kurangi 31 dan tambah sesuai parameter
					$bulan = $bulan + 1; // bulan ditambah 1
				}
			}
			elseif ($this->jml_hari == 30) // bulan bertanggal 30
			{
				if ($tgl > 30) {
					$tgl = ($this->date - 31) + $angka;
					$bulan = $bulan + 1;
				}
			}
			else if ($bulan == 12) // bulan desember
			{
				if ($tgl > 31) // jjika melebihi tanggal 31
				{
					$tgl = ($this->date - 31) + $angka;
					$bulan = 1; // bulan kita set januari
					$thn = $thn + 1; // tahun kita tambah satu
				}
			}
			else if ($bulan == 2) // jika bulan februari
			{
				if ($this->is_kabisat == 0) // jika kabisat
				{
					if ($tgl > 29) // jika lebih dari tanggal 29
					{
						$tgl = ($this->date - 29) + $angka; // tanggal = pemenbahan hari
						$bulan = $bulan + 1; // bulan ditambah 1
					}
				}
				else
					if ($tgl > 28) // jika non kabisat
					{
						$tgl = ($this->date - 28) + $angka;
						$bulan = $bulan + 1;
					}
			}
		}
		else
			$tgl = $this->date; // tetap

		if (strlen($tgl) < 2)
			$tgl = "0$tgl"; // agar berformat 2 digit

		if (strlen($bulan) < 2)
			$bulan = "0$bulan"; // agar berformat 2 digit

		// cek format yang diinginkan
		if ($format == 0 || $format != 1)
			$this->hasil = "$tgl - $bulan - $thn"; // tanggal biasa
		else
			$this->hasil = mktime(0, 0, 0, $bulan, $tgl, $thn); // detik

		return $this->hasil; // kembalikan hasil
	} // akhir dari metode set_tgl()
} // akhir dari class
?>