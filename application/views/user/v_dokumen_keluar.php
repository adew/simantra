<?php $this->load->view('template/v_header'); ?>

<div class="wrapper">
	<!-- Navbar -->
	<?php $this->load->view('template/v_navbar'); ?>
	<!-- End of Navbar -->

	<!-- Sidebar -->
	<?php $this->load->view('template/v_sidebar'); ?>
	<!-- End of Sidebar -->

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?= $title; ?></h1>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<?php if (isset($disable_button) == true) { ?>
							<blockquote class="ml-0 mt-0" style="font-size: 16px;">
								<strong>Perhatian!</strong><br>
								*<b>Maaf,</b> untuk sementara waktu akun anda tidak bisa untuk menambahkan surat. Silahkan upload file segera, agar akun anda bisa normal kembali.<br>
							</blockquote>
						<?php } ?>
					</div>
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<form action="" method="post" name="adminForm">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist">
										<tfoot>
											<tr>
												<td class="col-xs-2">
													<?php if (isset($disable_button) != true) { ?>
														<button type="button" class="btn btn-xs btn-primary" onclick="show_modal()">
															<i class="fa fa-plus"></i> Tambah Surat
														</button>
													<?php } ?>

													<?php if ($this->session->userdata('lv_user') == 'admin') { ?>
														<button type="button" class="btn btn-xs btn-success" onclick="show_modal(2)">
															<i class="fa fa-plus"></i> Tambah Surat Terbang
														</button>
													<?php } ?>
												</td>
												<td class="col-xs-5" style="width: 30%;">
													<div class="select-wrapper">
														<select class="form-control select2" name="bulan" id="filterBulan" tabindex="1" onchange="form.submit();" style="width:95%;">
															<?php
															echo '<option selected="selected" value="all"> Semua </option>';
															foreach (list_bulan() as $key => $value) {
																if ($key == $filter_bulan) {
																	echo '<option selected="selected" value="' . $key . '"> Bulan ' . $value . '</option>';
																} else {
																	echo '<option value="' . $key . '"> Bulan ' . $value . '</option>';
																}
															}
															?>
														</select>
													</div>
												</td>
												<td class="col-xs-5" style="width: 30%;">
													<!-- <div class=" select-wrapper" style="width:35%;"> -->
													<div class="select-wrapper">
														<select class="form-control select2" name="tahun" id="filterBulan" tabindex="1" onchange="form.submit();" style="width:95%;">
															<?php
															$year_array = array();
															for ($i = 2024; $i <= date('Y'); $i++) {
																$year_array[$i] = $i;
															}
															foreach ($year_array as $value) {
																if ($value == $filter_tahun) {
																	echo '<option selected="selected" value="' . $value . '"> Tahun ' . $filter_tahun . '</option>';
																} else {
																	echo '<option value="' . $value . '"> Tahun ' . $value . '</option>';
																}
															}
															?>
														</select>
													</div>
												</td>
											</tr>
										</tfoot>
									</table>
								</form>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-hover" id="table" style="width: 100%;">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">Jenis Surat</th>
											<th class="text-center">Detail</th>
											<th class="text-center">Tujuan</th>
											<th class="text-center">Tgl. Dibuat</th>
											<th class="text-center">Status</th>
											<th class="text-center">Opsi</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div>
			<!--/. container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
</div>

