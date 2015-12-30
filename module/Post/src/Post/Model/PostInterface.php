<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 10:23
 */

namespace Post\Model;

interface PostInterface {
    /**
     * Will return the p_id of the Post
     *
     * @return int
     */
    public function getP_id();

    /**
     * Will return the B_id of the Post
     *
     * @return int
     */
    public function getB_id();
    
    /**
     * Will return the mannschaft2 of the Spiel
     *
     * @return int
     */
    public function getG_id();
    
    /**
     * Will return the modus of the Spiel
     *
     * @return int
     */
    public function getBetreff();
    
    /**
     * Will return the anpfiff of the Spiel
     *
     * @return datetime
     */
    public function getText();
    
    /**
     * Will return the tore1 of the Spiel
     *
     * @return int
     */
    public function getDatum_zeit();
    
}