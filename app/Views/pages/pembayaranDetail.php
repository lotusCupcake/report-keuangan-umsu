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
            <li><a href="/pembayaranDetail"><?= $breadcrumb[1]; ?></a></li>
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
                <?php if ($validation->hasError('tahunAngkatan')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahunAngkatan'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahunAjar')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahunAjar'); ?>
                    </div>
                <?php endif; ?> <?php if ($validation->hasError('tahap')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Failed ! </strong><?= $validation->getError('tahap'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/pembayaranDetail" method="POST">
                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">-- Select --</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo "selected" ?>><?= $rows->Term_Year_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Angkatan</label>
                                <select class="form-control select" name="tahunAngkatan">
                                    <option value="">-- Select --</option>
                                    <?php for ($i = 2016; $i <= date("Y"); $i++) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $entryYear) echo "selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Pembayaran Tahap</label>
                                <select class="form-control select" name="tahap">
                                    <option value="">-- Select --</option>
                                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $paymentOrder) echo "selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Bank</label>
                                <select class="form-control select" name="bank">
                                    <option value="">Keseluruhan</option>
                                    <?php foreach ($listBank as $rows) : ?>
                                        <option value="<?= $rows->Bank_Acronym ?>" <?php if ($rows->Bank_Acronym == $bank) echo "selected" ?>><?= $rows->Bank_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-search"></span>
                                    Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($prodi != null) : ?>
                            <?php if ($termYear != null && $entryYear != null && $paymentOrder != null) : ?>
                                <form action="/pembayaranDetailProdi/cetak" method="post">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <input type="hidden" name="tahunAngkatan" value="<?= $entryYear; ?>">
                                    <input type="hidden" name="tahap" value="<?= $paymentOrder; ?>">
                                    <input type="hidden" name="bank" value="<?= $bank; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export Prodi</button></ul>
                                </form>
                                <span>
                                    <form action="/pembayaranDetailSeluruh/cetak" method="post">
                                        <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                        <input type="hidden" name="tahunAngkatan" value="<?= $entryYear; ?>">
                                        <input type="hidden" name="tahap" value="<?= $paymentOrder; ?>">
                                        <input type="hidden" name="bank" value="<?= $bank; ?>">
                                        <ul class="panel-controls"><button style="display: inline-block; margin-right: 11px; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                                Export Seluruh</button></ul>
                                    </form>
                                </span>
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
                                                        <th>Bank</th>
                                                        <th>Tahap</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $total = 0;
                                                    $no = 1;
                                                    if (count($pembayaran) > 0) : ?>
                                                        <?php foreach ($pembayaran as $rows) : ?>
                                                            <?php if ($rows->PRODI == $prd) : $total = $total + $rows->NOMINAL ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td><?= $rows->NO_REGISTER . " " . count($pembayaran) ?></td>
                                                                    <td><?= $rows->Npm ?></td>
                                                                    <td><?= $rows->NAMA_LENGKAP ?></td>
                                                                    <td><?= $rows->ANGKATAN ?></td>
                                                                    <td><?= $rows->NAMA_BIAYA ?></td>
                                                                    <td><?= $rows->BANK_NAMA ?></td>
                                                                    <td><?= ($rows->TAHAP == 0) ? "Lunas" : "Tahap " . $rows->TAHAP ?></td>
                                                                    <td><?= number_to_currency($rows->NOMINAL, 'IDR') ?></td>
                                                                </tr>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td colspan=8 style="text-align: center;"><strong>Total Amount</strong></td>
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
                                <lottie-player src="<?= $icon ?>" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
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