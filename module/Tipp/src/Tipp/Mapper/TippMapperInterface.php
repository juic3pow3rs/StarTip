<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 11:13
 */

namespace Tipp\Mapper;

use Tipp\Model\TippInterface;

/**
 * Interface TippMapperInterface
 * @package Tipp\Mapper
 */
interface TippMapperInterface {

    /**
     * @param $t_id
     * @return AlbumInterface
     * @internal param int|string $id
     */
    public function find($t_id);

    /**
     * @param $user_id
     * @return array|AlbumInterface[]
     */
    public function findAllTipps($user_id);

    /**
     * @param TippInterface $tippObject
     * @param $s_id
     * @return AlbumInterface
     * @internal param AlbumInterface $albumObject
     *
     * @internal param AlbumInterface $albumObject
     */
    public function save(TippInterface $tippObject, $s_id);

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function updateZusatztipp($id, $status);

    /**
     * @param $id
     * @param $user_id
     * @param $m_id
     * @return mixed
     */
    public function addZusatztipp($id, $user_id, $m_id);

    /**
     * @param $id
     * @param $m_id
     * @return mixed
     */
    public function setZusatztipp($id, $m_id);

    /**
     * @param $user_id
     * @return mixed
     */
    public function getZusatztipp($user_id);

    /**
     * @param $id
     * @return mixed
     */
    public function zusatzPunkteBerechnen($id);

    public function isActive();

    /**
     * @param $s_id
     * @return mixed
     */
    public function punkteBerechnen($s_id);

    /**
     * @param $s_id
     * @param $user_id
     * @return mixed
     */
    public function tippAbgegeben($s_id, $user_id);

    public function resetZusatztipp();
}