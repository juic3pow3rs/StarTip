<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 11:13
 */

namespace Gruppe\Mapper;

use Gruppe\Model\GruppeInterface;

interface GruppeMapperInterface {

    /**
     * @param int|string $g_id
     * @return GruppeInterface
     * @throws \InvalidArgumentException
     */
    public function find($g_id);
    
    public function findName($username, $g_id);

    /**
     * @return array|GruppeInterface[]
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
    
    public function isAdmin($user_id, $g_id);

    public function isMitglied($user_id, $g_id);

    public function compare($g_id);
    
    public function bereitsEingeladen($user_id, $g_id);
}