<!-- Modal -->
<div class="modal fade" id="modal_form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title-name"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" enctype="multipart/form-data" autocomplete="off">
					<input type="hidden" class="form-control" name="id_dok" id="id_dok">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Nomor Surat<sup class="text-red">*</sup></label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="nomor_dokumen" id="no-dokumen" placeholder="Nomor" readonly>
							<small class="help-text" id="no_dokumen-feedback"></small>
						</div>
						<label class="col-sm-auto col-form-label"> / </label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="no_dokumen2" id="no-dokumen2">
							<small class="help-text" id="no_dokumen2-feedback"></small>
						</div>
						<div class="col-sm-3">
							<small class="form-text text-muted">
								<span id="nb-surat" style="color: blue;">Nomor surat akan terlihat setelah di approve oleh administrator</span>
							</small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis Surat <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<select class="form-control selectpicker" name="kategori" id="kategori">
								<option selected disabled>-- Pilih --</option>
								<?php foreach ($kategori as $li) : ?>
									<option value="<?= $li['id_kategori'] ?>"><?= $li['jns_kategori']; ?></option>
								<?php endforeach; ?>
							</select>
							<small class="help-text" id="kategori-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Kepada <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<textarea class="form-control" name="tujuan_lain" id="tujuan-lain"></textarea>
							<small class="help-text" id="tujuan_lain-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Perihal <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<textarea class="form-control" name="perihal" id="perihal"></textarea>
							<small class="help-text" id="perihal-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Unit Satker <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<select class="form-control selectpicker" name="unit_satker" id="unit_satker">
								<option selected disabled>-- Pilih --</option>
								<?php foreach ($unit as $li) :
									if ($li['username'] != 'admin') { ?>
										<option value="<?= $li['id'] ?>"><?= $li['nm_user']; ?></option>
								<?php }
								endforeach; ?>
							</select>
							<small class="help-text" id="unit_satker-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Dibuat Oleh</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="pembuat" id="pembuat">
							<small class="help-text" id="pembuat-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Klasifikasi <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<select class="form-control selectpicker" name="jns_dokumen" id="jns_dokumen">
								<option selected disabled>-- Pilih --</option>
								<?php foreach ($jns_dokumen as $li) : ?>
									<option value="<?= $li['id_jns_dokumen'] ?>"><?= $li['jns_dokumen']; ?></option>
								<?php endforeach; ?>
							</select>
							<small class="help-text" id="jns_dokumen-feedback"></small>
						</div>
					</div>
					<!-- <div class="form-group row">
						<label class="col-sm-2 col-form-label">Status Dokumen <sup class="text-red">*</sup></label>
						<div class="col-sm-3">
							<select class="form-control selectpicker" name="sts_dokumen" id="sts_dokumen">
								<option selected disabled>-- Pilih --</option>
								<option value="Booking">Booking</option>
								<option value="Sent">Sent</option>
								<option value="Pending">Pending</option>
							</select>
							<small class="help-text" id="sts_dokumen-feedback"></small>
						</div>
					</div> -->
					<!-- <input type="hidden" value="Proses" name="sts_dokumen"> -->
					<div id="file-upload" class="form-group row">
						<label class="col-sm-2 col-form-label">File Upload<sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="file" id="file">
								<label class="custom-file-label" for="file">Choose file</label>
								<small class="help-text" id="file-feedback"></small>
							</div>
						</div>
					</div>
					<!-- <div class="form-group row">
						<label class="col-sm-2 col-form-label">Catatan</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="catatan" id="catatan"></textarea>
						</div>
					</div> -->
					<div class="form-group row">
						<div class="offset-4 col-sm-6">
							<button type="submit" class="btn btn-primary btn_save"></button>
							<!-- <span class="btn btn-primary btn_save" onclick="save_form()"></span> -->
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php $this->load->view('template/v_footer'); ?>
<!-- End of Footer -->
<!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script>
	var save_method = '';
	$(document).ready(function() {
		//datatables
		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [],
			'ajax': {
				'url': "<?= site_url('user/page/dokumen-keluar/list') ?>",
				'type': 'POST',
				'data': {
					bulan: "<?= $filter_bulan ?>",
					tahun: "<?= $filter_tahun ?>"
				},
			},
			'ordering': false,
			'paging': true,
			'layout': {
				'topStart': {
					'buttons': ['excel', 'pdf', 'print']
				}
			}
		});

		$('input[type="file"]').on('change', function() {
			//get the file name
			var file = $(this).val();
			var fileName = file.replace('C:\\fakepath\\', '');
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		});

		// $('#jns_dokumen').on('change', function() {
		// 	if ($(this).val() == 3) {
		// 		$('#tujuan_lain').attr('disabled', false);
		// 		$('#li_tujuan').attr('disabled', true);
		// 		$('#li_tujuan-feedback').empty();
		// 	} else {
		// 		$('#tujuan_lain').attr('disabled', true);
		// 		$('#li_tujuan').attr('disabled', false);
		// 		$('#tujuan_lain-feedback').empty();
		// 	}
		// });

		// $('#perihal').on('keypress', function() {
		// 	$(this).css('text-transform', 'uppercase');
		// });

		$('#form').on('change', 'input[type="file"]', function() {
			//get the file name
			var file = $(this).val();
			var fileName = file.replace('C:\\fakepath\\', '');
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);

			var size = $(this)[0].files[0].size / 1024;
			console.log(size);

			if ($(this)[0].files[0].type != 'application/pdf') {
				Swal.fire({
					title: 'Oops!',
					icon: 'warning',
					text: 'Format file upload tidak valid!'
				});
				$(this).next('.custom-file-label').html('Choose file');
				$(this).val('');
			} else {
				if (size > (1024 * 8)) {
					Swal.fire({
						title: 'Oops!',
						icon: 'warning',
						text: 'Ukuran file melebihi batas, maksimal 8 MB!'
					});
					$(this).next('.custom-file-label').html('Choose file');
					$(this).val('');
				}
			}
		});

		// save form
		$('#form').submit(function(evt) {
			evt.preventDefault();

			var url = '';
			if (save_method == 'add') url = '<?= site_url('user/page/dokumen-keluar/insert') ?>';
			else url = '<?= site_url('user/page/dokumen-keluar/update') ?>';

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'JSON',
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function(data) {
					if (data.status === true) {
						Swal.fire({
							title: data.title,
							text: data.text,
							icon: data.icon,
							timer: 2500,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					} else {
						$('.help-text').removeClass('text-red').empty();
						for (var i = 0; i < data.inputerror.length; i++) {
							$('#' + data.inputerror[i] + '-feedback').addClass('text-red').text(data.error[i]);
						}
					}
				}
			});
		});
		$('.date-picker').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			orientation: "bottom"
		})
	});

	function reset_form() {
		$('#form')[0].reset();
		$('.custom-file-label').text('Choose file');
		$('.help-text').removeClass('text-red').empty();

		$('input, select, textarea').attr('disabled', false);
		$('.select2').select2();
		$('.selectpicker').selectpicker('refresh');
	}

	function show_modal(val) {
		reset_form();
		save_method = 'add';
		$('#modal-title-name').text('Tambah')
		$('#no-dokumen').attr('readonly', true);
		$('#nb-surat').show();
		$('#file-upload').hide();

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'block',
			'cursor': 'pointer'
		}).text('Simpan');
		$('#tujuan_lain').attr('disabled', true);
		$('#li_tujuan').attr('disabled', true);
		if (val == 2) {
			$('#no-dokumen').attr('readonly', false);
			$('#nb-surat').hide();
		}
	}

	function sunting(id) {
		reset_form();
		save_method = 'update';
		$('#modal-title-name').text('Update');
		$('#file-upload').show();
		$('#no-dokumen').attr('readonly', true);

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'block',
			'cursor': 'pointer'
		}).text('Simpan');

		$.ajax({
			url: '<?= site_url('user/page/dokumen-keluar/get/') ?>' + id,
			type: 'GET',
			dataType: 'JSON',
			success: function(data) {
				$('#id_dok').val(data.id_dokumen);
				$('#jns_dokumen').val(data.jns_dokumen);
				// // if (data.jns_dokumen != 3) {
				// // 	$('#tujuan_lain').attr('disabled', true);
				// // 	$('#li_tujuan').val(data.unit_tujuan).change();
				// // } else {
				// // 	$('#li_tujuan').attr('disabled', true);
				// // 	$('#tujuan_lain').val(data.unit_tujuan);
				// }
				$('#perihal').val(data.perihal);
				$('#pembuat').val(data.pembuat);
				$('#lampiran').val(data.lampiran);
				$('#kategori').val(data.kategori);
				$('#no-dokumen').val(data.no_dokumen);
				$('#no-dokumen2').val(data.no_dokumen2);
				$('#tujuan-lain').val(data.unit_tujuan);
				$('#unit_satker').val(<?php $this->session->userdata('nama_user'); ?>);
				// $('.custom-file-label').text(data.file_dokumen);
				// $('#catatan').val(data.catatan);

				$('.selectpicker').selectpicker('refresh');
			}
		});
	}

	function view(id) {
		reset_form();

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'none',
			'cursor': 'none'
		});

		$.ajax({
			url: '<?= site_url('user/page/dokumen-keluar/get/') ?>' + id,
			type: 'GET',
			dataType: 'JSON',
			success: function(data) {
				$('#id_dok').val(data.id_dokumen).attr('disabled', true);
				$('#jns_dokumen').val(data.jns_dokumen).attr('disabled', true);
				if (data.jns_dokumen != 3) {
					$('#tujuan_lain').attr('disabled', true);
					$('#li_tujuan').val(data.unit_tujuan).change().attr('disabled', true);
				} else {
					$('#li_tujuan').attr('disabled', true);
					$('#tujuan_lain').val(data.unit_tujuan).attr('disabled', true);
				}
				$('#perihal').val(data.perihal).attr('disabled', true);
				$('#pembuat').val(data.pembuat).attr('disabled', true);
				$('#lampiran').val(data.lampiran).attr('disabled', true);
				$('#kategori').val(data.kategori).attr('disabled', true);
				$('#sts_dokumen').val(data.sts_dokumen).attr('disabled', true);
				$('#file').attr('disabled', true);
				$('.custom-file-label').text(data.file_dokumen);
				$('#catatan').val(data.catatan).attr('disabled', true);

				$('.selectpicker').selectpicker('refresh');
			}
		});
	}

	function hapus(id) {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus tidak bisa dikembalikan kembali!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?= site_url('user/page/dokumen-keluar/delete/') ?>" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						Swal.fire({
							title: 'Sukses',
							text: 'Dokumen telah berhasil dihapus',
							icon: 'success',
							timer: 2000,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			}
		})
	}

	function approve(id) {
		Swal.fire({
			title: 'Pengajuan nomor surat?',
			icon: "warning",
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Terima',
			cancelButtonText: 'Tolak',
			allowOutsideClick: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?= site_url('user/page/dokumen-keluar/status/') ?>",
					type: "POST",
					data: {
						id: id,
						sts_dok: 'Diterima'
					},
					dataType: "JSON",
					success: function(data) {
						Swal.fire({
							title: 'Diterima',
							text: 'Nomor telah berhasil dibuat',
							icon: 'success',
							timer: 2500,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			} else if (result.dismiss) {
				$.ajax({
					url: "<?= site_url('user/page/dokumen-keluar/status/') ?>",
					type: "POST",
					data: {
						id: id,
						sts_dok: 'Ditolak'
					},
					dataType: "JSON",
					success: function(data) {
						Swal.fire({
							title: 'Ditolak',
							text: 'Pengajuan nomor ditolak',
							icon: 'error',
							timer: 2500,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			}
		})
	}

	function verifikasi(id) {
		Swal.fire({
			title: 'Verifikasi dokumen?',
			icon: "warning",
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Terima',
			cancelButtonText: 'Tolak',
			allowOutsideClick: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?= site_url('user/page/dokumen-keluar/statusverifikasi/') ?>",
					type: "POST",
					data: {
						id: id,
						sts_dok: 'Selesai'
					},
					dataType: "JSON",
					success: function(data) {
						Swal.fire({
							title: 'Diterima',
							text: 'Dokumen diterima',
							icon: 'success',
							timer: 2500,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			} else if (result.dismiss) {
				$.ajax({
					url: "<?= site_url('user/page/dokumen-keluar/statusverifikasi/') ?>",
					type: "POST",
					data: {
						id: id,
						sts_dok: 'Diperbaiki'
					},
					dataType: "JSON",
					success: function(data) {
						Swal.fire({
							title: 'Ditolak',
							text: 'Dokumen ditolak',
							icon: 'error',
							timer: 2500,
							showConfirmButton: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			}
		})
	}
</script>