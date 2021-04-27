<?php
require_once('Village.class.php');
require_once('Log.class.php');
require_once('Scheduler.class.php');
class GameManager
{
    public $v; //wioska
    public $l; //logi
    public $s; // scheduler
    public $t; //czas ostatniego refresha

    public function __construct()
    {
        $this->l = new Log();
        $this->s = new Scheduler($this);
        $this->l->log("Tworzę nową gre...", 'gameManager', 'info');
        $this->v = new Village($this);
        $this->t = time();

    }
    public function deltaTime() : int
    {
        return time() - $this->t;
    }
    public function sync()
    {
       $this->s->check($this->t);

        //na koniec
        
        $this->t = time();
        $this->v->gain($this->deltaTime());
    }
}
?>