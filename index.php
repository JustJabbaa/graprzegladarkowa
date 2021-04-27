<?php
require_once('./class/GameManager.class.php');
        session_start();
        if(!isset($_SESSION['gm'])) // jeżeli nie ma w sesji naszej wioski
        {
            $gm = new GameManager();
            $_SESSION['gm'] = $gm;
        } 
        else //mamy już wioskę w sesji - przywróć ją
        {
            $gm = $_SESSION['gm'];
        }
        $v = $gm->v; //neizależnie cyz nowa gra czy załadowana
        $gm->sync(); //przelicz surowce
        
        if(isset($_REQUEST['action'])) 
        {
            switch($_REQUEST['action'])
            {
                case 'upgradeBuilding':
                    $v->upgradeBuilding($_REQUEST['building']);
                break;
                case 'townHall':
                    $buildingList = $v ->buildingList();
                  $mainContent = "<table class=\"table table-bordered\">";
                  $mainContent .= "<tr><th>Nazwa budynku</th><th>Poziom budynku</th>
                                <th>Produkcja/h / pojemność</th><th>Koszt ulepszenia</th><th>Rozbudowa</th></tr>";
                  foreach($buildingList as $index => $building)
                  {
                      $name = $building['buildingName'];
                      $level = $building['buildingName'];
                      $upgradeCost = "";
                      foreach($building['upgradeCost'] as $resource => $cost)
                      {
                       $upgradeCost .= "$resource: $cost,";
                      }
                      
                      $mainContent .="<tr><td>$name</td><td>$level</td><td>";
                      if(isset($building['capacity']))
                      {
                            $gain = $building['hourGain'];
                            $cap = $building['capacity'];
                            $mainContent .="<td>$gain / $cap</td>";   
                      }
                      else
                      {
                        $mainContent .="<td></td>"; 
                      }
                      $mainContent .="</td><td>$upgradeCost</td></tr>";
                      if($v->checkBuildingUpgrade($name))
                      $mainContent .= 
                     "<td><a href=\"index.php?action=upgradeBuilding&building=$name\">
                     <button>Rozbuduj</button> </a></td>";
                     else 
                    $mainContent .="<td> </td>";
                    $mainContent .="</tr>";
                  }


                  $mainContent .= "</table>";
                  $mainContent .= "<a href=\index.php\">Powrót</a>";
                  break;
                default:
                    $gm->l->log("Nieprawidłowa zmienna \"action\"", "controller", "error");
            }
        }




        
        
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="hudtest.css">
</head>
<body>
    <div id="lewo">
        <div id="gora">
            <div>
                Surowiec: Ilość Surowca | Przychód/godzine | Nazwa i poziom budynku<br>
                Drewno: <?php echo $v->showStorage("wood");?> | <?php echo $v->showHourGain("wood");?> | Drwal, poziom <?php echo $v->buildingLVL("woodcutter");?><br>
                Kamień: <?php echo $v->showStorage("stone");?> | <?php echo $v->showHourGain("stone");?> | Kopalnia Kamienia, poziom <?php echo $v->buildingLVL("stoneMine");?><br>
                Żelazo: <?php echo $v->showStorage("iron");?> | <?php echo $v->showHourGain("iron");?> | Kopalnia Żelaza, poziom <?php echo $v->buildingLVL("ironMine");?><br>

            </div>
        </div>
        
        <div id="dol">
            <p>
                <a class="button" href="index.php?action=upgradeBuilding&building=woodcutter">Rozbuduj drwala</a><br>
                <a class="button" href="index.php?action=upgradeBuilding&building=stoneMine">Ulepsz Kopalnie Kamieni</a><br>
                <a class="button" href="index.php?action=upgradeBuilding&building=ironMine">Ulepsz Kopalnie Żelaza</a><br>
                <a class="button" href="www.google.com">.</a><br>
                <a class="button" href="www.google.com">.</a><br>
                <a class="button" href="www.google.com">.</a><br>
                <a class="button" href="www.google.com">.</a> <br>
                <a class="button" href="index.php">Odśwież strone</a><br>
                <footer class="row">
            <div class="col-12">
            <table class="table table-bordered">
            <?php
            
                
                    
                
            
            foreach ($gm->l->getLog() as $entry) {
                $timestamp = date('d.m.Y H:i:s', $entry['timestamp']);
                $sender = $entry['sender'];
                $message = $entry['message'];
                $type = $entry['type'];
                echo "<tr>";
                echo "<td>$timestamp</td>";
                echo "<td>$sender</td>";
                echo "<td>$message</td>";
                echo "<td>$type</td>";
                echo "</tr>";
            }
            
            ?>
            </table>
            </div>
        </footer>
        </div>
            </p>

           
            <div>
            <?php if(isset($mainContent)) : 
                echo $mainContent; ?>
            <?php else : ?>
            Widok wioski
            <?php endif; ?>
            <br>
            <a href="index.php?action=townHall">Ratusz</a>
            </div>
            <div>
            Lista wojska
            </div>
            
            <footer class="row">
            <div class="col-12">
            <pre>
            <?php
            //var_dump($v);
            //var_dump($_REQUEST);
            ?>
            </pre>
            </div>
        </footer>
        </div>
    </div>
    
    <div></div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <?php var_dump($gm->s->schedule); ?>
</body>
</html>