<?php
class Village 
{
    private $gm;
    private $buildings;
    private $storage;
    private $upgradeCost;

    public function __construct($gameManger)
    {
        $this->gm = $gameManger;
        $this->log('Utworzono nową wioskę', 'info');
        $this->buildings = array(
            'townHall' => 1,
            'woodcutter' => 0,
            'ironMine' => 0,
            'stoneMine' => 0,
        );
        $this->storage = array(
            'wood' => 200,
            'iron' => 0,
            'stone' => 0,
        );
        $this->upgradeCost = array( //tablica wszystkich budynkow
                'woodcutter' => array(
                    1 => array(
                        'wood' => 50
                    ),
                    2 => array(
                        'wood' => 200,
                    ),
                    3 => array(
                        'wood' => 1500,
                        'stone' => 500,
                    ),
                    4 => array(
                        'wood' => 12000,
                        'stone' => 250,
                        'iron' => 50
                    ),
                    5 => array(
                        'wood' => 72000,
                        'stone' => 1000,
                        'iron' => 300,
                    )
                ),
                'stoneMine' => array(
                    1 => array(
                        'wood' => 1000,
                    ),
                    2 => array(
                        'wood' => 3000,
                        'stone' => 1000,
                    ),
                    3 => array(
                        'wood' =>14000,
                        'stone' => 700,
                        'iron' => 250
                    ),
                    4 => array(
                        'wood' => 40000,
                        'stone' => 2000,
                        'iron' => 500,
                    ),
                    5 => array(
                        'wood' => 120000,
                        'stone' => 10000,
                        'iron' => 2000,
                    )
                ),
                'ironMine' => array(
                    1 => array(
                        'wood' => 100,
                    ),
                    2 => array(
                        'wood' => 300,
                        'iron' => 100,
                    )
                ),
        

        );
    }
    public function buildingList() : array  {
        $buildingList = array();
        foreach($this->buildings as $buildingName => $buildingLVL)
        {
         $building = array();
         $building['buildingName'] = $buildingName;
         $building['buildingLVL'] = $buildingLVL;
         if(isset($this->upgradeCost[$buildingName][$buildingLVL+1]))
             $building['upgradeCost'] = $this->upgradeCost[$buildingName][$buildingLVL+1];
         else 
             $building['upgradeCost'] = array();
                switch($buildingName) {
                    case 'woodcutter' :
                        $building['HourGain'] = $this->woodGain(60*60);
                        $building['capacity'] = $this->capacity('wood');
             break;
             case 'ironMine':
                         $building['HourGain'] = $this->ironGain(60*60);
                        $building['capacity'] = $this->capacity('iron');
                        break;
            case 'stoneMine':
                $building['HourGain'] = $this->stoneGain(60*60);
                $building['capacity'] = $this->capacity('stone');
            break;
                    }
          
                    array_push($buildingList, $building);

        }
        return $buildingList;
    }
    private function woodGain(int $deltaTime) : float
    {
        //liczymy zysk na godzine z wzoru poziom_drwala ^ 2
        $gain = $this->buildings['woodcutter']* 2 * 750 ;
        // liczymy zysk na sekunde (godzina/3600)
        $perSecondGain = $gain / 3600;
        //zwracamy zysk w czasie $deltaTime
        return $perSecondGain * $deltaTime;
    }
    private function ironGain(int $deltaTime) : float
    {
        //liczymy zysk na godzine z wzoru poziom_drwala ^ 2
        $gain = $this->buildings['ironMine']*2 * 125;
        // liczymy zysk na sekunde (godzina/3600)
        $perSecondGain = $gain / 3600;
        //zwracamy zysk w czasie $deltaTime
        return $perSecondGain * $deltaTime;
    }
    private function stoneGain(int $deltaTime) : float
    {
        //liczymy zysk na godzine z wzoru poziom_drwala ^ 2
        $gain = $this->buildings['stoneMine']*2 * 500;
        // liczymy zysk na sekunde (godzina/3600)
        $perSecondGain = $gain / 3600;
        //zwracamy zysk w czasie $deltaTime
        return $perSecondGain * $deltaTime;
    }
    public function gain($deltaTime) 
    {
        $this->storage['wood'] += $this->woodGain($deltaTime);
        $this->storage['iron'] += $this->ironGain($deltaTime);
        $this->storage['stone'] += $this->stoneGain($deltaTime);
    }
    public function upgradeBuilding(string $buildingName) : bool
    {
        $currentLVL = $this->buildings[$buildingName];
        $cost = $this->upgradeCost[$buildingName][$currentLVL+1];
        foreach ($cost as $key => $value) {
            //key - nazwa surowca
            //value koszt surowca
            if($value > $this->storage[$key])
            {
                $this->log("Nie udalo sie ulepszyc budynku - brak surowca: ".$key, "warning");
                return false;                 
            }
                
        }
        foreach ($cost as $key => $value) {
            //odejmujemy surowce na budynek
            $this->storage[$key] -= $value;
        }
        //odwołanie do schedulera
        $this->gm->s->add(time()+300, 'Village' , 'scheduledBuildingUpgrade' , $buildingName);
        return true;
    }
    public function scheduledBuildingUpgrade(string $buildingName)
    {
        //podnies lvl budynku o 1
        $this->buildings[$buildingName] += 1;
        $this->log("Ulepszono budynek: ".$buildingName, "Info");
    }
    public function checkBuildingUpgrade(string $buildingName) : bool
    {
        $currentLVL = $this->buildings[$buildingName];
        if(!isset($this->upgradeCost[$buildingName][$currentLVL+1]))
        return false;
        $cost = $this->upgradeCost[$buildingName][$currentLVL+1];
        foreach ($cost as $key => $value) {
            //key - nazwa surowca
            //value koszt surowca
            if($value > $this->storage[$key])
                return false;
        }
        return true;
    }
    public function showHourGain(string $resource) : string
    {
        switch($resource) {
            case 'wood':
                return $this->woodGain(3600);
            break;
            case 'iron':
                return $this->ironGain(3600);
            break;
            case 'stone':
                return $this->stoneGain(3600);
            break;
            default:
                $this->log( "Nie ma takiego surowca!", "error");
            break;
        }
    }
    public function showStorage(string $resource) : string 
    {
        if(isset($this->storage[$resource]))
        {
            return floor($this->storage[$resource]);
        }
        else
        {
            $this->log( "Nie ma takiego surowca!", "error");
            return "";
        }
    }
    public function buildingLVL(string $building) : int 
    {
        return $this->buildings[$building];
    }
    public function capacity(string $resource) : int 
    {
        switch ($resource) {
            case 'wood':
                return $this->woodGain(60*60*24); //doba
                break;
            case 'iron':
                return $this->ironGain(60*60*12); //12 godzin
                break;
                
            default:
                return 0;
                break;
        }
    }
    public function log(string $message, string $type)
    {
        $this->gm->l->log($message, 'village', $type);
    }
}
?>