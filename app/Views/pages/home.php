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
            <!-- START WIDGETS -->

            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty(session()->getFlashdata('success'))) : ?>
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <?php echo session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty(session()->getFlashdata('error'))) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <?php echo session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('streamAdd')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('streamAdd'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('livechatAdd')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('livechatAdd'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('whatsappAdd')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('whatsappAdd'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('settingLogoApp')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('settingLogoApp'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('settingLogoRuangdengar')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('settingLogoRuangdengar'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('settingLogoHomescreen')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('settingLogoHomescreen'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('settingFlayerDefaultsiaran')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Failed ! </strong><?= $validation->getError('settingFlayerDefaultsiaran'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-3">
                    <!-- START WIDGET SLIDER -->
                    <div class="widget widget-default widget-carousel">
                        <div class="owl-carousel" id="owl-example">
                            <div>
                                <div class="widget-title">Acara Aktif</div>
                                <div class="widget-subtitle">Jumlah per Minggu</div>
                                <div class="widget-int"><?= $acara->where(['acaraStatus' => 1, 'acaraArsip' => 1])->countAllResults(); ?></div>
                            </div>
                            <div>
                                <div class="widget-title">Acara Segera</div>
                                <div class="widget-subtitle">Jumlah per Minggu</div>
                                <div class="widget-int"><?= $acara->where(['acaraStatus' => 0, 'acaraArsip' => 1])->countAllResults(); ?></div>
                            </div>
                            <div>
                                <div class="widget-title">Acara Non aktif</div>
                                <div class="widget-subtitle">Jumlah per Minggu</div>
                                <div class="widget-int"><?= $acara->where(['acaraArsip', 0])->countAllResults(); ?></div>
                            </div>
                        </div>
                        <div class="widget-controls">
                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                        </div>
                    </div>
                    <!-- END WIDGET SLIDER -->
                </div>
                <div class="col-md-3">
                    <!-- START WIDGET MESSAGES -->
                    <div class="widget widget-default widget-item-icon" onclick="location.href='#!';">
                        <div class="widget-item-left">
                            <span class="fa fa-users"></span>
                        </div>
                        <div class="widget-data">
                            <div class="widget-int num-count"><?= $jumlah_penyiar->where(['penyiarStatus' => 1])->countAllResults(); ?></div>
                            <div class="widget-title">Penyiar</div>
                            <div class="widget-subtitle">di UMSU FM</div>
                        </div>
                        <div class="widget-controls">
                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                        </div>
                    </div>
                    <!-- END WIDGET MESSAGES -->
                </div>
                <div class="col-md-3">
                    <!-- START WIDGET REGISTRED -->
                    <div class="widget widget-default widget-item-icon" onclick="location.href='#!';">
                        <div class="widget-item-left">
                            <span class="fa fa-inbox"></span>
                        </div>
                        <div class="widget-data">
                            <div class="widget-int num-count"><?= $jumlah_endors->where(['endorsementNama', 1])->countAllResults(); ?></div>
                            <div class="widget-title">Endorsement</div>
                            <div class="widget-subtitle">di UMSU FM</div>
                        </div>
                        <div class="widget-controls">
                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                        </div>
                    </div>
                    <!-- END WIDGET REGISTRED -->
                </div>
                <div class="col-md-3">
                    <!-- START WIDGET CLOCK -->
                    <div class="widget widget-danger widget-padding-sm">
                        <div class="widget-big-int plugin-clock">00:00</div>
                        <div class="widget-subtitle plugin-date">Loading...</div>
                        <div class="widget-controls">
                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
                        </div>
                        <div class="widget-buttons widget-c3">
                            <div class="col">
                                <a href="#!"><span class="fa fa-clock-o"></span></a>
                            </div>
                            <div class="col">
                                <a href="#!"><span class="fa fa-bell"></span></a>
                            </div>
                            <div class="col">
                                <a href="#!"><span class="fa fa-calendar"></span></a>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGET CLOCK -->
                </div>
            </div>
            <!-- END WIDGETS -->

            <!-- START LINK AND IMAGE APLIKASI -->
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-colorful">
                        <form action="/streamEdit/<?= $setting->findAll()[0]->configId; ?>/edit" method="post">
                            <?= csrf_field(); ?>
                            <div class="panel-heading">
                                <h3 class="panel-title">Stream Address</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <input type="text" name="streamAdd" class="form-control" value="<?= $setting->findAll()[0]->configValue; ?>" />
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="btnStreamEdit" value="saveStreamAdd" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-colorful">
                        <form id="livechatEdit" action="/livechatEdit/<?= $setting->findAll()[2]->configId; ?>/edit" method="post">
                            <?= csrf_field(); ?>
                            <div class="panel-heading">
                                <h3 class="panel-title">Live Chat Address</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <input type="text" name="livechatAdd" class="form-control" value="<?= $setting->findAll()[2]->configValue; ?>" />
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="btnLivechatEdit" value="saveLivechatAdd" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-colorful">
                        <form id="whatsappEdit" action="/whatsappEdit/<?= $setting->findAll()[3]->configId; ?>/edit" method="post">
                            <?= csrf_field(); ?>
                            <div class="panel-heading">
                                <h3 class="panel-title">Whatsapp Address</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <input type="text" name="whatsappAdd" class="form-control" value="<?= $setting->findAll()[3]->configValue; ?>" />
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="btnWhatsappEdit" value="saveWhatsappAdd" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-colorful">
                        <form role="form" action="/logoAppEdit/<?= $setting->findAll()[1]->configId; ?>/edit" method="POST" enctype="multipart/form-data">
                            <? csrf_field(); ?>
                            <input type="hidden" name="logoAppLama" value="<?= basename($setting->findAll()[1]->configValue); ?>" />
                            <div class="panel-heading">
                                <h3 class="panel-title">Logo Aplikasi</h3>
                            </div>
                            <div class="panel-body">
                                <input onchange="previewImgEditSet(<?= $setting->findAll()[1]->configId; ?>)" type="file" accept="image/png" class="fileinput btn-danger col-md-12" name="settingLogoApp" id="flayeredit<?= $setting->findAll()[1]->configId; ?>" data-filename-placement="inside" data-id="<?= $setting->findAll()[1]->configId; ?>" title="Browse..." />
                                <div class="form-group">
                                    <label class="col-md-12 control-label"></label>
                                    <div class="col-md-12">
                                        <img src="<?= $setting->findAll()[1]->configValue; ?>" alt="" class="img-thumbnail img-preview<?= $setting->findAll()[1]->configId; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" value="editLogoApp" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-colorful">
                        <form role="form" action="/logoRuangdengarEdit/<?= $setting->findAll()[4]->configId; ?>/edit" method="POST" enctype="multipart/form-data">
                            <? csrf_field(); ?>
                            <input type="hidden" name="logoRuangdengarLama" value="<?= basename($setting->findAll()[4]->configValue); ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title">Logo Ruang Dengar</h3>
                            </div>
                            <div class="panel-body">
                                <input onchange="previewImgEditSet(<?= $setting->findAll()[4]->configId; ?>)" type="file" accept="image/png" class="fileinput btn-danger col-md-12" name="settingLogoRuangdengar" id="flayeredit<?= $setting->findAll()[4]->configId; ?>" data-filename-placement="inside" data-id="<?= $setting->findAll()[4]->configId; ?>" title="Browse..." />
                                <div class="form-group">
                                    <label class="col-md-12 control-label"></label>
                                    <div class="col-md-12">
                                        <img src="<?= $setting->findAll()[4]->configValue; ?>" alt="" class="img-thumbnail img-preview<?= $setting->findAll()[4]->configId; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" value="editLogoRuangdengar" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-colorful">
                        <form role="form" action="/logoHomescreenEdit/<?= $setting->findAll()[5]->configId; ?>/edit" method="POST" enctype="multipart/form-data">
                            <? csrf_field(); ?>
                            <input type="hidden" name="logoHomescreenLama" value="<?= basename($setting->findAll()[5]->configValue); ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title">Logo Homescreen</h3>
                            </div>
                            <div class="panel-body">
                                <input onchange="previewImgEditSet(<?= $setting->findAll()[5]->configId; ?>)" type="file" accept="image/png" class="fileinput btn-danger col-md-12" name="settingLogoHomescreen" id="flayeredit<?= $setting->findAll()[5]->configId; ?>" data-filename-placement="inside" data-id="<?= $setting->findAll()[5]->configId; ?>" title="Browse..." />
                                <div class="form-group">
                                    <label class="col-md-12 control-label"></label>
                                    <div class="col-md-12">
                                        <img src="<?= $setting->findAll()[5]->configValue; ?>" alt="" class="img-thumbnail img-preview<?= $setting->findAll()[5]->configId; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" value="editLogoHomescreen" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-colorful">
                        <form role="form" action="/flayerDefaultsiaranEdit/<?= $setting->findAll()[6]->configId; ?>/edit" method="POST" enctype="multipart/form-data">
                            <? csrf_field(); ?>
                            <input type="hidden" name="flayerDefaultsiaranLama" value="<?= basename($setting->findAll()[6]->configValue); ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title">Flayer Default Siaran</h3>
                            </div>
                            <div class="panel-body">
                                <input onchange="previewImgEditSet(<?= $setting->findAll()[6]->configId; ?>)" type="file" accept="image/png" class="fileinput btn-danger col-md-12" name="settingFlayerDefaultsiaran" id="flayeredit<?= $setting->findAll()[6]->configId; ?>" data-filename-placement="inside" data-id="<?= $setting->findAll()[6]->configId; ?>" title="Browse..." />
                                <div class="form-group">
                                    <label class="col-md-12 control-label"></label>
                                    <div class="col-md-12">
                                        <img src="<?= $setting->findAll()[6]->configValue; ?>" alt="" class="img-thumbnail img-preview<?= $setting->findAll()[6]->configId; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" value="editFlayerDefaultsiaran" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END LINK AND IMAGE APLIKASI -->
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->


<?= $this->endSection(); ?>