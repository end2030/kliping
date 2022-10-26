<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>e-klipping</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0,initial-scale=1.0">
	<!-- Flipbook StyleSheet -->
	<link href="./flips/css/dflip.min.css" rel="stylesheet" type="text/css">
	<!-- Icons Stylesheet -->
	<link href="./flips/css/themify-icons.min.css" rel="stylesheet" type="text/css">
	<link href="./reading/assets/css/font-awesome.css" rel="stylesheet" type="text/css">
	<!-- <link href="./reading/assets/css/style.css" rel="stylesheet" type="text/css"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<?php 	
error_reporting(0);
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
if(!empty($_GET['tanggal'])){
	$pdf_file = array();
	$tgl = $_GET['tanggal'];
	$id_tgl = explode("-", $tgl);
	$tgl_cari = $id_tgl[2]."-".$bulanarray[$id_tgl[1]]."-".$id_tgl[0];
	$files = glob('reading/assets/books/*pdf');
	foreach($files as $file) {
		$str = explode("/", $file);
		$filename = $str[count($str)-1];
		$str2 =explode("_",$filename);
		$tanggal_file = str_replace(".pdf", "", $str2[count($str2)-1]);
		if ( $tgl_cari == $tanggal_file) {
			$pdf_file['filepath'][] = $file;
			$pdf_file['tgl'] = $tanggal_file;
			for ($i=0; $i < count($str2)-1 ; $i++) { 
				$pdf_file['title'][$file] .= $str2[$i]." ";
			}
		}
	}
}
?>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-9 full-height leftpdf">
				<div class="headright" id="mobile">
					<img src="./reading/assets/img/pdf_not_found.jpg" class="headimg">
					<div id="login">
						 <button type="button" class="btn btn-info" data-bs-toggle="modal"data-bs-target="#login-modal"><i class="fa fa-power-off" aria-hidden="true"></i></button>
						
					</div>
				</div>
				<?php
				if (!empty($pdf_file['filepath'])) {
					if (!empty($_GET['title'])) {
						$view = 'reading/assets/books/'.str_replace(" ","_",$_GET['title']).'_'.$tgl_cari.'.pdf';
						echo '<div class="_df_book full-height" webgl="true" backgroundcolor="grey"source="'.$view.'"id="df_manual_book"></div>';
					}else{
						?>
						<div class="col-md-9" style="height: 100%; bottom: 0px;">
							<img class="nopdf" src='./reading/assets/img/pdf_not_found.jpg' width='100px'/>
						</div>
						<?php
					}
				}else{
					?>
					<div class="col-md-9" style="height: 100%; bottom: 0px;">
						<img class="nopdf" src='./reading/assets/img/pdf_not_found.jpg' width='100px'/>
					</div>
					<?php
				}
				?>
			</div>
			<div class="col-md-3 rigthpdf">
				<div class="headright" id="desktop">
				<form role="form" action="upload.php" class="form-horizontal" method="post" id="formInput" name="formInput" enctype="multipart/form-data">
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-12 control-label">Nama File</label>
								<div class="col-md-12">
									<div class="input-icon right">
										<i class="fa"></i>
										<input type="text" class="form-control" name="undangan_name" placeholder="Contoh: Andi dan Susi" autocomplete="off" autofocus />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 control-label">Tanggal Acara</label>
								<div class="col-md-12">
									<div class="input-icon right">
										<i class="fa"></i>
										<input type="date" class="form-control" name="tanggal" placeholder="Tanggal" autocomplete="off" autofocus />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 control-label">File Upload</label>
								<small>max file size : kurang dari 2Mb</small>
								<div class="col-md-12">
									<div class="input-icon right">
										<i class="fa"></i>
										<input type="file" class="form-control" accept=".pdf" name="file_undangan" placeholder="File Undangan" autocomplete="off" autofocus />
									</div>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<div class="row">
								<div class="col-md-12" style="text-align:center;margin-top:10px;">
									<button type="submit" class="btn btn-primary right">
										<i class="fa fa-floppy-o"></i> Simpan
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="harijam"> 
					<span id="tanggalwaktu"></span><br>
					<span id="jam"></span>:<span id="menit"></span>:<span id="detik"></span> WIB
				</div>    		
				<div class="headmenu"><h4>Cari Undangan</h4></div>
				<input type="date" id="myInput" onchange="pdfSearch($(this).val())" value="<?php echo !empty($tgl) ? $tgl :''?>" />
				<?php 
					if (!empty($pdf_file['title'])) {
						echo "<center><h5>Daftar Undangan :</h5></center>";
					}
					if (!empty($_GET['gagal'])) {
						echo "<script>alert('Undangan Gagal Upload! Silahkan Ulangi Upload dengan ganti nama')</script>";
					}
				?>
				<ul id="myUL">
					<?php 
						if (!empty($pdf_file['title'])) {
							foreach ($pdf_file['title'] as $key => $value) {
								echo '<li><a href="?tanggal='.$_GET["tanggal"].'&title='.$value.'">'.$value.'</a></li>';
							} 
						}
					?>
				</ul>
				<div class="headmenu2"><h6>Statistik Pengunjung</h6></div>
				<table style="margin-left: auto; margin-right: auto;">
					<tr>
						<td>
							<div id="histats_counter"></div>
							<!-- Histats.com  START  (aync)-->
							<script type="text/javascript">var _Hasync= _Hasync|| [];
							_Hasync.push(['Histats.start', '1,4705861,4,4002,112,61,00011111']);
							_Hasync.push(['Histats.fasi', '1']);
							_Hasync.push(['Histats.track_hits', '']);
							(function() {
								var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
								hs.src = ('//s10.histats.com/js15_as.js');
								(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
							})();</script>
							<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4705861&101" alt="site hit counter" border="0"></a></noscript>
							<!-- Histats.com  END  -->
						</td>
					</tr>
				</table>
			</div>
		</div>
 </div>
 <!-- jQuery  -->
 <script src="./flips/js/libs/jquery.min.js" type="text/javascript"></script>
 <!-- Flipbook main Js file -->
 <script src="./flips/js/dflip.min.js" type="text/javascript"></script>
</body>
<!-- SignIn modal content -->
    <div id="login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <a href="document.html" class="text-success">
                        <span><img class="me-2" src="./material/src/assets/images/logo-icon.png"
                            alt="" height="18"><img
                            src="./material/src/assets/images/logo-text.png" alt=""
                            height="18"></span>
                        </a>
                    </div>
                    <form action="#" class="ps-3 pe-3 text-start">
                        <div class="mb-3">
                            <label for="emailaddress1">Email address</label>
                            <input class="form-control" type="email" id="emailaddress1"
                                required="" placeholder="john@deo.com">
                        </div>
                        <div class="mb-3">
                            <label for="password1">Password</label>
                            <input class="form-control" type="password" required=""
                                id="password1" placeholder="Enter your password">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input"
                                    id="customCheck2">
                                <label class="form-check-label"
                                    for="customCheck2">Remember me</label>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-rounded btn-primary" type="submit">Sign
                            In</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
</html>
<script type="text/javascript">
	window.setTimeout("waktu()", 1000);

	function waktu() {
		var waktu = new Date();
		setTimeout("waktu()", 1000);
		document.getElementById("jam").innerHTML = waktu.getHours();
		document.getElementById("menit").innerHTML = waktu.getMinutes();
		document.getElementById("detik").innerHTML = waktu.getSeconds();
	}
	var tw = new Date();
	if (tw.getTimezoneOffset() == 0) (a=tw.getTime() + ( 7 *60*60*1000))
		else (a=tw.getTime());
	tw.setTime(a);
	var tahun= tw.getFullYear ();
	var hari= tw.getDay ();
	var bulan= tw.getMonth ();
	var tanggal= tw.getDate ();
	var hariarray=new Array("Minggu,","Senin,","Selasa,","Rabu,","Kamis,","Jum'at,","Sabtu,");
	var bulanarray=new Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
	document.getElementById("tanggalwaktu").innerHTML = hariarray[hari]+" "+tanggal+" "+bulanarray[bulan]+" "+tahun;
	function Book(variable){
		var s = document.getElementById(variable);
		var hash = window.location.hash
      // window.location.reload()
      // console.log(s.hash());
      s.setAttribute('source',"reading/assets/books/"+hash+".pdf");
  }   
  function pdfSearch(tgl) {
  	location.href="?tanggal="+tgl;
  }
  function pdfSearchView(tgl,title) {
  	location.href="?tanggal="+tgl+"&title="+title;
  }
</script>
<style type="text/css">
	@media (min-width: 768px){
		#mobile {
			display: none;
		}
	}
	@media (max-width: 768px){
		#desktop {
			display: none;
		}
	}
	#login{
		margin-top: -3px;
		float: right;
		width: 37px;
		height: 40px;
	}
	#login i{
		margin-top: 20px;
		color: antiquewhite;
	}
	.nopdf{
		margin-left: 50%;
		margin-top: 30%;
	}
	.harijam{
		text-align: -webkit-center;
		padding-top: 10px;
	}
	.container{
		background:#fff;
	}
	body{
		background: #ea0882;
	}
	.headmenu{
		background: #ea0882;
		margin-top: 10px;
		height: 40px;
		border-top-right-radius: 26px;
		padding-left: 20px;
	}

	.headmenu2{
		margin-top: 10px; 
		margin-bottom: 10px;
		background: #ea0882;
		height: 40px;
		border-top-left-radius: 26px;
		padding-left: 20px;

	}
	.headmenu h4{
		padding-top: 11px;
		color: antiquewhite;
	}
	.headmenu2 h4{
		padding-top: 11px;
		color: antiquewhite;
	}
	.leftpdf{
		top: 0px;
		padding-bottom:30px; 
		/*width: 75%;*/
		max-height: 100vh;
		left: 0px;
	}
	.rigthpdf{
		top: 0px;
		/*width: 25%;*/
		/*position: absolute;*/
		right: 0px;
		max-height: 100vh;
    	overflow-y: scroll;
	}
	.headright{
		width: 100%;
		padding: 5px;
		background: #ea0882;
	}
	.headimg{
		margin-top: 12px;
		padding-left: 10px;
		width: 170px;
	}
	html, body {
		height: 100%;
	}

	.full-height {
		height: 100%;
	}
	body {
		/*height: 100vw;*/
		/*overflow-y: hidden;*/
		/*overflow-x: hidden;*/
	}

	* {
		box-sizing: border-box;
	}

	#myInput {
		background-image: url('/css/searchicon.png');
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		font-size: 16px;
		padding: 12px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
	}

	#myUL {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}

	#myUL li a {
		border: 1px solid #ddd;
		margin-top: -1px; /* Prevent double borders */
		background-color: #26c6da;
		padding: 12px;
		text-decoration: none;
		font-size: 14px;
		color: black;
		display: block
	}
	.input-icon.right{
		margin-top:-20px;
	}
	.form-group{
		color:#fff;
	}
	#myUL li a:hover:not(.header) {
		background-color: #ea0882;
	}
</style>
