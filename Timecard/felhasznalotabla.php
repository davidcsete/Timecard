<?php
    include 'init.php';
    include 'header/header.php';
    $felId = $_GET["felId"];
    $time = isset($_GET["time"]) ? $_GET["time"] : time();
    $neve = $felhasznalo->getFelhaszAdatai($felId);
    $startdate = $felhasznalo->getStartDate($time);   
    $enddate = $felhasznalo->getEndDate($time);
    
    
?>

        <div class = "container-fluid">
           <!-- <div class="d-flex flex-row-reverse ">                
                <div class="p-2 "><button type="button" class="btn btn-danger">Check Out</button></div>
                <div class="p-2 "><button type="button" class="btn btn-success">Check In</button></div> 
                <div class="p-2 align-middle szrke">Check In/Out a mai napra </div>
            </div>-->
            <div class = "container mrgtbl">
                <div class="table-responsive">
                    <table class="table caption-top" >
                    <caption><?=$neve['nev']. " " .$neve['knev'] ?></caption>
                        <thead style = "text-align:center;">
                            <tr>
                            <th scope="col" class="border-start border-top border-end"><a href = "felhasznalotabla.php?felId=<?=$felId?>&time=<?=$felhasznalo->removeWeek($time)?>" class="fas fa-angle-left btn"></a> A hét napja <a href = "felhasznalotabla.php?felId=<?=$felId?>&time=<?=$felhasznalo->addWeek($time)?>" class="fas fa-angle-right btn"></a><br/><p class = "tbldtrg"><?=$startdate.' <i class="fas fa-long-arrow-alt-right"></i> '.$enddate?></p></th>                            
                            <th scope="col" class="border-start border-top border-end">Check In</th>
                            <th scope="col" class="border-start border-top border-end">Check Out</th>
                            <th scope="col" class="border-start border-top border-end">Ossz dolgozott ido</th>
                            </tr>
                        </thead>
                        <tbody class="border-start border-bottom border-end" style = "text-align:center;">
                        <?php 
                        $totaltime = 0;
                        $dates = $felhasznalo->getFelhaszTablaja($startdate,$enddate,$felId);
                        foreach ( $dates as $key=>$date)
                        {
                            foreach($date as $subkey => $check)
                            {                                    
                                $kezdoidopont = strtotime($check[0]);
                                            
                                $utolsoidopont = strtotime($check[1]);
                                
                                if ($subkey == 0)
                                {
                                $interval = strtotime('0');
                                }
                                $interval = (($utolsoidopont-$kezdoidopont)+$interval);                                                                                                         
                                        
                            }                          
                            
                            //vegig megy a datumokon belul levo arrayaken numerikus index szerint, azert hogy kiirassa a checkin illetve checkout idopontokat
                            foreach($date as $subkey => $check)
                                {?>
                                    <tr class = "tblbgcolor">                                    
                                        <?php if($subkey == 0){ ?>                                        
                                        <td scope="row" class="border-start border-end "  rowspan = "<?=count($date)?>" > <?=" Datum: ".$key ?></td>
                                        <?php } ?>                                   
                                        <td class="border-start border-end"><?=" Checkin: ".$check[0];?></td>
                                        <td class="border-start border-end"><?=" Checkout: ".$check[1];?></td>                                
                                        <?php
                                         if($subkey == 0) 
                                        {?>                                             
                                             <td class="border-start border-top border-end " rowspan = "<?php echo(count($date));?>"><?=date('H:i:s',$interval-3600);?></td>
                                <?php   } ?>                                       
                                    </tr>
                                <?php }
                                $totaltime+= $interval;                                
                                if(array_key_last($dates) == $key)
                                { ?>
                                    <tr>
                                        <td scope = "row" class = "border-start border-end" colspan = "3" style = "text-align:right; padding-right: 3rem;"><?="A héten ledolgozott idő: "?></td>
                                        <td scope = "row" class = "border-start border-end"><?=$felhasznalo->timeConvert($totaltime)?></td>
                                    </tr>
                        <?php   }   
                        }?>                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>