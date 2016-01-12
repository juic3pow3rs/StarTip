<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:47
 */

namespace Gruppe\Service;

use Gruppe\Model\GruppeInterface;

interface GruppeServiceInterface {
    /**
     * Should return a set of all albums that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Album\Model\Album
     *
     * @return array|\Traversable
     */
    public function findAllGruppen($user_id);

    /**
     * Should return a single Gruppe
     *
     * @param  int $id Identifier of the Album that should be returned
     * @return \Album\Model\Album
     */
    public function findGruppe($g_id);
        
    
    public function findUser($username, $g_id);
    
   
    public function findAllEinladungen($user_id);

    /**
     * Should save a given implementation of the AlbumInterface and return it. If it is an existing Album the Album
     * should be updated, if it's a new Album it should be created.
     *
     * @param  AlbumInterface $album
     * @return AlbumInterface
     */
    public function saveGruppe(GruppeInterface $gruppe);
    
    
    public function annehmen($user_id, $g_id);
    
    
    public function ablehnen($user_id, $g_id);

    public function isAdmin($user_id, $g_id);

    public function isMitglied($user_id, $g_id);

    public function compare($g_id);
    
    public function bereitsEingeladen($user_id, $g_id);

}