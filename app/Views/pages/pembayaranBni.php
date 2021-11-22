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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form action="/pembayaranBni" method="POST">
                            <div class="col-md-3">
                                <select class="form-control select" name="tahunAjar">
                                    <option value="0">-- Select Tahun Ajar --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select" name="tahunAngkatan">
                                    <option value="0">-- Tahun Angkatan --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select" name="tahap">
                                    <option value="0">-- Pembayaran Tahap --</option>
                                </select>
                            </div>
                            <ul class="panel-controls">
                                <button type="submit" class="btn btn-success">Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>