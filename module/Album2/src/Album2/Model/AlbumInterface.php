<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 15:51
 */

namespace Album2\Model;

interface AlbumInterface {
    /**
     * Will return the ID of the Album
     *
     * @return int
     */
    public function getId();

    /**
     * Will return the TITLE of the Album
     *
     * @return string
     */
    public function getTitle();

    /**
     * Will return the ARTIST of the Album
     *
     * @return string
     */
    public function getArtist();
}