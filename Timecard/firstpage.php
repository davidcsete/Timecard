<?php 
    include 'init.php';
    include 'header/header.php';

    $errorcode = isset($_GET["errorcode"]) ? $_GET["errorcode"] : 0;
    
    if ($errorcode != 0 ){
        $uzenet = Felhasznalo::getErrorMessage($errorcode);
    }    
    
    
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php");
        exit;
    }
    
    $time = isset($_GET["time"]) ? $_GET["time"] : time(); 
    $startdate = $felhasznalo->getStartDate($time);   
    $enddate = $felhasznalo->getEndDate($time);
    
?>

        <div class = "container-fluid">
            <div class="d-flex flex-row-reverse ">
                <!--Meghivja a checkout functiont a gomb megnyomasra-->
                    
                <div class="p-2 ">
                    <a href="function/checkout.php" class="btn btn-danger">Check Out</a>
                </div>
                <!--Meghivja a checkin functiont a gomb megnyomasra-->
                <div class="p-2 ">
                    <a href="function/checkin.php" class="btn btn-success">Check In</a>
                </div>
                <div class="p-2 align-middle szrke">Check In/Out a mai napra </div>
                <!--Kiirja ha mar checkinelve van a felhasznalo es megkeri, checkoutoljon mire ujra checkinel-->
                <?php if($errorcode != 0){?>
                <div class = "p-2 alert alert-danger">
                    <?=$uzenet?>
                </div>
                <?php }?>
            </div>
            <div class = "container mrgtbl ">
                <div class="table-responsive table-bordered ">
                    <table class="table caption-top ctrtbltxt">
                        <caption><?=$felhasznalo->nev. " " .$felhasznalo->keresztnev ?></caption>
                        <thead style = "text-align:center; vertical-align:middle;">
                            <tr>
                            <th scope="col" class="border-start border-end"><a href = "firstpage.php?time=<?=$felhasznalo->removeWeek($time)?>" class="fas fa-angle-left btn"></a> A hét napja <a href = "firstpage.php?time=<?=$felhasznalo->addWeek($time)?>" class="fas fa-angle-right btn"></a><br/><p class = "tbldtrg"><?=$startdate.' <i class="fas fa-long-arrow-alt-right"></i> '.$enddate?></p></th>                            
                            <th scope="col" class="border-start border-end">Check In</th>
                            <th scope="col" class="border-start border-end">Check Out</th>
                            <th scope="col" class="border-start border-end">Össz dolgozott idő</th>
                            </tr>
                        </thead>
                        <tbody style = "text-align:center;">
                            <?php 
                            
                            $dates = $felhasznalo->getBejel($startdate, $enddate);
                            $totaltime = 0;
                            //vegig megy a datumokon ugy hogy a datum a kulcs a $date pedig az arrayek amik tartoznak a kulcshoz
                            foreach($dates as $key=>$date)
                            { ?>
                                <!---vegig megy a datumokon belul levo arrayaken numerikus index szerint, azert hogy kiszamolja a totalt-->
                            <?php
                                $interval = 0;
                                foreach($date as $subkey => $check)
                                {                                    
                                    $kezdoidopont = strtotime($check[0]);
                                             
                                    $utolsoidopont = strtotime($check[1]);
                                    if(isset($check[1]))
                                    {
                                    $interval += $utolsoidopont-$kezdoidopont;
                                    }
                                }                          
                                
                                //vegig megy a datumokon belul levo arrayaken numerikus index szerint, azert hogy kiirassa a checkin illetve checkout idopontokat
                                foreach($date as $subkey => $check)
                                {
                            ?>
                                    <tr class = "tblbgcolor">                                    
                                        <?php if($subkey == 0){ ?>                                        
                                        <td scope="rowgroup" class="border-start border-end "  rowspan = "<?=count($date)?>" > <?=" Datum: ".$key ?></td>
                                        <?php } ?>
                                        <td class="border-start border-end "><?=" Checkin: ".$check[0];?></td>
                                        <td class="border-start border-end "><?=" Checkout: ".$check[1];?></td>                                
                                        <?php if($subkey == 0){ ?>                                             
                                        <td class="border-start border-top border-end " rowspan = "<?php echo(count($date));?>"><?=$felhasznalo->timeConvert($interval);?></td>
                                        <?php } ?>

                                    </tr>
                                <?php
                                }                                
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