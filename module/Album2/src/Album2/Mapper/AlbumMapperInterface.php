<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 17.11.2015
 * Time: 11:13
 */

namespace Album2\Mapper;

use Album2\Model\AlbumInterface;

interface AlbumMapperInterface {

    /**
     * @param int|string $id
     * @return AlbumInterface
     * @throws \InvalidArgumentException
     */
    public function find($id);

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
    public function save(AlbumInterface $albumObject);
}