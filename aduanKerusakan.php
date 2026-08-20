<?php include( "contentsConUser.php" );
  $username = $_SESSION['username'];
  $myquery = "SELECT * FROM dt_all_user WHERE username='$username'";
  $d = mysqli_query($con, $myquery)or die( mysqli_error($con));
  $dtUser = mysqli_fetch_assoc($d);
  $idAdm = $dtUser['username'];
  ?>
<!DOCTYPE html>
<html lang="en">
  <?php include( "headUser.php" );?> 
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" style="min-height: 223.667px;">
      <?php 
        include( "navtopUser.php" );
        include( "navSbPj.php" );
        ?> 
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <?php include( "alertUser.php" );?>
            <div class="row mb-2">
              <div class="col-sm-6">
                <h4 class="mb-0">Aduan Perbaikan</h4>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Pengajuan</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <?php include 'pagination.php';
          if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
          $keyword=$_REQUEST['keyword'];
          $reload = "aduanKerusakan.php?pagination=true&keyword=$keyword";
          
          $sql = "SELECT 
          r.id
          , r.kode_tiket
          , r.tgl_pengajuan
          , r.level_pengajuan
          , r.nm_pengaju
          , r.lokasi_kampus
          , r.lokasi_gedung
          , r.lokasi_ruang
          , r.aitem_kerusakan
          , r.tingkat_kerusakan
          , r.img_kerusakan
          , r.deskripsi_kerusakan
          , r.admin
          , r.kat_koordinator
          , r.nm_koordinator
          , r.tgl_penugasan
          , r.tgl_pra_penanganan
          , r.tgl_proses_penanganan
          , r.tgl_selesai_penanganan
          , r.status_tiket
          , r.durasi_penanganan
          , r.approval
          , r.tgl_approval
          , r.validasi_admin
          , r.tgl_validasi_admin
          , r.catatan
          , olp.nm
          , dau.username
          , dau_admin.username
          , dau_koord.username
          , olk.nm_kampus
          , olg.nm_gedung
          , oak.nm
          , otk.nm
          , ost.nm
          , oap.nm
          , ov.nm

          FROM tiket_masuk r
          
          LEFT JOIN opsi_level_pengajuan olp
          on r.level_pengajuan = olp.id
          LEFT JOIN dt_all_user dau
          on r.nm_pengaju = dau.username
          LEFT JOIN dt_all_user dau_admin
          on r.admin = dau_admin.username
          LEFT JOIN dt_all_user dau_koord
          on r.nm_koordinator = dau_koord.username
          LEFT JOIN opsi_lokasi_kampus olk
          on r.lokasi_kampus = olk.id_kampus
          LEFT JOIN opsi_lokasi_gedung olg
          on r.lokasi_gedung = olg.id_gedung
          LEFT JOIN opsi_lokasi_ruang olr
          on r.lokasi_ruang = olr.id_ruang
          LEFT JOIN opsi_aitem_kerusakan oak
          on r.aitem_kerusakan = oak.id
          LEFT JOIN opsi_tingkat_kerusakan otk
          on r.tingkat_kerusakan = otk.id
          LEFT JOIN opsi_status_tiket ost
          on r.status_tiket = ost.id
          LEFT JOIN opsi_approval oap
          on r.approval = oap.id
          LEFT JOIN opsi_validasi ov
          on r.validasi_admin = ov.id
          LEFT JOIN dt_pelaksanaan_tiket dpTiket
          on r.id = dpTiket.id_tiket
          LEFT JOIN dt_all_user dau_pelaksana
          on dpTiket.id_pelaksana = dau_pelaksana.username
                    
          WHERE (r.id LIKE '%$keyword%' OR r.kode_tiket LIKE '%$keyword%' OR r.tgl_pengajuan LIKE '%$keyword%' OR r.tgl_penugasan LIKE '%$keyword%' OR r.tgl_pra_penanganan LIKE '%$keyword%' OR r.tgl_proses_penanganan LIKE '%$keyword%' OR r.tgl_selesai_penanganan LIKE '%$keyword%' OR r.deskripsi_kerusakan LIKE '%$keyword%' OR dau_admin.username LIKE '%$keyword%' OR dau_admin.nm_person LIKE '%$keyword%' OR dau_admin.domain_person LIKE '%$keyword%' OR dau_koord.username LIKE '%$keyword%' OR dau_koord.nm_person LIKE '%$keyword%' OR dau_koord.domain_person LIKE '%$keyword%' OR dau_pelaksana.username LIKE '%$keyword%' OR dau_pelaksana.nm_person LIKE '%$keyword%' OR dau_pelaksana.domain_person LIKE '%$keyword%' OR olp.nm LIKE '%$keyword%' OR dau.username LIKE '%$keyword%' OR dau.nm_person LIKE '%$keyword%' OR dau.domain_person LIKE '%$keyword%' OR olk.nm_kampus LIKE '%$keyword%' OR olg.nm_gedung LIKE '%$keyword%' OR olr.nm_ruang LIKE '%$keyword%' OR oak.nm LIKE '%$keyword%' OR otk.nm LIKE '%$keyword%' OR oap.nm LIKE '%$keyword%' OR ov.nm LIKE '%$keyword%') AND r.nm_pengaju='$dtUser[username]' ORDER BY r.id DESC";
          
          $result = mysqli_query($con, $sql);
          }else{
          $reload = "aduanKerusakan.php?pagination=true";
          $sql = "SELECT * FROM tiket_masuk WHERE nm_pengaju = '$dtUser[username]' ORDER BY id DESC";
          $result = mysqli_query($con, $sql);
          }
          
          $rpp = 20;
          $page = isset($_GET["page"]) ? (intval($_GET["page"])) : 1;
          $tcount = mysqli_num_rows($result);
          $tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
          $count = 0;
          $i = ($page-1)*$rpp;
          $no_urut = ($page-1)*$rpp;
          ?>
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12 col-md-9 col-lg-10 mb-2">
                <form method="post" action="aduanKerusakan.php">
                  <?php include ("undefined_array_key.php");?>
                  <div class="input-group">
                    <input type="search" name="keyword" class="form-control form-control-sm" placeholder="Kata kunci pencarian..." value="<?php echo $_REQUEST['keyword'];?>" required>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-sm btn-default">
                      <i class="fa fa-search"></i>
                      </button>
                      <?php
                        if($_REQUEST['keyword']<>""){
                        ?>
                      <a class="btn btn-sm btn-warning" title="Refresh" href="aduanKerusakan.php"><i class="fas fa-sync"></i> Refresh</a>
                      <?php
                        }
                        ?>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-sm-12 col-md-3 col-lg-2 mb-2">
                <a type="button" class="btn btn-info btn-sm btn-block" href="inputTiketBaru.php?page=<?php echo $page;?>"><i class="fas fa-cart-plus"></i> Ajukan Aduan Baru</a>
              </div>
            </div>
            <div class="row">
              <section class="col-sm-12 connectedSortable">
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="clearfix">
                      <h4 class="card-title float-left">Pengajuan</h4>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive-no-wrap">
                      <table class="table table-hover m-0 table-sm custom">
                        <thead class="thead-light">
                          <th width="4%" class="pl-1">No.</th>
                          <th class="text-center" width="4%"><i class="far fa-edit"></i></th>
                          <th class="text-center" width="4%"><i class="far fa-trash-alt"></i></th>
                          <th width="10%">Status Approval</th>
                          <th width="12%">Tgl. Pengajuan</th>
                          <th width="14%">Aitem Perbaikan</th>
                          <th width="34%">Lokasi Perbaikan</th>
                          <th width="10%">Status Aduan</th>
                          <th width="4%">&nbsp;</th>
                          <th class="pr-1" width="4%">&nbsp;</th>
                        </thead>
                        <tbody>
                          <?php
                            while(($count<$rpp) && ($i<$tcount)) {
                            mysqli_data_seek($result, $i);
                            $data = mysqli_fetch_array($result);
                            
                            $qpt = "SELECT * FROM dt_all_user WHERE username = '$data[nm_pengaju]'";
                            $rpt = mysqli_query($con, $qpt);
                            $dpt = mysqli_fetch_assoc($rpt);
                            
                            $queryNmLevel = "SELECT * FROM opsi_level_user WHERE id='$dpt[level]'";
                            $rNmLevel = mysqli_query($con, $queryNmLevel);
                            $dNmLevel = mysqli_fetch_assoc($rNmLevel);
                            
                            $qolp = "SELECT * FROM opsi_level_pengajuan WHERE id = '$data[level_pengajuan]'";
                            $rolp = mysqli_query($con, $qolp);
                            $dolp = mysqli_fetch_assoc($rolp);
                            
                            $qoak = "SELECT * FROM opsi_aitem_kerusakan WHERE id = '$data[aitem_kerusakan]'";
                            $roak = mysqli_query($con, $qoak);
                            $doak = mysqli_fetch_assoc($roak);
                            
                            $qotk = "SELECT * FROM opsi_tingkat_kerusakan WHERE id = '$data[tingkat_kerusakan]'";
                            $rotk = mysqli_query($con, $qotk);
                            $dotk = mysqli_fetch_assoc($rotk);
                            
                            $qolk = "SELECT * FROM opsi_lokasi_kampus WHERE id_kampus = '$data[lokasi_kampus]'";
                            $rolk = mysqli_query($con, $qolk);
                            $dolk = mysqli_fetch_assoc($rolk);
                            
                            $qolg = "SELECT * FROM opsi_lokasi_gedung WHERE id_gedung = '$data[lokasi_gedung]'";
                            $rolg = mysqli_query($con, $qolg);
                            $dolg = mysqli_fetch_assoc($rolg);
                            
                            $qolr = "SELECT * FROM opsi_lokasi_ruang WHERE id_ruang = '$data[lokasi_ruang]'";
                            $rolr = mysqli_query($con, $qolr);
                            $dolr = mysqli_fetch_assoc($rolr);
                            
                            $qost = "SELECT * FROM opsi_status_tiket WHERE id = '$data[status_tiket]'";
                            $rost = mysqli_query($con, $qost);
                            $dost = mysqli_fetch_assoc($rost);
                            
                            $qoap = "SELECT * FROM opsi_approval WHERE id = '$data[approval]'";
                            $roap = mysqli_query($con, $qoap);
                            $doap = mysqli_fetch_assoc($roap);

                            $qov = "SELECT * FROM opsi_validasi WHERE id = '$data[validasi_admin]'";
                            $rov = mysqli_query($con, $qov);
                            $dov = mysqli_fetch_assoc($rov);
                            
                            $qdpt = "SELECT COUNT(id) AS jumKosong FROM dt_pelaksanaan_tiket WHERE id_tiket = '$data[id]' AND eviden = ''";
                            $rdpt = mysqli_query($con, $qdpt);
                            $ddpt = mysqli_fetch_assoc($rdpt);
                            $jumKosong = $ddpt['jumKosong'];
                            ?>
                          <tr data-widget="expandable-table" aria-expanded="false">
                            <td class="pl-1"><?php echo ++$no_urut;?></td>
                            <td class=""><?php if($data['status_tiket']==1 OR $data['status_tiket']==10) { echo "<a class='btn btn-info btn-xs btn-block' href='editTiketMasuk.php?id=".$data['id']."&page=".$page."' title='Edit data'><i class='far fa-edit'></i></a>";} else { echo "<a class='btn btn-secondary btn-xs btn-block' onclick='return confirm(\"Tidak bisa diedit! Aduan sudah melewati tahap Pengajuan\")' title='Tidak bisa diedit! Aduan sudah melewati tahap Pengajuan' disabled><i class='far far fa-edit'></i></a>";}?></td>
                            <td class=""><?php if($data['status_tiket']==1 OR $data['status_tiket']==10) { echo "<a class='btn btn-danger btn-xs btn-block' href='deleteTiketMasuk.php?id=".$data['id']."&page=".$page."' onclick='return confirm(\"Yakin data ini dihapus?\")' title='Hapus data'><i class='far fa-trash-alt'></i></a>";} else { echo "<a class='btn btn-secondary btn-xs btn-block' onclick='return confirm(\"Tidak bisa dihapus! Aduan sudah melewati tahap Pengajuan\")' title='Tidak bisa dihapus! Aduan sudah melewati tahap Pengajuan' disabled><i class='far fa-trash-alt'></i></a>";}?></td>
                            <td class="">
                              <?php 
                                if($data['status_tiket']==1) {echo "
                                <select class='form-control form-control-xs' style='width:140px;' disabled title='".$dost['nm']."' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                
                                elseif($data['status_tiket']==2) {echo "
                                <select class='form-control form-control-xs' style='width:140px;' disabled title='".$dost['nm']."' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                
                                elseif($data['status_tiket']==3) {echo "
                                <select class='form-control form-control-xs' style='width:140px;' disabled title='".$dost['nm']."' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                
                                elseif($data['validasi_admin']==2) {echo "
                                <select class='form-control form-control-xs' style='width:140px;' disabled title='".$dost['nm']."' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                
                                  elseif($jumKosong > 0) {echo "
                                <select name='approval' class='form-control form-control-xs' style='width:140px;' disabled title='Tidak bisa diedit! Ada teknisi yang belum upload foto data dukung.' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                elseif($data['durasi_penanganan']==0) {echo "
                                <select name='approval' class='form-control form-control-xs' style='width:140px;' disabled title='Tidak bisa diedit! Durasi penanganan belum diisi.' required>";
                                  $tampil = mysqli_query($con,  "SELECT * FROM opsi_approval ORDER BY id ASC" );
                                  while ( $w = mysqli_fetch_array( $tampil ) ) {
                                    if ( $data['approval'] == $w[ 'id' ] ) {
                                      echo "<option value='$w[id]' selected>$w[nm]</option>";
                                    } else {
                                      echo "<option value='$w[id]'>$w[nm]</option>";
                                    }
                                  } echo "</select>";}
                                else {
                                    include("editStatusApproval.php");}?>
                            </td>
                            <td class=""><?php echo $data['tgl_pengajuan'];?></td>
                            <td class=""><?php echo $doak['nm'];?></td>
                            <td class=""><?php echo $dolr['nm_ruang'].' '.$dolg['nm_gedung'].' '.$dolk['nm_kampus'];?></td>
                            <td class=""><?php echo $dost['nm'];?></td>
                            <td class="pr-1"><a href="fotoKerusakan.php?id=<?php echo $data['id'];?>&page=<?php echo $page;?>" class="btn btn-warning btn-xs btn-block" title="Lihat foto kerusakan"><i class="far fa-image"></i></a></td>
                            <td class="pr-1"><a href="fotoDtDukungTiket.php?id=<?php echo $data['id'];?>&page=<?php echo $page;?>" class="btn btn-success btn-xs btn-block" title="Lihat foto bukti penanganan aduan"><i class="fas fa-photo-video"></i></a></td>
                          </tr>
                          <tr class="expandable-body">
                            <td colspan="10">
                              <div class="card bg-gradient-light rounded-0 shadow">
                                <div class="card-body p-0">
                                  <?php include("ketTiket.php");?>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <?php
                            $i++; 
                            $count++;
                            }
                            ?>                        
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer pb-0 clearfix">
                    <div class="float-right"><?php echo paginate_one($reload, $page, $tpages);?></div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </section>
      </div>
    </div>
    <?php include( "footerUser.php" );?>
    <?php include( "jsUser.php" );?>
  </body>
</html>