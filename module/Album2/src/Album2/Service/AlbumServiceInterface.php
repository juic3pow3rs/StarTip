<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 15:47
 */

namespace Album2\Service;

use Album2\Model\AlbumInterface;

/**
 * Interface AlbumServiceInterface
 * @package Album2\Service
 */
interface AlbumServiceInterface {
    /**
     * Should return a set of all albums that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Album\Model\Album
     *
     * @return array|\Traversable
     */
    public function findAllAlbums();

    /**
     * Should return a single album
     *
     * @param  int $id Identifier of the Album that should be returned
     * @return \Album\Model\Album
     */
    public function findAlbum($id);

    /**
     * Should save a given implementation of the AlbumInterface and return it. If it is an existing Album the Album
     * should be updated, if it's a new Album it should be created.
     *
     * @param  AlbumInterface $album
     * @return AlbumInterface
     */
    public function saveAlbum(AlbumInterface $album);

    /**
     * Should delete a given implementation of the AlbumInterface and return true if the deletion has been
     * successful or false if not.
     *
     * @param  AlbumInterface $album
     * @return bool
     */
    public function deleteAlbum(AlbumInterface $album);
}