<aside class="main-sidebar sidebar-dark-primary elevation-4">

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<span class="nav-link" style="color: white;">
						<!-- <i class="fa fa-fw fa-desktop nav-icon"></i> -->
						<i><img src="<?= base_url('assets/simantra.jpg') ?>" width="50px" height="30px"></i>
						<p style="font-size: 25px;">SI-MANTRA</p>
					</span>
				</li>
				<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
				<li class="header">&nbsp;</li>
				<li class="nav-item">
					<a href="<?= site_url('page/dashboard') ?>" class="nav-link" id="dashboard">
						<i class="fa fa-fw fa-desktop nav-icon"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= site_url('user/page/dokumen-keluar') ?>" class="nav-link" id="dokumen-keluar">
						<i class="fa fa-fw fa-folder nav-icon"></i>
						<p>Penomoran Surat</p>
					</a>
				</li>

				<!-- Lv. Admin -->
				<?php $role = $this->session->userdata('lv_user');
				if ($role == 'admin') : ?>
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link">
							<i class="nav-icon fa fa-database"></i>
							<p>
								Data Master
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<!-- <li class="nav-item">
								<a href="<?= site_url('admin/page/jenis-dokumen') ?>" class="nav-link" id="jenis-dokumen">
									<p>Jenis Dokumen</p>
								</a>
							</li> -->
							<li class="nav-item">
								<a href="<?= site_url('admin/page/kategori-dokumen') ?>" class="nav-link" id="kategori-dokumen">
									<p>Jenis Surat</p>
								</a>
							</li>

							<!-- <li class="nav-item">
								<a href="<?= site_url('admin/page/jabatan') ?>" class="nav-link" id="jabatan">
									<p>Jabatan</p>
								</a>
							</li> -->
							<li class="nav-item">
								<a href="<?= site_url('admin/page/pengguna') ?>" class="nav-link" id="pengguna">
									<p>Pengguna</p>
								</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php $role = $this->session->userdata('lv_user');
				if ($role == 'roleadmin') : ?>
					<!-- Lv. User -->
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link">
							<i class="nav-icon fa fa-folder"></i>
							<p>
								Transaksi Dokumen
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="<?= site_url('user/page/dokumen-masuk') ?>" class="nav-link" id="dokumen-masuk">
									<p>Dokumen Masuk</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= site_url('user/page/dokumen-keluar') ?>" class="nav-link" id="dokumen-keluar">
									<p>Dokumen Keluar</p>
								</a>
							</li>
							<!-- <li class="nav-item">
								<a href="<?= site_url('user/page/dokumen-disposisi') ?>" class="nav-link" id="dokumen-disposisi">
									<p>Dokumen Disposisi</p>
								</a>
							</li> -->
						</ul>
					</li>

					<li class="nav-item has-treeview">
						<a href="#" class="nav-link">
							<i class="nav-icon fa fa-clipboard-list"></i>
							<p>
								Laporan
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="<?= site_url('user/page/laporan-dokumen-masuk') ?>" class="nav-link" id="laporan-dokumen-masuk">
									<p>Dokumen Masuk</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= site_url('user/page/laporan-dokumen-keluar') ?>" class="nav-link" id="laporan-dokumen-keluar">
									<p>Dokumen Keluar</p>
								</a>
							</li>
							<!-- <li class="nav-item">
								<a href="#" class="nav-link" id="report-dokumen-disposisi">
									<p>Dokumen Disposisi</p>
								</a>
							</li> -->
						</ul>
					</li>
				<?php endif; ?>

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>