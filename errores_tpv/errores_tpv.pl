my $user          = param("user");

use DBI;
use DBD::ODBC;
use lib::Global;
use open 'locale';

######### INICIO Conector SQL SERVER #########
   my $dbh = DBI->connect("dbi:ODBC:sql_33", 'sa', 'sa', {PrintError => 0});
   $dbh->do("use SBO_KAYSER");
######### FIN Conector SQL SERVER  #########


###################### QUERY BUSCA ID PRODUCTOS ######################

####### INICIO Fecha y Hra. #####
my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
$year += 1900;
$mon++;
#print "$mday/$mon/$year $hour:$min:$sec\n";
####### FIN Fecha y Hra. #####

my $htx = HTX->new($FolderRoot{template}.'errores_tpv.htx');
my $a=0;
my $AccountCode;
my $ItemCode;
my $Currency;
my $UnitPrice;
my $Quantity;
my $WarehouseCode;
my $RecordKey;
my $RecordKey_count=0;
my $LineNum;
my $RecordKey_;
my $Comments_;
my $DocDate_;
my $DocEntry_;
$htx->print_header(0);
$htx->param('user'=>$user,'date'=>$mday."".$mon."".$year);
$htx->print_detail;
$htx->print_header(1);
      my $sql_error="SELECT     t1_1.U_GSP_LIBOTI, t1_1.U_GSP_LIARTI, cast(t1_1.CANTIDAD as int), t2.ItemCode, t2.WhsCode, cast(t2.OnHand as int)
               FROM (
                   SELECT     t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI, SUM(t1.U_GSP_LIQUAN) AS CANTIDAD
                   FROM [\@GSP_TPVLIN] AS t1 
                   INNER JOIN [\@GSP_TPVCAP] AS t2 ON t1.U_GSP_DOCCODE = t2.Code
                   WHERE      (t2.U_GSP_ERROR LIKE '%la cantidad recae%') AND (t1.U_GSP_LIARTI <> 'KDIF') AND (t2.U_GSP_CADOCU IN ('VTI_AG', 'VTIM_AG', 'VFA')) AND (t2.U_GSP_CAESTA = 'E')
                   GROUP BY t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI) AS t1_1 
               INNER JOIN  dbo.OITW AS t2 ON t1_1.U_GSP_LIBOTI = t2.WhsCode AND t1_1.U_GSP_LIARTI = t2.ItemCode AND t1_1.CANTIDAD > t2.OnHand
               where U_GSP_LIARTI not like 'BOL%'  and U_GSP_LIARTI not like 'PROM%'
               order by U_GSP_LIBOTI asc";      
      $query_error = $dbh->prepare($sql_error);
      $query_error->execute();
      my $count_fila,$count_fila_=0;
      
      ######### INICIO Conector SQL SERVER #########
         my $dbh = DBI->connect("dbi:ODBC:sql_33", 'sa', 'sa', {PrintError => 0});
         $dbh->do("use SBO_KAYSER");
      ######### FIN Conector SQL SERVER  #########
      my $costo=0;
      my $costo_img;
      my $suma_diferencia=0;
      my $count_almacen=0;
      my $almacen;
      while (my @row_error=$query_error->fetchrow) {
         $count_fila++;
          my $sql_stock_prod_ = "select cast(AvgPrice as int),U_APOLLO_SEG1,U_APOLLO_SEG2,U_APOLLO_SEG3 from OITM where ItemCode='".$row_error[1]."'";
            my $sth_stock_ = $dbh->prepare ($sql_stock_prod_);
            $sth_stock_->execute();
            my @row_stock_= $sth_stock_->fetchrow_array();
               #print $sql_stock_prod_."<br>";
               #print $row_stock_[0]."<br>";
            if ($row_stock_[0] == 0) {
               my @segmentos_array = split('-',$row_error[1]);
               my $seg_1=$segmentos_array[0];
               my $seg_2=$segmentos_array[1];
                  ######### INICIO Conector SQL SERVER #########
                  my $dbh = DBI->connect("dbi:ODBC:sql_33", 'sa', 'sa', {PrintError => 0});
                  $dbh->do("use SBO_KAYSER");
               ######### FIN Conector SQL SERVER  #########
               my $sql_stock_prod3 = "select cast(avg(AvgPrice) as int)from OITM where U_APOLLO_SEG1='$seg_1' and U_APOLLO_SEG2='$seg_2'  and AvgPrice !=0";
             
               my $sth_stock3 = $dbh->prepare ($sql_stock_prod3);
               $sth_stock3->execute();
               my @row_stock3= $sth_stock3->fetchrow_array();
               if ($row_stock3[0] == 0) {
                  my $sql_stock_prod2 = "select cast(avg(AvgPrice) as int) from OITM where U_APOLLO_SEG1='$seg_1' and AvgPrice !=0";
                  #print $sql_stock_prod2."<br>";
                  my $sth_stock2 = $dbh->prepare ($sql_stock_prod2);
                  $sth_stock2->execute();
                  my @row_stock2= $sth_stock2->fetchrow_array();
                  $costo=$row_stock2[0];
                  if (!$costo) {
                     $costo_img='falta_costo.png';
                  }
                  
               }else{
                  $costo=$row_stock3[0];
                    $costo_img='no_falta_costo.png';
               }
            }else{
                $costo=$row_stock_[0];
                  $costo_img='no_falta_costo.png';
            }

            $diferencia=$row_error[2] - $row_error[5];
            if ($row_error[0] ne $almacen and $costo > 0) {
               $almacen=$row_error[0];
               $count_almacen++;
               $RecordKey_count++;
               
               $RecordKey_.=$RecordKey_count."<";
               $Comments_.=$row_error[0]."- AJUSTES <";
               $DocDate_=$mday."-".$mon."-".$year;
               $count_fila_=0;
               
            }
            
            $suma_diferencia+=$diferencia;
            $htx->param('fila'=>$count_fila,'Almacen'=>$row_error[0],'Articulo'=>$row_error[1],'Venta'=>$row_error[2],'ItemCode'=>$row_error[3],'Stock'=>$row_error[4],'diferencia'=>$diferencia,'costo'=>$costo,'img_costo'=>$costo_img);
            $htx->print_detail;
            
            if ($costo > 0) {
               $RecordKey.=$RecordKey_count."<";
               $LineNum.=$count_fila_."<";
               $AccountCode='_SYS00000000225';
               $ItemCode.=$row_error[3]."<";
               $Currency='$';
               $UnitPrice.=$costo."<";
               $Quantity.=$diferencia."<";
               $WarehouseCode.=$row_error[0]."<";
                  #$sku_kayser_tienda.=$sku_kayser."<";
            }
            
            $count_fila_++;
      }
      
