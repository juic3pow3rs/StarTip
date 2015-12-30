<?php
/**
 * Created by PhpStorm.
 * User: Spiel
 * Date: 03.12.2015
 * Time: 10:20
 */

namespace Spiel\Model;

class Spiel implements SpielInterface {
    /**
     * @var int
     */
    protected $s_id;

    /**
     * @var int
     */
    protected $mannschaft1;

    /**
     * @var int
     */
    protected $mannschaft2;
    
    /**
     * @var int
     */
    protected $modus;
    
    /**
     * @var datetime
     */
    protected $anpfiff;
    
    /**
     * @var int
     */
    protected $tore1;
    
    /**
     * @var int
     */
    protected $tore2;
    
    /**
     * @var int
     */
    protected $punkte1;
    
    /**
     * @var int
     */
    protected $punkte2;
    
    /**
     * @var int
     */
    protected $gelb1;
    
    /**
     * @var int
     */
    protected $gelb2;
    
    /**
     * @var int
     */
    protected $rot1;
    
    /**
     * @var int
     */
    protected $rot2;
        
    

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
    public function getMannschaft1()
    {
        return $this->mannschaft1;
    }

    /**
     * @inheritDoc
     */
    public function setMannschaft1($mannschaft1)
    {
        $this->mannschaft1 = $mannschaft1;
    }

    /**
     * @inheritDoc
     */
    public function getMannschaft2()
    {
        return $this->mannschaft2;
    }

    /**
     * @inheritDoc
     */
    public function setMannschaft2($mannschaft2)
    {
        $this->mannschaft2 = $mannschaft2;
    }
    
    /**
     * @inheritDoc
     */
    public function getModus()
    {
    	return $this->modus;
    }
    
    /**
     * @inheritDoc
     */
    public function setModus($modus)
    {
    	$this->modus = $modus;
    }
    
    /**
     * @inheritDoc
     */
    public function getAnpfiff()
    {
    	return $this->anpfiff;
    }
    
    /**
     * @inheritDoc
     */
    public function setAnpfiff($anpfiff)
    {
    	$this->anpfiff = $anpfiff;
    }
    
    /**
     * @inheritDoc
     */
    public function getTore1()
    {
    	return $this->tore1;
    }
    
    /**
     * @inheritDoc
     */
    public function setTore1($tore1)
    {
    	$this->tore1 = $tore1;
    }
    
    /**
     * @inheritDoc
     */
    public function getTore2()
    {
    	return $this->tore2;
    }
    
    /**
     * @inheritDoc
     */
    public function setTore2($tore2)
    {
    	$this->tore2 = $tore2;
    }
    
    /**
     * @inheritDoc
     */
    public function getPunkte1()
    {
    	return $this->punkte1;
    }
    
    /**
     * @inheritDoc
     */
    public function setPunkte1($punkte1)
    {
    	$this->punkte1 = $punkte1;
    }
    
    /**
     * @inheritDoc
     */
    public function getPunkte2()
    {
    	return $this->punkte2;
    }
    
    /**
     * @inheritDoc
     */
    public function setPunkte2($punkte2)
    {
    	$this->punkte2 = $punkte2;
    }
    
    /**
     * @inheritDoc
     */
    public function getGelb1()
    {
    	return $this->gelb1;
    }
    
    /**
     * @inheritDoc
     */
    public function setGelb1($gelb1)
    {
    	$this->gelb1 = $gelb1;
    }
    
    /**
     * @inheritDoc
     */
    public function getGelb2()
    {
    	return $this->gelb2;
    }
    
    /**
     * @inheritDoc
     */
    public function setGelb2($gelb2)
    {
    	$this->gelb2 = $gelb2;
    }
    
    /**
     * @inheritDoc
     */
    public function getRot1()
    {
    	return $this->rot1;
    }
    
    /**
     * @inheritDoc
     */
    public function setRot1($rot1)
    {
    	$this->rot1 = $rot1;
    }
    
    /**
     * @inheritDoc
     */
    public function getRot2()
    {
    	return $this->rot2;
    }
    
    /**
     * @inheritDoc
     */
    public function setRot2($rot2)
    {
    	$this->rot2 = $rot2;
    }
    
}