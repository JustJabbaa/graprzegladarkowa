<?php 
        require('./class/Village.class.php');
        session_start();
        if(!isset($_SESSION['v'])) // jeżeli nie ma w sesji naszej wioski
        {
            echo "Tworzę nową wioskę...";
            $v = new Village();
            $_SESSION['v'] = $v;
            //reset czasu od ostatniego odświerzenia strony
            $deltaTime = 0;
        } 
        else //mamy już wioskę w sesji - przywróć ją
        {
            $v = $_SESSION['v'];
            //ilosc sekund od ostatniego odświerzenia strony
            $deltaTime = time() - $_SESSION['time'];
        }
        $v->gain($deltaTime);
        
        if(isset($_REQUEST['action'])) 
        {
            switch($_REQUEST['action'])
            {
                case 'upgradeBuilding':
                    if($v->upgradeBuilding($_REQUEST['building']))
                    {
                        echo "Ulepszono budynek: ".$_REQUEST['building'];
                    }
                    else
                    {
                        echo "Nie udało się ulepszyć budynku: ".$_REQUEST['building'];
                    }
                    
                break;
                default:
                    echo 'Nieprawidłowa zmienna "action"';
            }
        }




        $_SESSION['time'] = time();
        
        
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="hud.css">
</head>
<body>
    <div id="lewo">
        <div id="gora">
            <div>
                Surowiec: Ilość Surowca | Przychód/godzine | Nazwa i poziom budynku<br>
                Drewno: <?php echo $v->showStorage("wood");?> | <?php echo $v->showHourGain("wood");?> | Drwal, poziom <?php echo $v->buildingLVL("woodcutter");?><br>
                Kamień: <?php echo $v->showStorage("kamien");?> | <?php echo $v->showHourGain("kamien");?> | Kopalnia Kamienia, poziom <?php echo $v->buildingLVL("kopalniaKamienia");?><br>
                Żelazo: <?php echo $v->showStorage("iron");?> | <?php echo $v->showHourGain("iron");?> | Kopalnia Żelaza, poziom <?php echo $v->buildingLVL("ironMine");?><br>

            </div>
        </div>
        
        <div>
            <div>Widok wioski</div>
            <div>Lista wojska</div>
            <div>Wyloguj</div>
            <footer class="row">
            <div class="col-12">
            <pre>
            <?php
            var_dump($v);
            var_dump($_REQUEST);
            ?>
            </pre>
            </div>
        </footer>
        </div>
    </div>
    <div id="prawo"></div>
    <div></div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>