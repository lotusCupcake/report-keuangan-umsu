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
                        <form action="/tunggakan" method="POST">
                            <div class="col-md-3">
                                <select class="form-control select" name="tahunAjar">
                                    <option>Tahun Ajar</option>
                                    <option value="20211">2021 Ganjil</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select" name="tahunAngkatan">
                                    <option>Tahun Angkatan</option>
                                    <?php  for ($i=2016; $i <= date("Y"); $i++) :?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select" name="tahap">
                                    <option>Tunggakan Tahap</option>
                                    <?php  for ($i=1; $i <= 4; $i++) :?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                    <?php endfor ?>
                                </select>
                            </div>     
                            <ul class="panel-controls" >
                                <button type="submit" class="btn btn-success">Cari</button>
                            </ul>
                        </form>

                        <?php if ($termYear != null && $entryYear != null && $paymentOrder != null) :?>
                            <form action="/tunggakan/cetak" method="post">
                                <input type="hidden" name="tahunAjar" value="<?= $termYear;?>">
                                <input type="hidden" name="tahunAngkatan" value="<?= $entryYear;?>">
                                <input type="hidden" name="tahap" value="<?= $paymentOrder;?>">
                                <button type="submit" class="btn btn-info">Export</button>
                            </form>
                        <?php endif ?>
                    </div>
                    <div class="panel-body col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>No Register</th>
                                        <th>NPM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Fakultas</th>
                                        <th>Nama Prodi</th>
                                        <th>Angkatan</th>
                                        <th>Nama Biaya</th>
                                        <th>Tahap</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($tunggakan)>0) :?>
                                    <?php foreach ($tunggakan as $rows) :?>
                                        <tr>
                                            <td><?= $rows->NO_REGISTER ?></td>
                                            <td><?= $rows->Npm ?></td>
                                            <td><?= $rows->NAMA_LENGKAP ?></td>
                                            <td><?= $rows->FAKULTAS ?></td>
                                            <td><?= $rows->NAMA_PRODI ?></td>
                                            <td><?= $rows->ANGKATAN ?></td>
                                            <td><?= $rows->NAMA_BIAYA ?></td>
                                            <td><?= $rows->TAHAP ?></td>
                                            <td><?= number_to_currency($rows->NOMINAL, 'IDR') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <?php else :?>
                                        <tr>
                                            <td colspan=8 style="text-align:center">Tidak ada data</td>
                                        </tr>
                                    <?php endif ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>