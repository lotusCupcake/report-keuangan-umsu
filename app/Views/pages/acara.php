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
        <!-- END BREADCRUMB -->

        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <!-- START PROJECTS BLOCK -->
                    <?php if (!empty(session()->getFlashdata('success'))) : ?>
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <?php echo session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('flayer')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('flayer'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraNama')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraNama'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraPenyiar')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraPenyiar'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraHari')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraHari'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraJamMulai')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraJamMulai'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraJamAkhir')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraJamAkhir'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('acaraStatus')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('acaraStatus'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3>Acara</h3>
                                <span>Berikut data acara UMSU FM</span>
                            </div>
                            <ul class="panel-controls" style="margin-top: 2px;">
                                <button Type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahAcara"><span class="fa fa-plus"></span> Tambah Data</button>
                            </ul>
                        </div>
                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">No</th>
                                            <th>Nama Acara</th>
                                            <th>Nama Penyiar</th>
                                            <th>Jadwal Acara</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Akhir</th>
                                            <th>Status Acara</th>
                                            <th>Status Arsip</th>
                                            <th style="text-align:center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($acara->findAll() as $row) : ?>
                                            <tr>
                                                <td style="text-align:center"><?= $no++; ?></td>
                                                <td><?= $row->acaraNama; ?></td>
                                                <td><?= $row->penyiarNama; ?></td>
                                                <td><?= $row->acaraHari; ?></td>
                                                <td><?= $row->acaraJamMulai; ?></td>
                                                <td><?= $row->acaraJamAkhir; ?>

                                                    <!-- START MODAL EDIT ACARA -->
                                                    <div class="modal fade" id="editAcara<?= $row->acaraId; ?>" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form role="form" class="form-horizontal" action="/acara/<?= $row->acaraId; ?>/edit" method="POST" enctype="multipart/form-data">
                                                                    <?= csrf_field(); ?>
                                                                    <input type="hidden" name="flayerLama" value="<?= basename($row->acaraFlayer); ?>" />
                                                                    <div class="modal-header">
                                                                        <button type=button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h3 class="modal-title" id="modalLabel"><strong>Edit</strong> Data Acara</h3>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Nama Acara</label>
                                                                            <div class="col-md-9">
                                                                                <input type="text" class="form-control" value="<?= $row->acaraNama; ?>" name="acaraNama" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Nama Penyiar</label>
                                                                            <div class="col-md-8">
                                                                                <select class="form-control select" name="acaraPenyiar">
                                                                                    <option value="88">Please Select One</option>
                                                                                    <?php foreach ($penyiar->findAll() as $mc) : ?>
                                                                                        <option value="<?= $mc->penyiarId; ?>" <?= $mc->penyiarId == $row->acaraPenyiar ? "selected" : ""; ?>><?= $mc->penyiarNama; ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Jadwal Acara</label>
                                                                            <div class="col-sm-9">
                                                                                <div class="btn-group" data-toggle="buttons">
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Senin" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Senin" <?= $row->acaraHari == "Senin" ? "checked" : ""; ?> /> Sen
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Selasa" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Selasa" <?= $row->acaraHari == "Selasa" ? "checked" : ""; ?> /> Sel
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Rabu" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Rabu" <?= $row->acaraHari == "Rabu" ? "checked" : ""; ?> /> Rab
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Kamis" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Kamis" <?= $row->acaraHari == "Kamis" ? "checked" : ""; ?> /> Kam
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Jumat" ? "active" : ""; ?> ">
                                                                                        <input type="radio" name="acaraHari" value="Jumat" <?= $row->acaraHari == "Jumat" ? "checked" : ""; ?> /> Jum
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Sabtu" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Sabtu" <?= $row->acaraHari == "Sabtu" ? "checked" : ""; ?> /> Sab
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraHari == "Minggu" ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraHari" value="Minggu" <?= $row->acaraHari == "Minggu" ? "checked" : ""; ?> /> Min
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Jam Mulai</label>
                                                                            <div class="col-md-9">
                                                                                <div class="input-group bootstrap-timepicker">
                                                                                    <input type="time" class="form-control" value=<?= $row->acaraJamMulai; ?> name="acaraJamMulai" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Jam Akhir</label>
                                                                            <div class="col-md-9">
                                                                                <div class="input-group bootstrap-timepicker">
                                                                                    <input type="time" class="form-control" value=<?= $row->acaraJamAkhir; ?> name="acaraJamAkhir" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Status Acara</label>
                                                                            <div class="col-sm-9">
                                                                                <div class="btn-group" data-toggle="buttons">
                                                                                    <label class="btn btn-default <?= $row->acaraStatus == 1 ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraStatus" value="1" <?= $row->acaraStatus == 1 ? "checked" : ""; ?> /> Tayang
                                                                                    </label>
                                                                                    <label class="btn btn-default <?= $row->acaraStatus == 0 ? "active" : ""; ?>">
                                                                                        <input type="radio" name="acaraStatus" value="0" <?= $row->acaraStatus == 0 ? "checked" : ""; ?> /> Segera
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Arsip Acara</label>
                                                                            <div class="col-md-9">
                                                                                <label class="switch">
                                                                                    <input type="checkbox" <?= ($row->acaraArsip == 0) ? "checked" : ""; ?> value="<?= $row->acaraArsip; ?>" name="acaraArsip" />
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Flayer Acara</label>
                                                                            <div class="col-md-9">
                                                                                <input onchange="previewImgEditSet(<?= $row->acaraId; ?>)" type="file" accept="image/png" class="fileinput btn-danger" name="flayer" id="flayeredit<?= $row->acaraId; ?>" data-filename-placement="inside" data-id="<?= $row->acaraId; ?>" title="<?= basename($row->acaraFlayer); ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label"></label>
                                                                            <div class="col-md-3">
                                                                                <img src="<?= $row->acaraFlayer; ?>" alt="" class="img-thumbnail img-preview<?= $row->acaraId; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-success">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL EDIT ACARA -->
                                                    <!-- START MODAL DELETE ACARA -->
                                                    <div class="modal fade" id="hapusAcara<?= $row->acaraId; ?>" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type=button class="close" data-dismiss="modal" aria-label="Close">
                                                                        <div aria-hidden="true">&times;</div>
                                                                    </button>
                                                                    <h3 class="modal-title" id="modalLabel"><strong>Hapus</strong> Data Acara</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Apakah kamu yakin ingin menghapus data acara <strong><?= $row->acaraNama; ?></strong>?</p>
                                                                    <p class="text-warning"><small>This action cannot be undone</small></p>
                                                                </div>
                                                                <form action="/acara/<?= $row->acaraId; ?>" method="post">
                                                                    <?= csrf_field(); ?>
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <div class="modal-footer">
                                                                        <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL DELETE ACARA -->
                                                    <!-- START MODAL FLAYER -->
                                                    <div class="modal fade" id="flayerAcara<?= $row->acaraId ?>" arialabelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="banner">
                                                            <div class="modal-content" style="width:85%; margin:auto">
                                                                <img src="<?= $row->acaraFlayer ?>" alt="flayer" class="img-responsive" style="width:100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL FLAYER -->
                                                </td>
                                                <td style="text-align:center"><span class="label label-form <?= $row->acaraStatus == 1 ? "label-success" : "label-warning"; ?>"><?= $row->acaraStatus == 1 ? "Tayang" : "Segera Tayang"; ?></span></td>
                                                <td style="text-align:center"><span class="label label-form <?= $row->acaraArsip == 1 ? "label-info" : "label-default"; ?>"><?= $row->acaraArsip == 1 ? "Tidak Diarsip" : "Diarsip"; ?></span></td>
                                                <td style="text-align:center">
                                                    <button Type="button" class="btn btn-primary" data-toggle="modal" data-target="#editAcara<?= $row->acaraId; ?>"><span class="fa fa-edit"></span></button>
                                                    <button Type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusAcara<?= $row->acaraId; ?>"><span class="fa fa-trash-o"></span></button>
                                                    <button Type="button" class="btn btn-default" data-toggle="modal" data-target="#flayerAcara<?= $row->acaraId ?>"><span class=" fa fa-eye"></span></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END PROJECTS BLOCK -->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

<!-- START MODAL TAMBAH ACARA -->
<div class="modal fade" id="tambahAcara" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" action="/acara" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <button type=button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="modalLabel"><strong>Tambah</strong> Data Acara</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Acara</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="acaraNama" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Penyiar</label>
                        <div class="col-md-9">
                            <select class="form-control select" name="acaraPenyiar">
                                <option value="88">Please Select One</option>
                                <?php foreach ($penyiar->findAll() as $row) : ?>
                                    <option value="<?= $row->penyiarId; ?>"><?= $row->penyiarNama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jadwal Acara</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Senin" /> Sen
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Selasa" /> Sel
                                </label>
                                <label class="btn btn-default ">
                                    <input type="radio" name="acaraHari" value="Rabu" /> Rab
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Kamis" /> Kam
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Jumat" /> Jum
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Sabtu" /> Sab
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraHari" value="Minggu" /> Min
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jam Mulai</label>
                        <div class="col-md-9">
                            <div class="input-group bootstrap-timepicker">
                                <input type="time" class="form-control" value="" name="acaraJamMulai" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Jam Akhir </label>
                        <div class="col-md-9">
                            <div class="input-group bootstrap-timepicker">
                                <input type="time" class="form-control" value="" name="acaraJamAkhir" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status Acara</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraStatus" value="1" /> Tayang
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="acaraStatus" value="0" /> Segera
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Arsip Acara</label>
                        <div class="col-md-9">
                            <label class="switch">
                                <input type="checkbox" name="acaraArsip" />
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Flayer Acara</label>
                        <div class="col-md-9">
                            <input onchange="previewImg()" type="file" accept="image/png" class="fileinput btn-danger" name="flayer" id="flayer" data-filename-placement="inside" title="Browse..." />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-3">
                            <img src="uploads/default.png" alt="" class="img-thumbnail img-preview">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END MODAL TAMBAH ACARA -->

<?= $this->endSection(); ?>