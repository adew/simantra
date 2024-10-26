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
				<!-- Info boxes -->
				<div class="row">

					<div class="col-12 col-sm-6">
						<div class="info-box mb-3">
							<span class="info-box-icon bg-gradient-danger"><i class="fa fa-paper-plane"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Jumlah Surat Keluar</span>
								<h2 class="info-box-number"><?= number_format($dok_keluar) ?></h2>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-12 col-sm-6">
						<div class="info-box mb-3">
							<span class="info-box-icon bg-gradient-success"><i class="fa fa-users"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Pembuat Surat</span>
								<h2 class="info-box-number"><?= number_format($pegawai) ?></h2>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="container-chart"></div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- /.row -->
				<!-- <div class="row">
					<div class="col-12">
						<div class="alert alert-info" role="alert">
							<h4 class="alert-heading">Selamat Datang</h4>
							<p>Aww yeah, you successfully read this important alert message.</p>
						</div>
					</div>
				</div> -->
			</div>
			<!--/. container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
</div>


<!-- Footer -->
<?php $this->load->view('template/v_footer'); ?>
<!-- End of Footer -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
	Highcharts.chart('container-chart', {
		chart: {
			type: 'column'
		},
		tooltip: {
			enabled: false
		},
		exporting: {
			enabled: false
		},
		title: {
			text: 'Statistik Surat Keluar ' + <?= date('Y') ?>
		},
		yAxis: {
			title: {
				text: 'Jumlah Surat'
			}
		},
		xAxis: {
			title: false,
			categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
			crosshair: true
		},
		credits: {
			enabled: false
		},
		legend: {
			layout: 'vertical',
			align: 'center',
			verticalAlign: 'middle'
		},
		plotOptions: {
			series: {
				label: {
					connectorAllowed: false
				},
			}
		},

		series: [{
				showInLegend: false,
				color: '#c2c7d0',
				data: [
					<?php
					$series = array();
					foreach ($bulan as $kbulan => $vbulan) {
						if (isset($jumlah[$kbulan])) {
							$series[] = (int) $jumlah[$kbulan];
						} else {
							$series[] = 0;
						}
					}
					echo implode(',', $series);
					?>
				],
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					y: 0, // 10 pixels down from the top
					style: {
						fontSize: '20px',
						fontFamily: 'helvetica, arial, sans-serif',
						textShadow: false,
						fontWeight: 'normal'

					}
				}
			}

		],

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}

	});
</script>