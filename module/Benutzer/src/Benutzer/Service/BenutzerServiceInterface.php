<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 11:13
 */

namespace Benutzer\Service;

interface BenutzerServiceInterface {
    /**
     * Should return a set of all albums that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Benutzer\Model\Benutzer
     *
     * @return array|\Traversable
     */
    public function findAllBenutzer();

    /**
     * Should return a single album
     *
     * @param  int $id Identifier of the Album that should be returned
     * @return \Benutzer\Model\User
     */
    public function findBenutzer($id);

}