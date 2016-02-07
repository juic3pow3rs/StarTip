<?php
/**
 * Created by PhpStorm.
 * User: Spiel
 * Date: 11.12.2015
 * Time: 10:20
 */

namespace Post\Model;

/**
 * Class Post
 * @package Post\Model
 */
class Post implements PostInterface {
    /**
     * @var int
     */
    protected $p_id;

    /**
     * @var int
     */
    protected $b_id;

    /**
     * @var int
     */
    protected $g_id;
    
    /**
     * @var int
     */
    protected $betreff;
    
       
    /**
     * @var int
     */
    protected $text;
    
    /**
     * @var int
     */
    protected $datum_zeit;

    /**
     * @inheritDoc
     */
    public function getP_id()
    {
        return $this->p_id;
    }

    /**
     * @inheritDoc
     */
    public function setP_id($p_id)
    {
        $this->p_id = $p_id;
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
    public function getBetreff()
    {
    	return $this->betreff;
    }
    
    /**
     * @inheritDoc
     */
    public function setBetreff($betreff)
    {
    	$this->betreff = $betreff;
    }
    
    /**
     * @inheritDoc
     */
    public function getText()
    {
    	return $this->text;
    }
    
    /**
     * @inheritDoc
     */
    public function setText($text)
    {
    	$this->text = $text;
    }
    
 
    
    /**
     * @inheritDoc
     */
    public function getDatum_zeit()
    {
    	return $this->datum_zeit;
    }
    
    /**
     * @inheritDoc
     */
    public function setDatum_zeit($datum_zeit)
    {
    	$this->datum_zeit = $datum_zeit;
    }

}