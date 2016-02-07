<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 11:13
 */

namespace Benutzer\Service;

/**
 * Interface BenutzerServiceInterface
 * @package Benutzer\Service
 */
interface BenutzerServiceInterface {
    /**
     * @return array|\Traversable
     */
    public function findAllBenutzer();

    /**
     * @param $name
     * @return \Benutzer\Model\User
     */
    public function findBenutzer($name);

    /**
     * @param $id
     * @return mixed
     */
    public function findUser($id);

    /**
     * @param $g_id
     * @param $id
     * @param $leiter
     * @return mixed
     */
    public function inviteBenutzer($g_id, $id, $leiter);


    /**
     * @param $benutzername
     * @return mixed
     */
    public function suchBenutzer($benutzername);

    /**
     * @param $id
     * @param $url
     * @return mixed
     */
    public function setAva($id, $url);

    /**
     * @param $id
     * @return mixed
     */
    public function getAva($id);
    
}