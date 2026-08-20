<?php include( "contentsConUser.php" );
  $username = $_SESSION['username'];
  $tahun = mysqli_real_escape_string($con,  $_GET[ 'tahun' ] );
  $page = mysqli_real_escape_string($con,  $_GET[ 'page' ] );

  function getBulanIndonesia($bulanAngka) {
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $index = (int)$bulanAngka; 
    return $bulan[$index] ?? 'Bulan tidak valid';}

  $sql = "SELECT MONTH(thn_pengambilan) as bulan, COUNT(*) as jumData FROM kebutuhan_material_tiket WHERE YEAR(thn_pengambilan)='$tahun' GROUP BY MONTH(thn_pengambilan) ORDER BY bulan DESC";
    $result = mysqli_query($con, $sql);
    $chart_data="";
    WHILE($row = mysqli_fetch_array($result)) {
      $xBulan[]  = getBulanIndonesia($row['bulan']);
      $yJumlah[] = $row['jumData'];
  }
  ?>
<!DOCTYPE html>
<html lang="en">
  <?php include( "headUser.php" );?> 
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" style="min-height: 223.667px;">
      <?php 
        include( "navtopUser.php" );
        include( "navKsp.php" );
        ?> 
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <?php include( "alertUser.php" );?>
            <div class="row mb-2">
              <div class="col-sm-4">
                <h4 class="mb-0">Rekap Data</h4>
              </div>
              <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="rekapGudangKsp.php">Persediaan Barang</a></li>
                  <li class="breadcrumb-item"><a href="aitemKeluarPertahunKsp.php?page=<?php echo $page;?>">Pengeluaran</a></li>
                  <li class="breadcrumb-item active">Tahun <?php echo $tahun;?></li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <section class="col-lg-6 col-md-6 col-sm-12 connectedSortable">
                <?php include 'pagination1.php';
                  $reload1 = "aitemKeluarPerBulanKsp.php?tahun=$tahun&page=$page&pagination=true";
                  $sql1 = "SELECT MONTH(thn_pengambilan) as bulan, COUNT(*) as jumData FROM kebutuhan_material_tiket WHERE YEAR(thn_pengambilan)='$tahun' GROUP BY MONTH(thn_pengambilan) ORDER BY bulan DESC";
                  $result1 = mysqli_query($con, $sql1);
              
                  $rpp1 = 12;
                  $page1 = isset($_GET["page1"]) ? (intval($_GET["page1"])) : 1;
                  $tcount1 = mysqli_num_rows($result1);
                  $tpages1 = ($tcount1) ? ceil($tcount1/$rpp1) : 1;
                  $count1 = 0;
                  $i1 = ($page1-1)*$rpp1;
                  $no_urut1 = ($page1-1)*$rpp1;
                ?>
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="clearfix">
                      <h4 class="card-title float-left">Tahun <?php echo $tahun;?></h4>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive-no-wrap">
                      <table class="table table-hover m-0 table-sm custom">
                        <thead class="thead-light">
                          <th width="4%" class="pl-1">No.</th>
                          <th width="36%">Bulan</th>
                          <th width="56%">Frekuensi Pengeluaran</th>
                          <th class="pr-1" width="4%">Opsi</th>
                        </thead>
                        <tbody>
                          <?php
                            while(($count1<$rpp1) && ($i1<$tcount1)) {
                            mysqli_data_seek($result1, $i1);
                            $data = mysqli_fetch_array($result1);
                            ?>
                          <tr>
                            <td class="pl-1"><?php echo ++$no_urut1;?></td>
                            <td class=""><?php echo getBulanIndonesia($data['bulan']);?></td>
                            <td class=""><?php echo $data['jumData'];?></td>
                            <td class="pr-1"><a class="btn btn-info btn-xs btn-block" href="aitemKeluarPerTglKsp.php?tahun=<?php echo $tahun;?>&bulan=<?php echo $data['bulan'];?>&page=<?php echo $page;?>&page1=<?php echo $page1;?>" title="Lihat Detail"><i class="fas fa-angle-double-right"></i> Lihat Detail</a></td>
                          </tr>
                          <?php
                            $i1++; 
                            $count1++;
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer pb-0 clearfix">
                    <div class="float-right"><?php echo paginate_one1($reload1, $page1, $tpages1);?></div>
                  </div>
                </div>
              </section>
              <section class="col-lg-6 col-md-6 col-sm-12 connectedSortable">
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="clearfix">
                      <h4 class="card-title float-left">Grafik Tahun <?php echo $tahun;?></h4>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <canvas id="tekBul"></canvas>
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
    <script>
      var barColors = ["rgba(255, 99, 132, 0.4)","rgba(54, 162, 235, 0.4)","rgba(255, 206, 86, 0.4)","rgba(75, 192, 192, 0.4)","rgba(255, 255, 0, 0.4)","rgba(64, 224, 208, 0.4)","rgba(0, 128, 128, 0.4)","rgba(70, 130, 180, 0.4)","rgba(106, 90, 205, 0.4)","rgba( 160, 82, 45, 0.4)","rgba(46, 139, 87, 0.4)","rgba(65, 105, 225, 0.4)","rgba(255, 0, 0, 0.4)","rgba(128, 0, 128, 0.4)","rgba( 255, 165, 0, 0.4)","rgba(199, 21, 133, 0.4)"];
      
      new Chart("tekBul", {
      type: "bar",
      data: {
      labels: <?php echo json_encode($xBulan);?>,
      datasets: [{
      backgroundColor: barColors,
      data: <?php echo json_encode($yJumlah);?>
      }]
      },
      options: {
      scales: {
         yAxes: [{
             ticks: {
                 beginAtZero: true,
                 userCallback: function(label, index, labels) {
                     if (Math.floor(label) === label) {
                         return label;
                     }
      
                 },
             }
         }],
      },
        legend: {display: false},
      title: {
      display: true
      }
      }
      });
    </script>
  </body>
</html>