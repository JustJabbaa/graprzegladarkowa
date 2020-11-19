<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <pre>
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
        var_dump($v);
        var_dump($_REQUEST);
        
    ?>
    </pre>
    <a href="index.php?action=upgradeBuilding&building=woodcutter">
        <button>Rozbuduj drwala</button>
    </a>
    <a href="index.php?action=upgradeBuilding&building=ironMine">
        <button>Rozbuduj kopalnie żelaza</button>
    </a>
</body>
</html>