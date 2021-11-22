<!-- START PAGE SIDEBAR -->
<div class="page-sidebar">
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation">
        <li class="xn-logo">
            <a href="/home">UMSU</a>
            <a href="#!" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#!" class="profile-mini">
                <img src="<?= base_url('theme/assets/images/users/no-image.jpg'); ?>" alt="John Doe" />
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="<?= base_url('theme/assets/images/users/no-image.jpg'); ?>" alt="John Doe" />
                </div>
                <div class="profile-data">
                    <div class="profile-data-name"><?= user()->username; ?></div>
                    <div class="profile-data-title"><?= user()->email; ?></div>
                </div>
            </div>
        </li>
        <li class="xn-title">Home</li>
        <li>
            <a href="/home"><span class="fa fa-home"></span><span class="xn-text">Dashboard</span></a>
        </li>
        <li class="xn-title">Jenis Laporan</li>
        <li>
            <a href="/tunggakan"><span class="fa fa-ban"></span><span class="xn-text">Tunggakan</span></a>
        </li>
        <li>
            <a href=" /pembayaran"><span class="fa fa-money"></span><span class="xn-text">Pembayaran</span></a>
        </li>
        <li class="xn-title">Setting</li>
        <li>
            <a href="/ubahTanggalTahap"><span class="fa fa-edit"></span><span class="xn-text">Ubah Tanggal Tahap</span></a>
        </li>
    </ul>
    <!-- END X-NAVIGATION -->
</div>
<!-- END PAGE SIDEBAR -->