$htx->param('fila'=>'','Almacen'=>'TOTAL TIENDAS = '.$count_almacen,'Articulo'=>'','Venta'=>'','ItemCode'=>'','Stock'=>'','costo'=>'','diferencia'=>'TOTAL DIFERENCIA = '.$suma_diferencia,'img_costo'=>'no_falta_costo.png');
$htx->print_detail;

####### TABLA DETALLE
$htx->print_header(2);
   my @ItemCode_array=split("<", $ItemCode);
   my @UnitPrice_array=split("<", $UnitPrice);
   my @Quantity_array=split("<", $Quantity);
   my @WarehouseCode_array=split("<", $WarehouseCode);
   my @RecordKey_array=split("<", $RecordKey);
   my @LineNum_array= split("<", $LineNum);
   my $tm=0;
   while($ItemCode_array[$tm]){
         $htx->param('RecordKey'=>$RecordKey_array[$tm],'LineNum'=>$LineNum_array[$tm],'AccountCode'=>$AccountCode,'ItemCode'=>$ItemCode_array[$tm],'Currency'=>$Currency,'UnitPrice'=>$UnitPrice_array[$tm],'Quantity'=>$Quantity_array[$tm],'WarehouseCode'=>$WarehouseCode_array[$tm]);
         $htx->print_detail;
         $tm++;
   }
#######

####### TABLA ENCABEZADOS
$htx->print_header(3);
   my @RecordKey_array =split("<", $RecordKey_);
   my @Comments_array  =split("<",  $Comments_);
   my $te=0;
   while($RecordKey_array[$te]){
         $htx->param('RecordKey_array'=>$RecordKey_array[$te],'Comments_array'=>$Comments_array[$te],'c'=>$DocDate_);
         $htx->print_detail;
         $te++;
   }
#######

$htx->print_footer;
$htx->close;
