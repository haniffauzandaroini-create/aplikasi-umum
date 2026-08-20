<?php include( "contentsConUser.php" );?>
<!DOCTYPE html>
<html lang="en">
  <?php include( "headUser.php" );?> 
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" style="min-height: 223.667px;">
      <?php 
        include( "navtopUser.php" );
        include( "navAdmTa.php" );
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
                  <li class="breadcrumb-item active">Penugasan</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <?php include 'pagination.php';
          if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
          $keyword=$_REQUEST['keyword'];
          $reload = "aduanPenugasan.php?pagination=true&keyword=$keyword";
          
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
          
          WHERE (r.id LIKE '%$keyword%' OR r.kode_tiket LIKE '%$keyword%' OR r.tgl_pengajuan LIKE '%$keyword%' OR r.tgl_penugasan LIKE '%$keyword%' OR r.tgl_pra_penanganan LIKE '%$keyword%' OR r.tgl_proses_penanganan LIKE '%$keyword%' OR r.tgl_selesai_penanganan LIKE '%$keyword%' OR r.deskripsi_kerusakan LIKE '%$keyword%' OR dau_admin.username LIKE '%$keyword%' OR dau_admin.nm_person LIKE '%$keyword%' OR dau_admin.domain_person LIKE '%$keyword%' OR dau_koord.username LIKE '%$keyword%' OR dau_koord.nm_person LIKE '%$keyword%' OR dau_koord.domain_person LIKE '%$keyword%' OR dau_pelaksana.username LIKE '%$keyword%' OR dau_pelaksana.nm_person LIKE '%$keyword%' OR dau_pelaksana.domain_person LIKE '%$keyword%' OR olp.nm LIKE '%$keyword%' OR dau.username LIKE '%$keyword%' OR dau.nm_person LIKE '%$keyword%' OR dau.domain_person LIKE '%$keyword%' OR olk.nm_kampus LIKE '%$keyword%' OR olg.nm_gedung LIKE '%$keyword%' OR olr.nm_ruang LIKE '%$keyword%' OR oak.nm LIKE '%$keyword%' OR otk.nm LIKE '%$keyword%' OR oap.nm LIKE '%$keyword%' OR ov.nm LIKE '%$keyword%') AND r.status_tiket = '2' ORDER BY r.id DESC";
          
          $result = mysqli_query($con, $sql);
          }else{
          $reload = "aduanPenugasan.php?pagination=true";
          $sql = "SELECT * FROM tiket_masuk WHERE status_tiket = '2' ORDER BY id DESC";
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
              <div class="col-sm mb-2">
                <form method="post" action="aduanPenugasan.php">
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
                      <a class="btn btn-sm btn-warning" title="Refresh" href="aduanPenugasan.php"><i class="fas fa-sync"></i> Refresh</a>
                      <?php
                        }
                        ?>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="row">
              <section class="col-sm-12 connectedSortable">
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="clearfix">
                      <h4 class="card-title float-left">Penugasan</h4>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive-no-wrap">
                      <table class="table table-hover m-0 table-sm custom">
                        <thead class="thead-light">
                          <th width="4%" class="pl-1">No.</th>
                          <th width="18%">Pilih Koordinator</th>
                          <th width="28%">Pengaju Aduan</th>
                          <th width="12%">Tgl. Pengajuan</th>
                          <th width="14%">Aitem Perbaikan</th>
                          <th width="20%">Lokasi Perbaikan</th>
                          <th width="4%" class="pr-1">&nbsp;</th>
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
                            ?>
                          <tr data-widget="expandable-table" aria-expanded="false">
                            <td class="pl-1"><?php echo ++$no_urut;?></td>
                            <?php if($data['status_tiket']==1 OR $data['status_tiket']==2 OR $data['status_tiket']==10) {
                              include ('pilihKoordinator.php');}
                              else {echo
                            '<td class="">
                              <select with="140%" class="form-control form-control-xs" title="Tidak bisa diedit. Aduan telah diproses oleh Koordinator" disabled required>
                            </td>';}?>
                            <td class=""><?php echo $dpt['nm_person'].' '.'['.$dpt['domain_person'].']' ;?></td>
                            <td class=""><?php echo $data['tgl_pengajuan'];?></td>
                            <td class=""><?php echo $doak['nm'];?></td>
                            <td class=""><?php echo $dolr['nm_ruang'].' '.$dolg['nm_gedung'].' '.$dolk['nm_kampus'];?></td>
                            <td class="pr-1">
                              <button type="button" class="btn btn-warning btn-xs btn-block" data-toggle="modal" data-target="#fotoKerusakan" data-whatever="<?php echo $data['id'];?>" title="Lihat foto kerusakan"><i class="far fa-image"></i></button>
                              <div class="modal fade" id="fotoKerusakan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="fotoKerusakanLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="fotoKerusakan">Foto Kerusakan</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="isiFotoKerusakan"></div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr class="expandable-body">
                            <td colspan="7">
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