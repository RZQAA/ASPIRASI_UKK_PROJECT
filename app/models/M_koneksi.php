<?php
class Koneksi {
    public $koneksi; 

    public function __construct() {
        $this->koneksi = mysqli_connect("localhost", "root", "", "sarana_aspirasi");

        if (!$this->koneksi) {
            die("Database error: " . mysqli_connect_error());
        }
    }
}


$koneksi = new Koneksi(); 
?>