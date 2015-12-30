<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 15:47
 */

namespace Tipp\Service;

use Tipp\Model\TippInterface;

interface TippServiceInterface {
    /**
     * Should return a set of all albums that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Album\Model\Album
     *
     * @return array|\Traversable
     */
    public function findAllTipps();

    /**
     * Should return a single album
     *
     * @param  int $id Identifier of the Album that should be returned
     * @return \Album\Model\Album
     */
    public function findTipp($t_id);

    /**
     * Should save a given implementation of the AlbumInterface and return it. If it is an existing Album the Album
     * should be updated, if it's a new Album it should be created.
     *
     * @param  AlbumInterface $album
     * @return AlbumInterface
     */
    public function saveTipp(TippInterface $tipp);

    public function updateZusatztipp($id, $status);

    public function addZusatztipp($id, $user_id, $m_id);

    public function isActive();
}