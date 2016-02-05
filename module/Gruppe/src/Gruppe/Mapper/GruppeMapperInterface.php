<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 11:13
 */

namespace Gruppe\Mapper;

use Gruppe\Model\GruppeInterface;

/**
 * Interface GruppeMapperInterface
 * @package Gruppe\Mapper
 */
interface GruppeMapperInterface {

    /**
     * @param int|string $g_id
     * @return GruppeInterface
     * @throws \InvalidArgumentException
     */
    public function find($g_id);

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
    public function findName($username, $g_id);

    /**
     * @param $user_id
     * @return array|\Gruppe\Model\GruppeInterface[]
     */
    public function findAll($user_id);

    /**
     * @paramGruppeInterface $gruppeObject
     *
     * @param GruppeInterface $gruppeObject
     * @return GruppeInterface
     * @throws \Exception
     */
    public function save(GruppeInterface $gruppeObject);

    /**
     * @param $user_id
     * @return mixed
     */
    public function findAllEinladungen($user_id);

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