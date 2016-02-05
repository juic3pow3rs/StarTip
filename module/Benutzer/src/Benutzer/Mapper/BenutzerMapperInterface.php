<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:58
 */

namespace Benutzer\Mapper;

use ZfcUser\Entity\UserInterface;

/**
 * Interface BenutzerMapperInterface
 * @package Benutzer\Mapper
 */
interface BenutzerMapperInterface {

    /**
     * @return array|UserInterface[]
     */
    public function findAll();

    /**
     * @param $name
     * @return mixed
     */
    public function find($name);

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
    public function invite($g_id, $id, $leiter);

    /**
     * @param $benutzername
     * @return mixed
     */
    public function such($benutzername);

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