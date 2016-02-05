<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 15:50
 */

namespace Tipp\Model;

/**
 * Class Tipp
 * @package Tipp\Model
 */
class Tipp implements TippInterface {
    /**
     * @var int
     */
    protected $t_id;

    /**
     * @var int
     */
    protected $b_id;

    /**
     * @var int
     */
    protected $s_id;
    
    /**
     * @var int
     */
    protected $tipp1;
    
    /**
     * @var int
     */
    protected $tipp2;
    
    /**
     * @var int
     */
    protected $punkte;

    /**
     * @inheritDoc
     */
    public function getT_id()
    {
        return $this->t_id;
    }

    /**
     * @inheritDoc
     */
    public function setT_id($t_id)
    {
        $this->t_id = $t_id;
    }

    /**
     * @inheritDoc
     */
    public function getB_id()
    {
        return $this->b_id;
    }

    /**
     * @inheritDoc
     */
    public function setB_id($b_id)
    {
        $this->b_id = $b_id;
    }

    /**
     * @inheritDoc
     */
    public function getS_id()
    {
        return $this->s_id;
    }

    /**
     * @inheritDoc
     */
    public function setS_id($s_id)
    {
        $this->s_id = $s_id;
    }
    
    /**
     * @inheritDoc
     */
    public function getTipp1()
    {
    	return $this->tipp1;
    }
    
    /**
     * @inheritDoc
     */
    public function setTipp1($tipp1)
    {
    	$this->tipp1 = $tipp1;
    }
    
    /**
     * @inheritDoc
     */
    public function getTipp2()
    {
    	return $this->tipp2;
    }
    
    /**
     * @inheritDoc
     */
    public function setTipp2($tipp2)
    {
    	$this->tipp2 = $tipp2;
    }
    
    /**
     * @inheritDoc
     */
    public function getPunkte()
    {
    	return $this->punkte;
    }
    
    /**
     * @inheritDoc
     */
    public function setPunkte($punkte)
    {
    	$this->punkte = $punkte;
    }
}