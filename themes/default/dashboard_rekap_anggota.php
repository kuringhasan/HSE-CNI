 
</div><!-- ./col -->
<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Anggota Aktif</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_aktif'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>
</div><!-- ./col -->
<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Anggota No-Aktif</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_aktif1'],0,",",".")."/".number_format($rekap['jml_aktif2'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="fa fa-close"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Anggota Beku</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_beku'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-red"><i class="fa fa-power-off"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Anggota Keluar</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_keluar'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-gray"><i class="fa fa-user-plus"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Calon Anggota</span>
      <span class="info-box-number"><?php echo number_format($rekap['calon_anggota'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-blue"><i class="fa fa-male"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Laki-Laki</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_lakilaki'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-fuchsia"><i class="fa fa-female"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Perempuan</span>
      <span class="info-box-number"><?php echo number_format($rekap['jml_perempuan'],0,",",".");?></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>