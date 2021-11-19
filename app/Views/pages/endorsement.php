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
                    <?php if (!empty(session()->getFlashdata('error'))) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Maaf!</strong> <?php echo session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('endorsmentFlayer')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('endorsmentFlayer'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('endorsmentNama')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('endorsmentNama'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('endorsmentDeskripsi')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('endorsmentDeskripsi'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3>Endorsement</h3>
                                <span>Berikut data Endorsement UMSU FM</span>
                            </div>
                            <ul class="panel-controls" style="margin-top: 2px;">
                                <button Type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahEndorsement"><span class="fa fa-plus"></span> Tambah Data</button>
                            </ul>
                        </div>

                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">No</th>
                                            <th>Nama Endorsement</th>
                                            <th>Tanggal Awal</th>
                                            <th>Tanggal Akhir</th>
                                            <th width="35%">Deskripsi</th>
                                            <th width="10%" style="text-align:center">Status</th>
                                            <th style="text-align:center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($endorse->findAll() as $row) : ?>
                                            <tr>
                                                <td style="text-align:center"><?= $no++; ?></td>
                                                <td><?= $row->endorsmentNama; ?></td>
                                                <td><?= $row->endorsmentTanggalAwal; ?></td>
                                                <td><?= $row->endorsmentTanggalAkhir; ?></td>

                                                <td><?= $row->endorsmentDeskripsi; ?>

                                                    <!-- START MODAL DELETE ENDORSEMENT -->
                                                    <div class="modal fade" id="hapusEndorsement<?= $row->endorsmentId; ?>" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type=button class="close" data-dismiss="modal" aria-label="Close">
                                                                        <div aria-hidden="true">&times;</div>
                                                                    </button>
                                                                    <h3 class="modal-title" id="modalLabel"><strong>Hapus</strong> Data Endorsement</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Apakah kamu yakin ingin menghapus data endorsement <strong><?= $row->endorsmentNama ?></strong>?</p>
                                                                    <p class="text-warning"><small>This action cannot be undone</small></p>
                                                                </div>
                                                                <form action="/endorsement/<?= $row->endorsmentId; ?>" method="post">
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
                                                    <!-- END MODAL DELETE ENDORSEMENT -->

                                                    <!-- START MODAL EDIT ENDORSEMENT -->
                                                    <div class="modal fade" id="editEndorsement<?= $row->endorsmentId ?>" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form role="form" class="form-horizontal" action="/endorsement/<?= $row->endorsmentId; ?>/edit" method="POST" enctype="multipart/form-data">
                                                                    <?= csrf_field(); ?>
                                                                    <input type="hidden" name="flayerLama" value="<?= basename($row->endorsmentFlayer); ?>" />
                                                                    <div class="modal-header">
                                                                        <button type=button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h3 class="modal-title" id="modalLabel"><strong>Edit</strong> Data Endorsement</h3>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Nama Endorsement</label>
                                                                            <div class="col-md-9">
                                                                                <input type="text" class="form-control" name="endorsmentNama" value="<?= $row->endorsmentNama; ?>" required />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Tanggal Awal</label>
                                                                            <div class="col-md-9">
                                                                                <div class="input-group date " id="dp-2" data-date="<?= gmdate('d-m-Y', $row->endorsmentTanggalAwal); ?>" data-date-format="dd-mm-yyyy">
                                                                                    <input type="text" class="form-control datepicker" name="endorsmentTanggalAwal" value="<?= gmdate('d-m-Y', $row->endorsmentTanggalAwal); ?>" />
                                                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Tanggal Akhir</label>
                                                                            <div class="col-md-9">
                                                                                <div class="input-group date" id="dp-2" data-date="<?= gmdate('d-m-Y', $row->endorsmentTanggalAkhir); ?>" data-date-format="dd-mm-yyyy">
                                                                                    <input type="text" class="form-control datepicker" name="endorsmentTanggalAkhir" value="<?= gmdate('d-m-Y', $row->endorsmentTanggalAkhir); ?>" />
                                                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Deskripsi</label>
                                                                            <div class="col-md-9">
                                                                                <textarea class="form-control" name="endorsmentDeskripsi" rows="20" style="resize: vertical" required><?= $row->endorsmentDeskripsi; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Flayer Endorsement</label>
                                                                            <div class="col-md-9">
                                                                                <input onchange="previewImgEditSet(<?= $row->endorsmentId; ?>)" type="file" accept="image/png" class="fileinput btn-danger" name="endorsmentFlayer" id="flayeredit<?= $row->endorsmentId; ?>" data-filename-placement="inside" data-id="<?= $row->endorsmentId; ?>" title="<?= basename($row->endorsmentFlayer); ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label"></label>
                                                                            <div class="col-md-3">
                                                                                <img src="<?= $row->endorsmentFlayer; ?>" alt="" class="img-thumbnail img-preview<?= $row->endorsmentId; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-success">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL EDIT DATA ENDORSEMENT -->

                                                    <!-- START MODAL FLAYER -->
                                                    <div class="modal fade" id="flayerEndorsement<?= $row->endorsmentId ?>" arialabelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="banner">
                                                            <div class="modal-content" style="width:85%; margin:auto">
                                                                <img src="<?= $row->endorsmentFlayer ?>" alt="flayer" class="img-responsive" style="width:100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL FLAYER -->
                                                </td>
                                                <td style="text-align:center"><span class="label label-form <?= (strtotime(date('d-m-Y h:i:s')) >= $row->endorsmentTanggalAwal && strtotime(date('d-m-Y h:i:s')) <= $row->endorsmentTanggalAkhir) ? "label-success" : "label-danger" ?>"><?= (strtotime(date('d-m-Y h:i:s')) >= $row->endorsmentTanggalAwal && strtotime(date('d-m-Y h:i:s')) <= $row->endorsmentTanggalAkhir) ? "Tayang" : "Dihentikan" ?></span></td>
                                                <td style="text-align:center">
                                                    <button Type="button" class="btn btn-primary" data-toggle="modal" data-target="#editEndorsement<?= $row->endorsmentId ?>"><span class="fa fa-edit"></span></button>
                                                    <button Type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusEndorsement<?= $row->endorsmentId ?>"><span class="fa fa-trash-o"></span></button>
                                                    <button Type="button" class="btn btn-default" data-toggle="modal" data-target="#flayerEndorsement<?= $row->endorsmentId ?>"><span class="fa fa-eye"></span></button>
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

<!-- START MODAL TAMBAH ENDORSEMENT -->
<div class="modal fade" id="tambahEndorsement" arialabelledby="staticBackdropLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" class="form-horizontal" method="POST" action="/endorsement" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <button type=button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="modalLabel"><strong>Tambah</strong> Data Endorsement</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Endorsement</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="endorsmentNama" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tanggal Awal</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="dp-2" data-date="17-08-2021" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control datepicker" value="17-08-2021" name="endorsmentTanggalAwal" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tanggal Akhir</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="dp-2" data-date="17-08-2021" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control datepicker" value="17-08-2021" name="endorsmentTanggalAkhir" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Deskripsi</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="endorsmentDeskripsi" rows="20" style="resize: vertical"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Flayer endorsement</label>
                        <div class="col-md-9">
                            <input onchange="previewImg()" type="file" accept="image/png" class="fileinput btn-danger" name="endorsmentFlayer" id="flayer" data-filename-placement="inside" title="Browse..." />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-3">
                            <img src="endorsements/endorsement.png" alt="" class="img-thumbnail img-preview">
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
<!-- END MODAL TAMBAH DATA ENDORSEMENT -->

<?= $this->endSection(); ?>