<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 10:23
 */

namespace Spiel\Model;

/**
 * Interface SpielInterface
 * @package Spiel\Model
 */
interface SpielInterface {
    /**
     * Will return the s_id of the Spiel
     *
     * @return int
     */
    public function getS_id();

    /**
     * Will return the mannschaft1 of the Spiel
     *
     * @return int
     */
    public function getMannschaft1();
    
    /**
     * Will return the mannschaft2 of the Spiel
     *
     * @return int
     */
    public function getMannschaft2();
    
    /**
     * Will return the modus of the Spiel
     *
     * @return int
     */
    public function getModus();
    
    /**
     * Will return the anpfiff of the Spiel
     *
     * @return int
     */
    public function getAnpfiff();
    
    /**
     * Will return the tore1 of the Spiel
     *
     * @return int
     */
    public function getTore1();
    
    /**
     * Will return the tore2 of the Spiel
     *
     * @return int
     */
    public function getTore2();

    /**
     * Will return the punkte1 of the Spiel
     *
     * @return int
     */
    public function getPunkte1();
    
    /**
     * Will return the punkte2 of the Spiel
     *
     * @return int
     */
    public function getPunkte2();
    
    /**
     * Will return the gelb1 of the Spiel
     *
     * @return int
     */
    public function getGelb1();
    
    /**
     * Will return the gelb2 of the Spiel
     *
     * @return int
     */
    public function getGelb2();
    
    /**
     * Will return the rot1 of the Spiel
     *
     * @return int
     */
    public function getRot1();
   
    /**
     * Will return the rot2 of the Spiel
     *
     * @return int
     */
    public function getRot2();

    /**
     * @return mixed
     */
    public function getStatus();
}