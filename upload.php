<?php
$bulanarray = array(
	"01"=>"Januari",
	"02"=>"Februari",
	"03"=>"Maret",
	"04"=>"April",
	"05"=>"Mei",
	"06"=>"Juni",
	"07"=>"Juli",
	"08"=>"Agustus",
	"09"=>"September",
	"10"=>"Oktober",
	"11"=>"Nopember",
	"12"=>"Desember"
);
$id_tgl = explode("-", $_POST['tanggal']);
$tgl_accara = $id_tgl[2]."-".$bulanarray[$id_tgl[1]]."-".$id_tgl[0];

$target_dir = "reading/assets/books/";
echo $new_name = str_replace(" ","_",ucwords($_POST['undangan_name']))."_".$tgl_accara.".pdf";
// $target_file = $target_dir . basename($_FILES["file_undangan"]["name"]);
$target_file = $target_dir.$new_name;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["file_undangan"]["size"] > 3000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "pdf" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo '<meta content="0; url=index.php?gagal=1" http-equiv="refresh">';
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file_undangan"]["tmp_name"], $target_file)) {
    echo "<meta http-equiv='refresh' content='0;url=index.php?tanggal=".$_POST['tanggal']."&title=".urlencode(ucwords($_POST['undangan_name']))."'>";
  } else {
    echo '<meta content="0; url=index.php?gagal=1" http-equiv="refresh">';
  }
}
?>