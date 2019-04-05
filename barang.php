<?php
ob_start();
include "model/m_barang.php";
$barang = new Barang($connect);

if(@$_GET['act'] == '' ) {
?>
<div class="row">
	<div class="col-lg-12">
		<h1>Barang <small>Data Barang</small></h1>
		<ol class="breadcrumb">
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped" id="datatables">
					<thead>
				<tr>
					<th>No</th>
					<th>Nama Barang</th>
					<th>Harga Barang</th>
					<th>Tanggal</th>
					<th>Gambar</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				$tampil = $barang->tampil();
				while ($data = $tampil->fetch_object()) {
				?>

				<tr>
					<td align="center"><?= $no++."."; ?></td>
					<td><?= $data->nama_barang; ?></td>
					<td><?= $data->harga; ?></td>
					<td><?= date('d F Y',strtotime($data->tgl)); ?></td>
					<td><img src="img/barang/<?= $data->gambar; ?>" width="50px"</td>
					<td align="center">

					<a id="edit_barang" data-toggle="modal" data-target="#edit" data-id="<?= $data->id_barang; ?>" data-nama="<?= $data->nama_barang; ?>" data-harga="<?= $data->harga; ?>" data-gbr="<?= $data->gambar; ?>">
					<button class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</button>
				</a>

				<a href="?page=barang&act=delete&id=<?= $data->id_barang; ?>" onclick="return confirm('Yakin Akan Menghapus')">
					<button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i>Hapus</button></a>

					<a href="./report/cetak.php?id=<?=$data->id_barang ?>" target="_blank"><button class="btn btn-primary btn-xs"><i class="fa fa-print"></i>Cetak</button></a>
				</td>
			</tr>

			<?php
		}
		 ?>
		 	</tbody>
			</table>
			</div>


			<button type="button" class="btn btn-success btn-xs" style="margin-bottom: 5px;" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>Tambah Data</button>


			<a href="./report/export_barang.php" target="blank">
			<button type="button" class="btn btn-primary btn-xs" style="margin-bottom: 5px;"><i class="fa fa-print"></i>Export Excel</button>
		</a>

		<button class="btn btn-primary btn-xx" data-toggle="modal" data-target="#cetakpdf" style="margin-bottom: 5px;"><i class="fa fa-print"></i>Cetak</button>

			
			<div id="tambah" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Tambah Data Barang</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<!-- <h4 class="text-left"></h4> -->
						</div>
					<form action="" method="POST" enctype="multipart/form-data">
							<div class="modal-body">
								<div class="form-group">
									<label class="control-label" for="nama_barang">Nama Barang</label>
									<input type="text" name="nama_barang" class="form-control" id="nama_barang" required>
								</div>
					<div class="form-group">
						<label class="control-label" for="harga">Harga Barang</label>
							<input type="text" name="harga" class="form-control" id="harga" required>
							</div>

					<div class="form-group">
						<label class="control-label" for="gambar">Gambar</label>
							<input type="file" name="gambar" class="form-control" id="gambar" required>
							</div>
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-danger">Reset</button>
							<input type="submit" name="tambah" class="btn btn-info" value="Simpan">
						</div>
						</form>
							<?php 
							if(@$_POST['tambah']) {
								$nama_barang = $connect->conn->real_escape_string($_POST['nama_barang']);
								$harga = $connect->conn->real_escape_string($_POST['harga']);
								
								$extensi = explode(".", $_FILES['gambar']['name']);
								$gambar = "brg-".round(microtime(true)).".".end($extensi);
								$sumber = $_FILES['gambar']['tmp_name'];
								$upload = move_uploaded_file($sumber,"img/barang/".$gambar);
								if($upload) {
									$barang->tambah($nama_barang, $harga, $gambar); 
									header("location:?page=barang");
								} else {
									echo "<script>alert('Upload Gambar Gagal')
									</script>";
								}

							}

								?>

					</div>
				</div>
			</div>



			<div id="edit" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Edit Data Barang</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<!-- <h4 class="text-left"></h4> -->
						</div>
					<form id="form" enctype="multipart/form-data">
							<div class="modal-body" id="modal-edit">
								<div class="form-group">
									<label class="control-label" for="nama_barang">Nama Barang</label>
									<input type="hidden" name="id_barang" id="id_barang">
									<input type="text" name="nama_barang" class="form-control" id="nama_barang" required>
								</div>
					<div class="form-group">
						<label class="control-label" for="harga">Harga Barang</label>
							<input type="text" name="harga" class="form-control" id="harga" required>
							</div>

					<div class="form-group">
						<label class="control-label" for="gambar">Gambar</label>
						<div style="padding-bottom: 5px">
							<img src="" width="70px" id="pict">
						</div>
							<input type="file" name="gambar" class="form-control" id="gambar">
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" name="edit" class="btn btn-info" value="Save">
						</div>
						</form>
					</div>
				</div>
			</div>

			<script src="asset/vendor/jquery/jquery-2.0.3.min.js"></script>
			<script type="text/javascript">
				$(document).on("click", "#edit_barang", function(){
					var idbarang = $(this).data('id');
					var namabarang = $(this).data('nama');
					var hargabarang = $(this).data('harga');
					var gambar = $(this).data('gbr');
					$("#modal-edit #id_barang").val(idbarang);
					$("#modal-edit #nama_barang").val(namabarang);
					$("#modal-edit #harga").val(hargabarang);
					$("#modal-edit #pict").attr("src", "img/barang/"+gambar);
				})

				$(document).ready(function(e) {
					$("#form").on("sumbit", (function(e)
					{
						e.preventDefault();
						$.ajax({
							url : 'model/proses_edit.php',
							type : 'POST',
							data : new FormData(this),
							contentType : false,
							cache : false,
							processData : false,
							success : function(msg) {
								$('.table').html(msg);
							}

						});
					}));
				})
			</script>


			<div id="cetakpdf" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Cetak Data Barang</h4>
						<button class="close" data-dismiss="modal">&times;</button>
						
					</div>


					<div class="modal-body">
						<form action="./report/cetak.php" method="POST" target="_blank">
							<table>
								<tr>
									<td>
										<div class="form-group">Dari Tanggal</div>
									</td>
									<td align="center" width="5%">
										<div class="form-group">:</div>
									</td>
									<td>
										<div class="form-group">
											<input type="date" class="form-control" name="tgl_a" id="tgl_a" required>
										</div>
									</td>
								</tr>

								<tr>
									<td>
										<div class="form-group">Sampai Tanggal</div>
									</td>
									<td align="center" width="5%">
										<div class="form-group">:</div>
									</td>
									<td>
										<div class="form-group">
											<input type="date" class="form-control" name="tgl_b" id="tgl_b" required>
										</div>
									</td>
								</tr>

								<tr>
									<td></td>
									<td></td>
									<td>
										<button type="sumbit" name="cetak" class="btn btn-primary btn-sm">Cetak</button>
									</td>
								</tr>
							</table>
							
						</form>
					</div>
					<div class="modal-footer">
						<a href="./report/cetak.php" target="_blank" class="btn btn-primary btn-xs">Cetak Semua Data</a>
					</div>
				</div>
			</div>
		</div>


		</div>
	</div>


<?php 
} else if(@$_GET['act'] == 'delete') {

	$gambar_awal = $barang->tampil($_GET['id'])->fetch_object()->gambar;
	unlink("img/barang/".$gambar_awal);

	$barang->hapus($_GET['id']);
	echo "<script>window.location='?page=barang';</script>";
	}
?>