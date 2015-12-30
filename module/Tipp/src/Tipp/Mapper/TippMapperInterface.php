<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 11:13
 */

namespace Tipp\Mapper;

use Tipp\Model\TippInterface;

interface TippMapperInterface {

    /**
     * @param int|string $id
     * @return AlbumInterface
     * @throws \InvalidArgumentException
     */
    public function find($t_id);

    /**
     * @return array|AlbumInterface[]
     */
    public function findAll();

    /**
     * @param AlbumInterface $albumObject
     *
     * @param AlbumInterface $albumObject
     * @return AlbumInterface
     * @throws \Exception
     */
    public function save(TippInterface $tippObject);

    public function updateZusatztipp($id, $status);

    public function addZusatztipp($id, $user_id, $m_id);

    public function isActive();
}