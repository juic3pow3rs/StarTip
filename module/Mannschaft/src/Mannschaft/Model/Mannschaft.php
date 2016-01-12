<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 15:50
 */

namespace Mannschaft\Model;

class Mannschaft implements MannschaftInterface {
    /**
     * @var int
     */
    protected $m_id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $kuerzel;
    
    /**
     * @var string
     */
    protected $gruppe;

    /**
     * @inheritDoc
     *
     */
    public function getM_id()
    {
        return $this->m_id;
    }

    /**
     * @inheritDoc
     */
    public function setM_id($m_id)
    {
        $this->m_id = $m_id;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getKuerzel()
    {
        return $this->kuerzel;
    }

    /**
     * @inheritDoc
     */
    public function setKuerzel($kuerzel)
    {
        $this->kuerzel = $kuerzel;
    }
  
    
    /**
     * @inheritDoc
     */
    public function getGruppe()
    {
    	return $this->gruppe;
    }
    
    /**
     * @inheritDoc
     */
    public function setGruppe($gruppe)
    {
    	$this->gruppe = $gruppe;
    }
}