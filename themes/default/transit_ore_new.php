<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
  .btn-action{
  	text-align:center;
  }
  .col-number{
  	text-align:center;
  }

  #daily-transit-list tr th{
  	vertical-align:middle;
  	text-align:center;
  }

  #daily-transit-list tr th{
  	vertical-align:middle;
  	text-align:center;
  }

  .table tr th, table tr td{
  	font-size:12px;
  	padding:2px 0px 2px 0px;
  }

  .table tr th{
    background: #e1e1e1;
  }
</style>

<div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
          <table id="daily-transit-list" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead class="header-data">
               <tr>
                  <th style="width:25px;" class="row_no">No</th>
                  <th style="width:70px;">Tanggal</th>
                  <th style="width:50px;">Shift</th>
                  <th style="width:60px;">Entry Time</th>
                  <th style="width:60px;">Sent Time</th>
                  <th style="width:60px;">Received Time</th>
                  <th style="width:60px;">PIT</th>
                  <th style="width:210px;">Kontraktor</th>
                  <th style="width:60px;text-align:center">Ritase</th>
                  <th style="width:60px;text-align:center">Qty</th>
                  <th style="width:60px;text-align:center"></th>
               </tr>
            </thead>
            <thead>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
									<th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
</div>


<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>datatables/lang.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script>
  $(function() {
      $.LoadingOverlaySetup({
          image: "<?php echo $theme_path;?>images/loading.svg",
      });
  });
</script>
<script src="<?php echo $theme_path;?>pages/form-helper.js?dev"></script>
<script type="text/javascript">
  const dailyTransitTable = $('#daily-transit-list');

  $(() => {
    dailyTransitTable.DataTable({
  		language: dtLang,
    });
  })
</script>
