<?php
    include 'init.php';
    include 'header/header.php';
    if($felhasznalo->admin == 0)
    {
        header("location: ../firstpage.php");
    }

    $delId = isset($_GET["delId"]) ? $_GET["delId"] : 0;

    if (isset($delId))
    {
        $felhasznalo->deleteFelhasz($delId);
    }
    
    $nevierr = $knevierr = $jelszoierr = $adminierr = $telszamierr = $emailierr = $jelszomierr = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        
        if (isset($_POST["mnevi"]))
        {   
            
            $mid = $_POST["mid"];     
            $mnevi = $_POST["mnevi"];
            $mknevi = $_POST["mknevi"];
            $mtelszami = $_POST["mtelszami"];
            $memaili = $_POST["memaili"];
            
            $felhasznalo->chgFelhasz($mnevi, $mknevi, $mtelszami, $memaili, $mid);    
        
    
        }
        elseif(!isset($_POST["mid"]))
        {
                    
            if (!empty($_POST["nevi"]))
            {
                $nevi = $_POST["nevi"];
            }
            else 
            {
                $nevierr = "Kérem adja meg a felhasznaló nevét!";
            }
            if (!empty($_POST["knevi"]))
            {
                $knevi = $_POST["knevi"];
            }
            else
            {
                $knevierr = "Kérem adja meg a felhasznaló keresztnevét!";
            }
            if (!empty($_POST["jelszoi"]))
            {
                $jelszoi = password_hash($_POST["jelszoi"], PASSWORD_DEFAULT);
            }            
            else
            {
                $jelszoierr = "Kérem adja meg a jelszót!";
            }
            if (empty($_POST["jelszomi"]) || ($_POST["jelszomi"]) != $_POST["jelszoi"])
            {
                $jelszomierr = "A második megadott jelszó nem egyezik meg az elsővel!";
            }                          
            
            if(!empty($_POST["telszami"]))
            {                
                
                $chkphne = $felhasznalo->checkPhoneExists($_POST["telszami"]);
                if ($chkphne == true)
                {
                    $telszami = $_POST["telszami"];
                }
                else
                {
                    $telszamierr = 'A telefonszám már szerepel az adatbázisunkban! Kérem használjon másikat!';
                }
            }
            else
            {
                $telszamierr = "Kérem adja meg a felhasznaló telefonszámát!";
            }
            if(!empty($_POST["emaili"]))
            {
                
                $chkmail = $felhasznalo->checkMailExists($_POST["emaili"]);
                if($chkmail == true)
                {
                    $emaili = $_POST["emaili"];
                }
                else
                {
                    $emailierr = 'Az emailcím már szerepel az adatbázisunkban! Kérem használjon másikat!';
                }
            }
            else
            {
                $emailierr = "Kérem adja meg a felhasznaló emailcímét!";
            }
            
            if (!empty($nevi) && !empty($knevi) && !empty($jelszoi) && !empty($telszami) && !empty($emaili))
            {          
                
                $admini = $_POST["admini"];  
                $felhasznalo->createFelhasz($nevi, $knevi, $jelszoi, $admini, $telszami, $emaili);

               unset($_POST);
               header("Location: ".$_SERVER['PHP_SELF']);  
            }
            
                
                      
        }  
    }
    
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
                        <caption>Felhasználo lista</caption>
                        <thead class="border-start border-top border-end" style = "text-align:center;">
                            <tr>                            
                            <th scope="col" class="border-end">Családnév</th>
                            <th scope="col" class="border-end">Keresztnév</th>
                            <th scope="col" class="border-end" >E-mail cím</th>
                            <th scope="col" class="border-end">Telefonszám</th>                            
                            </tr>
                        </thead>
                        <tbody class="border-start border-bottom" style = "text-align:center;">
                        <?php foreach ($felhasznalo->getAllFelhasz() as $key=>$adatok)
                        {?>
                            <tr>
                            <th scope="row" class="border-end"><?=$adatok['nev']?></th>
                            <td class="border-end"><?=$adatok['knev']?></td>
                            <td class="border-end"><?=$adatok['email']?></td>
                            <td class="border-end "><?=$adatok['telszam']?></td>
                            <td class="transbrdr" style = "padding:0; border-color:transparent; text-align:left;">
                                <?php if ($adatok['admn'] == 0)
                                {
                                 echo ' <!-- Button trigger modal -->
                                <button type="button" class="btn btn-danger btnmrg btn-sm" data-bs-toggle="modal" data-bs-target="#torlesModal'.$adatok['id'].'">
                                Törlés
                                </button>';
                                } ?>         

                                <!-- Modal -->
                                <div class="modal fade" id="torlesModal<?=$adatok['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">FIGYELEM!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Ha ezt válassza véglegesen kitörli a <?=$adatok['nev']." ".$adatok['knev']?> felhasználót!<br/>
                                            Biztos a változtatásban?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                                            <button type="button" class="btn btn-primary" onclick="window.location='/adminfelulet.php?delId=<?=$adatok['id']?>'">Igen</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="felhasznalotabla.php?felId=<?=$adatok['id']?>" class="btn btn-info btnmrg btn-sm">Felhasználó táblája</a>

                                <!-- Button trigger modal -->                                
                                <button type="button" class="btn btn-success btnmrg btn-sm btnmod" data-bs-toggle="modal" data-bs-target="#modositasModal<?=$adatok['id']?>">
                                Módosítás
                                </button>

                                <!-- Modal -->
                                <form method="post" action="<?php echo ($_SERVER["PHP_SELF"]);?>">
                                    <div class="modal fade" id="modositasModal<?=$adatok['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Módosítás!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <input type="hidden" name = "mid" value = "<?=$adatok['id']?>">                                            
                                                Név:<br/> <input type="text" name="mnevi" value="<?=$adatok['nev']?>">
                                                <br/><br/>
                                                Keresztnév:<br/> <input type="text" name="mknevi" value="<?=$adatok['knev']?>">
                                                <br/><br/>   
                                                Telefonszám:<br/> <input type="text" name="mtelszami" value="<?=$adatok['telszam']?>"> 
                                                <br/><br/> 
                                                Email:<br/> <input type="text" name="memaili" value="<?=$adatok['email']?>">                                                                                                                                           
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class = "btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                                                <input type="submit" name = "submit" value = "Módosítás" class="btn btn-primary" >
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            </tr> 
                <?php   }                        
                        ?>                           
                        </tbody>                        
                    </table>                    
                </div>
                <!-- Button trigger modal -->  
                <li class="list-group-item d-flex justify-content-between align-items-center chgwdthhzad">                              
                <button type="button" class="btn btnmrg btn-sm fas fa-user-plus hzadicon" data-bs-toggle="modal" data-bs-target="#hozzaadModal"></button>
                <span >Új Felhasználó létrehozása</span> 
                </li>
                <!-- Modal -->
                <form method="post" id = "form"action="<?php echo ($_SERVER["PHP_SELF"]);?>">
                    <div class="modal fade" id="hozzaadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Új felhasználó létrehozása!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">                                            
                                Név:<br/><input type="text" name="nevi"<?php if(!empty($nevi)){ ?>value = "<?=$nevi;}?>" placeholder="Vezetéknév...">
                                <span class="error"><?= $nevierr;?></span>
                                <br/><br/>
                                Keresztnév:<br/><input type="text" name="knevi" <?php if(!empty($knevi)){ ?>value = "<?=$knevi;}?>" placeholder="Keresztnév...">
                                <span class="error"><?= $knevierr;?></span>
                                <br/><br/>
                                Jelszó:<br/><input type="password" name="jelszoi" placeholder="Jelszó...">
                                <span class="error"><?= $jelszoierr;?></span>
                                <br/><br/>
                                Jelszó ismétlése:<br/><input type="password" name="jelszomi" placeholder="Jelszó ismétlése...">
                                <span class="error"><?= $jelszomierr;?></span>
                                <br/><br/>      
                                Telefonszám:<br/><input type="text" name="telszami" <?php if(!empty($telszami)){ ?>value = "<?=$telszami;}?>" placeholder="Telefonszám...">
                                <span class="error" id = "telszamerr"><?= $telszamierr;?></span> 
                                <br/><br/> 
                                Email:<br/> <input type="text" name="emaili" <?php if(!empty($emaili)){ ?>value = "<?=$emaili;}?>" placeholder="Emailcím...">
                                <span class="error" id = "emailerr"><?= $emailierr;?></span>
                                <br/><br/>
                                Admin lesz a felhasznaló?                                
                                <label><input type="radio" name="admini" value="1" style = "margin-right:1%;">Igen</label>                                
                                <label><input type="radio" name="admini" value="0" style = "margin-right:1%;" checked>Nem</label>
                                <br/>                                                                           
                            </div>
                            <div class="modal-footer">
                                <button type="button" class = "btn btn-secondary " data-bs-dismiss="modal">Mégse</button>
                                <input type="submit" name = "submit" value = "Létrehozás" class="btn btnmod" style = "color:white;" >
                            </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
        console.log('here');
        document.addEventListener("DOMContentLoaded", function(event) {
            const form = document.getElementById('form');
            console.log(form);
            console.log('here');
            const telErrSpan = document.getElementById('telszamerr');
            const emailErrSpan = document.getElementById('emailerr');
            form.addEventListener('submit', logSubmit);
            
            function logSubmit(event)
            {
                var str = form.telszami.value;
                var regex = new RegExp("^(\\+4)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\\s|\\.|\\-)?([0-9]{3}(\\s|\\.|\\-|)){2}$","gm");
                if(!(regex.test(str))){telErrSpan.textContent = `Az telefonszám nem helyes kérem adjon meg egy másikat!`;event.preventDefault();};
                var str2 = form.emaili.value;
                var regex2 = new RegExp("^(([^<>()[\\]\\.,;:\\s@\"]+(\\.[^<>()[\\]\\.,;:\\s@\"]+)*)|(\".+\"))@(([^<>()[\\]\\.,;:\\s@\"]+\\.)+[^<>()[\\]\\.,;:\\s@\"]{2,})$","i");
                if(!(regex2.test(str2))){emailErrSpan.textContent = `Az emailcím nem helyes kérem adjon meg egy másikat!`;};
            }
        });
            
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_POST["mid"])) && (!empty($nevierr) || !empty($knevierr) || empty(!$jelszoierr) || empty($jelszomi) || !empty($adminierr) || !empty($telszamierr) || !empty($emailierr) )) { ?>
        
        document.addEventListener("DOMContentLoaded", function(event) {
            var myModalEl = document.getElementById('hozzaadModal');
            var modal = new bootstrap.Modal(myModalEl);
            modal.show();            
        });
        
        <?php } ?>
        </script>
    </body>
</html>