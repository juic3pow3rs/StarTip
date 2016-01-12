<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:51
 */

namespace Gruppe\Model;

interface GruppeInterface {
    /**
     * Will return the ID of the Gruppe
     *
     * @return int
     */
    public function getG_id();

    /**
     * Will return the User_id of the Gruppe
     *
     * @return string
     */
    public function getUser_id();

    /**
     * Will return the NAME of the Gruppe
     *
     * @return string
     */
    public function getName();
    
    /**
     * Will return the BESCHREIBUNG of the Gruppe
     *
     * @return string
     */
    public function getBeschreibung();
    
    /**
     * Will return the AVATAR of the Gruppe
     *
     * @return string
     */
    public function getAvatar();
}