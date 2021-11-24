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
            <li class="active"><?= $breadcrumb[1]; ?></li>
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
                <?php if ($validation->hasError('tahunAjar')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahunAjar'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahunAngkatan')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahunAngkatan'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahap')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahap'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahapTanggalAwal')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahapTanggalAwal'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahapTanggalAkhir')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahapTanggalAkhir'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php if ($termYear != null && $entryYear != null && $paymentOrder != null && $startDate != null && $endDate != null) : ?>
                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <input class="form-control" name="tahunAjar" value="<?= $termYear; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Angkatan</label>
                                <input class="form-control" name="tahunAngkatan" value="<?= $entryYear; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Pembayaran Tahap</label>
                                <input class="form-control" name="tahap" value="<?= $paymentOrder; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Awal</label>
                                <input class="form-control" name="tahap" value="<?= $startDate; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Akhir</label>
                                <input class="form-control" name="tahap" value="<?= $endDate; ?>">
                            </div>
                        <?php else : ?>
                            <form autocomplete="off" class="form-horizontal" action="/ubahNonKedokteran" method="POST">
                                <div class="col-md-2">
                                    <label>Tahun Ajar</label>
                                    <select class="form-control select" name="tahunAjar">
                                        <option value="">-- Select --</option>
                                        <?php foreach ($listTermYear as $rows) : ?>
                                            <option value="<?= $rows->Term_Year_Id ?>"><?= $rows->Term_Year_Name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Tahun Angkatan</label>
                                    <select class="form-control select" name="tahunAngkatan">
                                        <option value="">-- Select --</option>
                                        <?php for ($i = 2016; $i <= date("Y"); $i++) : ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Pembayaran Tahap</label>
                                    <select class="form-control select" name="tahap">
                                        <option value="">-- Select --</option>
                                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Tanggal Awal</label>
                                    <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control datepicker" value="" name="tahapTanggalAwal" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Tanggal Akhir</label>
                                    <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control datepicker" value="" name="tahapTanggalAkhir" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <ul class="panel-controls">
                                    <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-arrow-circle-right"></span>
                                        Proses</button>
                                </ul>
                            </form>
                        <?php endif ?>
                    </div>
                    <div class="panel-body col-md-12">
                        <center>
                            <lottie-player src="<?= $icon; ?>" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>