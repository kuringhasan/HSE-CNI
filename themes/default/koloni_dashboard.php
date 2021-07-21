 <div class="box-header with-border" style="padding-left:0px;0">
      <h3 class="box-title" ><strong>Rekap Populasi</strong> 
      <a href="#" title="Perbarui Ringkasan" id="btn_refresh_ringkasan"><i class="fa fa-fw fa-refresh" style="font-size:14px;"></i></a>
      <img src="<?php echo $theme_path;?>images/loading50.gif"  style="display:none;margin-left:2px;" id="refresh_ringkasan_loader" width="12"/>
      </h3>
      
      
 </div> 
 <div class="row">
 <?php
// echo "<pre>";print_r($statistik);echo "</pre>";
 ?>

<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_populsi,0,",",".");?></h3>
      <h4><strong>Jumlah Populasi</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->
 
    
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_unverified,0,",",".");?></h3>
       <h4><strong>Jumlah Blm. Verifikasi</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-check-square-o"></i>
    </div>
    <a href="<?php echo $url_unverified;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->

<div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo number_format($rekap->jml_inactive,0,",",".");?></h3>
         <h4><strong>Jumlah Non-Aktif</strong></h4>
          
        </div>
        <div class="icon">
          <i class="fa fa-close"></i>
        </div>
        <a href="<?php echo $url_inactive;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
  </div><!-- /.row -->
  
<div class="box-header with-border" style="padding-left:0px;0">
  <h3 class="box-title" ><strong>Rekap Status Sapi</strong> 
  <a href="#" title="Perbarui Ringkasan" id="btn_refresh_ringkasan"><i class="fa fa-fw fa-refresh" style="font-size:14px;"></i></a>
  <img src="<?php echo $theme_path;?>images/loading50.gif"  style="display:none;margin-left:2px;" id="refresh_ringkasan_loader" width="12"/>
  </h3>
  
  
</div> 
 <div class="row">
 <?php
// echo "<pre>";print_r($statistik);echo "</pre>";
 ?>

<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_populsi,0,",",".");?></h3>
      <h4><strong>Pregnant</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->
 
    
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_unverified,0,",",".");?></h3>
       <h4><strong>Breed</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-check-square-o"></i>
    </div>
    <a href="<?php echo $url_unverified;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->

<div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo number_format($rekap->jml_inactive,0,",",".");?></h3>
         <h4><strong>Open</strong></h4>
          
        </div>
        <div class="icon">
          <i class="fa fa-close"></i>
        </div>
        <a href="<?php echo $url_inactive;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
    
<div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo number_format($rekap->jml_inactive,0,",",".");?></h3>
         <h4><strong>Dry</strong></h4>
          
        </div>
        <div class="icon">
          <i class="fa fa-close"></i>
        </div>
        <a href="<?php echo $url_inactive;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
  </div><!-- /.row -->