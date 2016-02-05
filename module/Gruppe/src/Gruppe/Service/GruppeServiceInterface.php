<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:47
 */

namespace Gruppe\Service;

use Gruppe\Model\GruppeInterface;

/**
 * Interface GruppeServiceInterface
 * @package Gruppe\Service
 */
interface GruppeServiceInterface {


    /**
     * @param $user_id
     * @return mixed
     */
    public function findAllGruppen($user_id);

    /**
     * @param $g_id
     * @return mixed
     */
    public function findGruppe($g_id);

    /**
     * @param $name
     * @return mixed
     */
    public function pruefGruppe($name);

    /**
     * @param $username
     * @param $g_id
     * @return mixed
     */
    public function findUser($username, $g_id);

    /**
     * @param $user_id
     * @return mixed
     */
    public function findAllEinladungen($user_id);

    /**
     * @param GruppeInterface $gruppe
     * @return mixed
     */
    public function saveGruppe(GruppeInterface $gruppe);

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function annehmen($user_id, $g_id);

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function ablehnen($user_id, $g_id);

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function isAdmin($user_id, $g_id);

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function isMitglied($user_id, $g_id);

    /**
     * @param $g_id
     * @return mixed
     */
    public function compare($g_id);

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function bereitsEingeladen($user_id, $g_id);

    /**
     * @param $g_id
     * @param $user_id
     * @return mixed
     */
    public function delete($g_id, $user_id);

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