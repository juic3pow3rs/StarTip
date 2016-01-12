<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.11.2015
 * Time: 15:51
 */

namespace Tipp\Model;

interface TippInterface {
    /**
     * Will return the ID of the Album
     *
     * @return int
     */
    public function getT_id();

    /**
     * Will return the TITLE of the Album
     *
     * @return string
     */
    public function getB_id();

    /**
     * Will return the ARTIST of the Album
     *
     * @return string
     */
    public function getS_id();
    
    public function getTipp1();
    
    /**
     * Will return the TITLE of the Album
     *
     * @return string
     */
    public function getTipp2();
    
    /**
     * Will return the ARTIST of the Album
     *
     * @return string
     */
    public function getPunkte();
}