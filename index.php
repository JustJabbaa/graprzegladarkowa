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
                    require('view/townHall.php');
                break;
                case 'newUnit':
                    if(isset($_REQUEST['spearmen'])) //kliknelismy wyszkol przy włócznikach
                    {
                        $count = $_REQUEST['spearmen']; //ilość nowych włóczników
                        $gm->newArmy($count, 0, 0, $v); //tworz nowy oddział włóczników w wiosce w ilości $count;
                    }
                    if(isset($_REQUEST['archer'])) 
                    {
                        $count = $_REQUEST['archer']; 
                        $gm->newArmy(0, $count, 0, $v);
                    }    

                        if(isset($_REQUEST['cavalry'])) 
                        {
                            $count = $_REQUEST['cavalry']; 
                            $gm->newArmy(0, 0, $count , $v);
                        }
                        require('view/townSquare.php');
                        break;
                case 'townHall':
                    require('view/townHall.php');
                  break;
                  case 'townSquare':
                    require('view/townSquare.php');
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
    <div>
        <div>
            <div>
                Surowiec: Ilość Surowca | Przychód/godzine | Nazwa i poziom budynku<br>
                Drewno: <?php echo $v->showStorage("wood");?> | <?php echo $v->showHourGain("wood");?> | Drwal, poziom <?php echo $v->buildingLVL("woodcutter");?><br>
                Kamień: <?php echo $v->showStorage("stone");?> | <?php echo $v->showHourGain("stone");?> | Kopalnia Kamienia, poziom <?php echo $v->buildingLVL("stoneMine");?><br>
                Żelazo: <?php echo $v->showStorage("iron");?> | <?php echo $v->showHourGain("iron");?> | Kopalnia Żelaza, poziom <?php echo $v->buildingLVL("ironMine");?><br>

            </div>
        </div>
        
        <div> 
        <div>
            <?php if(isset($mainContent)) : 
                echo $mainContent; ?>
            <?php else : ?>
            Widok wioski
            <?php endif; ?>
            <br>
            <ul style="list-style-type: none; padding:0;">
            <li>
                    <a href="index.php?action=townHall">Ratusz</a>
            </li> 
             <li>
                      <a href="index.php?action=townSquare">Plac</a>
            </li> 
            <ul>
            </div>
            <div>
            Lista wojska
            </div>
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
    <pre>
    <?php
        echo "Obecny czas: ".time(); 
        var_dump($gm->s->schedule); 

    ?>
    </pre>
</body>
</html>