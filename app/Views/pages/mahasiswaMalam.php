<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">

    <?= view('layout/templateSidebar', ['menus' => $menu]); ?>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <?= $this->include('layout/templateHead'); ?>

        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            <li><a href="/home"><?= $breadcrumb[0]; ?></a></li>
            <li><a href="/mahasiswaMalam"><?= $breadcrumb[1]; ?></a></li>
            <li><a href="active"><?= $breadcrumb[2]; ?></a></li>
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
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" action="/mahasiswaMalam" method="POST">
                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">Semua Fakultas</option>
                                    <?php foreach ($fakultasFilter as $rows) : ?>
                                        <option value="<?= $rows->Faculty_Acronym ?>" <?php if ($rows->Faculty_Acronym == $filter) echo " selected" ?>><?= $rows->Faculty_Name ?></option>
                                    <?php endforeach ?>
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
                            <ul class="panel-controls">
                                <button style="display: inline-block; margin-top: 11px;" type="submit" class="btn btn-success"><span class="fa fa-search"></span>
                                    Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if ($mahasiswaMalam != null) : ?>
                            <?php if ($termYear != null) : ?>
                                <form action="/mahasiswaMalam/cetak" method="post">
                                    <input type="hidden" name="fakultas" value="<?= $filter; ?>">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <ul class="panel-controls"><button style="display: inline-block; margin-top:3px; margin-bottom: 18px;" type="submit" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                            Export</button></ul>
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Mahasiswa KRS Aktif (Malam)</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions datatable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>NPM</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Prodi</th>
                                                    <th>Kelas</th>
                                                    <th>Tahun Ajaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($mahasiswaMalam as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row->NPM; ?></td>
                                                        <td><?= $row->NAMA_LENGKAP; ?></td>
                                                        <td><?= $row->PRODI; ?></td>
                                                        <td><?= $row->KELAS; ?></td>
                                                        <td><?= $row->TAHUN_SEMESTER; ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <center>
                                <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_qozq0stb.json" background="transparent" speed="1" style="width: 100%; height: 600px;" loop autoplay></lottie-player>
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