<?php
class Scheduler
{
    public $schedule; // (timestamp, klasa, funkcja, parametr)
    private $gm;

    public function __construct($gameManager) {
        $this->schedule = array();

        $this->gm = $gameManager;
        $this->log('utworzono schedulera', 'info');
    }

    public function add($t, $c, $f, $p)
    {
        $task = array('timestamp' => $t,
                        'class' => $c,
                        'function' => $f,
                        'param' => $p;
        array_push($this->$schedule, $task)
        $this->log('dodano do schedulera nową pozycję'), 'info');
    }

    public function check($timestamp)
    {
        $todo = array();
        $this->log('kompletuje listę zaległych rzeczy do zrobienia'), 'info');
        foreach($this->schedule as$task)
    {
            if($task['timestamp'] >= $timestamp && $timestamp >= time())
            {
                array_push($todo, $task);
            }
        }
        $this->execute($todo);
    }

    public function execute($tasklist)
    {
        if(count($tasklist) >0)
            $this->log('wykonuje listę zadań', 'info');
        foreach($tasklist as $task)
        {
            if($task['class'] == 'Village')
            {
                //przetwarzanie zadań dla wioski
                $className = $task['class'];
                $funtionName = $task['funtion'];
                $param = $task['param'];
                $this->gm->v->{$funtionName}($param);
                $this->log("wywołuje funkcję $funtionName dla klasy $className z parametrem $parem", 'info');
                $this->gm->v->gain($task['timestamp'] - $this->gm->t);
                $this->log("synchronizuje surowce w wiosce", 'info');
                $this->gm->t = $task['timestamp'];
                $this->log("synchronizuje czas gry do czasu ukonczenia zadania", 'info');
           }
        }
    }
    public function log(string $message, string $type)
    {
        $this->gm-l->log($message, 'scheduler', $type);
    }



?>