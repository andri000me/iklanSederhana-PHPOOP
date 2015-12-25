<?php 
// 20
class skin
{
	// deklarasikan properti
	var $tag = array();
	var $file_skin;
	var $halaman;

	// metode untuk menentukan nama tag yang diganti
	// dan string penggantinya
	function ganti_tag($namatag, $str_pengganti)
	{
		$this->tag[$namatag] = $str_pengganti;
	}

	// metode untuk menentukan file template yang digunakan
	function ganti_skin($namafile)
	{
		$this->file_skin = $namafile;
	}

	// metode untuk mengganti tampilan
	function ganti_tampilan()
	{
		// buka file template menggunakan file()
		$this->halaman = file($this->file_skin);
		// gabungkan setiap baris dengan implode()
		$this->halaman = implode("", $this->halaman);
		// gunakan looping foreach() untuk mengganti setiap tag
		foreach ($this->tag as $str_dicari => $str_baru) {
			$this->halaman = ereg_replace($str_dicari, $str_baru, $this->halaman);
		}

		echo $this->halaman; // tampilkan ke layar
	}
}
?>