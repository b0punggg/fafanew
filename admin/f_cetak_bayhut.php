<link rel="stylesheet" href="../assets/css/paper.css">
<?php
    session_start();
    include 'config.php';
    $connect=opendtcek();
?>
<style>
	 th
    {
        text-align: center;
        border: solid 1px #113300;
        /*background: #EEFFEE;*/
    }

    td
    {
        border: solid 1px #113300;
        background: white;
        font-size: 8pt;
        border-left: none;
        border-right: none;
        border-top: none;
        /*border-style:dotted; */
    }
    .sheet {
      overflow: visible;
      height: auto !important;
     
    }
    tbody {page-break-before:always;}
    @page { size: F4 }

    #content {
    display: table;
    }

    #pageFooter {
        display: table-footer-group;
    }

    #pageFooter:after {
        counter-increment: page;
        content: counter(page);
    }

    @page {
      @bottom-right {
        content: counter(page) ' of ' counter(pages);
      }
    }
</style>
<body class="F4 ">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
        <?php 
        //ini_set('memory_limit', '1024M'); // or you could use 1G 
        $pilihan  = $_POST['pilihmuthut'];
    	  $kd_toko  = $_SESSION['id_toko'];
        $nm_toko  = "";$toko  = "";
        $x        = explode('-', $_SESSION['tgl_set']);
        $endbln   = $x[1];
        $endyear  = $x[0];
        $tglhi    = $_SESSION['tgl_set'];
        unset($x);

        $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
        $sql=mysqli_fetch_assoc($cektoko);
        $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
        $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
        unset($cektoko,$sql); 
        if ($pilihan=='alldata'){
            $cek=mysqli_query($connect,"SELECT beli_bay_hutang.*,supplier.nm_sup FROM beli_bay_hutang 
              LEFT JOIN supplier ON beli_bay_hutang.kd_sup=supplier.kd_sup
              WHERE MONTH(beli_bay_hutang.tgl_tran)='$endbln' AND YEAR(beli_bay_hutang.tgl_tran)='$endyear' AND byr_hutang>0
              ORDER BY beli_bay_hutang.tgl_tran,beli_bay_hutang.no_fak ASC");   
          
        }else {
          $toko=$_POST['kd_tokomuthut'];
          $cek=mysqli_query($connect,"SELECT beli_bay_hutang.*,supplier.nm_sup FROM beli_bay_hutang 
              LEFT JOIN supplier ON beli_bay_hutang.kd_sup=supplier.kd_sup
              WHERE MONTH(beli_bay_hutang.tgl_tran)='$endbln' AND YEAR(beli_bay_hutang.tgl_tran)='$endyear' AND byr_hutang>0 AND kd_toko='$toko'
              ORDER BY beli_bay_hutang.tgl_tran,beli_bay_hutang.no_fak ASC");   
        }
          if(mysqli_num_rows($cek)>=1){
            ?>
             
    	      <table cellspacing="0" style="width: 100%; font-size: 8pt;">
              <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Laporan Pembayaran Hutang Supplier Bulan Berjalan</b></td></tr>
               <tr style="background-color: lightgrey;">
    	            <th style="width:5%;">NO</th>
    	            <th style="width:10%">TGL. BELI</th>
    	            <th style="width:25%">NOTA</th>
    	            <th >SUPPLIER</th>
                  <th style="width:10%">TGL. BAYAR</th>
                  <th style="width:12%">NOMINAL</th>
               </tr> 
              </thead>   
    	        <?php
    	        $no=0;$tot_nom=0;
    	      	while($databay=mysqli_fetch_assoc($cek)){
    	      	  $no++;	
                $tot_nom=$tot_nom+$databay['byr_hutang'];               
                ?>
                <tr>
    	            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_fak']);?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo $databay['no_fak'];?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $databay['nm_sup']; ?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_tran']); ?></td>
                  <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo gantitides($databay['byr_hutang']); ?></td>
                </tr>	
    	        <?php           
    	      	}
    	        ?>
    	        <tr cellspacing="2" style="background-color: lightgrey;color: black;font-size: 8pt">
                <th colspan=5 align="center">TOTAL</th>
                <th style="text-align:right"><?php echo gantitides($tot_nom) ?></th>
              </tr>   
            </table>
    	    <?php  	
          }?>
    </div>
  </section>
</body>             

<?php mysqli_close($connect); ?>
<script>window.print();</script>