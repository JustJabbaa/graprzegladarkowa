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
        $this->log('Tworzę nową wioskę', 'info');
        $this->buildings = array(
            'townHall' => 1,
            'woodcutter' => 0,
            'ironMine' => 0,
            'stoneMine' => 0,
        );
        $this->storage = array(
            'wood' => 50,
            'iron' => 0,
            'stone' => 0,
        );
        $this->upgradeCost = array( //tablica wszystkich budynkow
                'woodcutter' => array(
                    1 => array(
                        'wood' => 50
                    ),
                    2 => array(
                        'wood' => 50,
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
        $this->log('Utworzono nową wioskę', 'info');
    }
    private function woodGain(int $deltaTime) : float
    {
        //liczymy zysk na godzine z wzoru poziom_drwala ^ 2
        $gain = $this->buildings['woodcutter']* 2 * 50 ;
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
        if($this->storage['wood'] > $this->capacity('wood'))
          $this->storage['wood'] = $this->capacity('wood');

        $this->storage['iron'] += $this->ironGain($deltaTime);
        if($this->storage['iron'] > $this->capacity('iron'))
          $this->storage['iron'] = $this->capacity('iron');
        
        $this->storage['stone'] += $this->stoneGain($deltaTime);
        if($this->storage['stone'] > $this->capacity('stone'))
          $this->storage['stone'] = $this->capacity('stone');
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
                $this->log("Nie udało się ulepszyć budynku - brak surowca: ".$key, "warning");
                return false;
            }
        foreach ($cost as $key => $value) {
            //odejmujemy surowce na budynek
            $this->storage[$key] -= $value;
        }
        //podnies lvl budynku o 1
        $this->buildings[$buildingName] += 1; 
        $this->log("Ulepszono budynek: ".$buildingName, "info");
        return true;
    }}
    public function checkBuildingUpgrade(string $buildingName) : bool
    {
        $currentLVL = $this->buildings[$buildingName];
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
                echo "Nie ma takiego surowca!";
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
            return "Nie ma takiego surowca!";
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