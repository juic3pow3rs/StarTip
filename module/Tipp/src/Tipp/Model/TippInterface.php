<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.11.2015
 * Time: 15:51
 */

namespace Tipp\Model;

/**
 * Interface TippInterface
 * @package Tipp\Model
 */
interface TippInterface {
	
    /**
     * Gibt die t_d des Tipps zurück
     *
     * @return int
     */
    public function getT_id();

    /**
     * Gibt die B_id des Tipps zurück 
     *
     * @return int
     */
    public function getB_id();

    /**
     * Gibt die S_id des Tipps zurück
     *
     * @return int
     */
    public function getS_id();
    
    /**
     * Gibt die Tore1 des Tipps zurück
     *
     * @return int
     */
    public function getTipp1();
    
     /**
     * Gibt die Tore2 des Tipps zurück
     *
     * @return int
     */
    public function getTipp2();
    
      /**
     * Gibt die Punkte des Tipps zurück
     *
     * @return int
     */
    public function getPunkte();
}