<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">

    <?= $this->include('layout/templateSidebar'); ?>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <?= $this->include('layout/templateHead'); ?>

        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            <li><a href="/home"><?= $breadcrumb[0]; ?></a></li>
            <li><a href="/detailTunggakan"><?= $breadcrumb[1]; ?></a></li>
            <li class="active"><?= $breadcrumb[2]; ?></li>
        </ul>
        <!-- END BREADCRUMB  ->getBody()-->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($validation->hasError('namaMahasiswa')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('namaMahasiswa'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/tunggakanPerMahasiswa" method="POST">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label>NPM / Nama Mahasiswa</label>
                                        <input type="text" class="form-control" value="" name="namaMahasiswa" placeholder="Type here..." />
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary"><span class="fa fa-search"></span>
                                                cari</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($prodi != null) : ?>
                            <?php if ($termYear != null) : ?>
                                <form action="/tunggakanPerMahasiswa/cetak" method="post">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export</button></ul>
                                </form>
                            <?php endif ?>
                            <?php foreach ($prodi as $prd) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= $prd ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-actions">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>No Register</th>
                                                        <th>NPM</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Angkatan</th>
                                                        <th>Nama Biaya</th>
                                                        <th>Tahap</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $total = 0;
                                                    $no = 1;
                                                    if (count($tunggakan) > 0) : ?>
                                                        <?php foreach ($tunggakan as $rows) : ?>
                                                            <?php if ($rows->NAMA_PRODI == $prd) : $total = $total + $rows->NOMINAL ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td><?= $rows->NO_REGISTER . " " . count($tunggakan) ?></td>
                                                                    <td><?= $rows->Npm ?></td>
                                                                    <td><?= $rows->NAMA_LENGKAP ?></td>
                                                                    <td><?= $rows->ANGKATAN ?></td>
                                                                    <td><?= $rows->NAMA_BIAYA ?></td>
                                                                    <td><?= $rows->TAHAP ?></td>
                                                                    <td><?= number_to_currency($rows->NOMINAL, 'IDR') ?></td>
                                                                </tr>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td colspan=7 style="text-align: center;"><strong>Total Amount</strong></td>
                                                            <td><strong><?= number_to_currency($total, 'IDR') ?></strong></td>
                                                        </tr>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan=8 style="text-align:center">Tidak ada data</td>
                                                        </tr>
                                                    <?php endif ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            <?php endforeach ?>
                        <?php else : ?>
                            <center>
                                <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
                            </center>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>