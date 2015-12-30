<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 10:02
 */

namespace Spiel\Service;

use Spiel\Model\SpielInterface;

interface SpielServiceInterface {
    /**
     * Should return a set of all Spiele that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Spiel\Model\Spiel
     *
     * @return array|\Traversable
     */
    public function findAllSpiele();

    /**
     * Should return a single Spiel
     *
     * @param  int $s_id Identifier of the Spiel that should be returned
     * @return \Spiel\Model\Album
     */
    public function findSpiel($s_id);

    /**
     * Should save a given implementation of the SpielInterface and return it. If it is an existing Spiel the Spiel
     * should be updated, if it's a new Spiel it should be created.
     *
     * @param  SpielInterface $Spiel
     * @return SpielInterface
     */
    public function saveSpiel(SpielInterface $spiel);

  
}