<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:50
 */

namespace Gruppe\Model;

class Gruppe implements GruppeInterface {
    /**
     * @var int
     */
    protected $g_id;

    /**
     * @var string
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $beschreibung;
    
    /**
     * @var string
     */
    protected $avatar;

    /**
     * @inheritDoc
     */
    public function getG_id()
    {
        return $this->g_id;
    }

    /**
     * @inheritDoc
     */
    public function setG_id($g_id)
    {
        $this->g_id = $g_id;
    }

    /**
     * @inheritDoc
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @inheritDoc
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
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
    public function getBeschreibung()
    {
    	return $this->beschreibung;
    }
    
    /**
     * @inheritDoc
     */
    public function setBeschreibung($beschreibung)
    {
    	$this->beschreibung = $beschreibung;
    }
    
    /**
     * @inheritDoc
     */
    public function getAvatar()
    {
    	return $this->avatar;
    }
    
    /**
     * @inheritDoc
     */
    public function setAvatar($avatar)
    {
    	$this->avatar = $avatar;
    }
}