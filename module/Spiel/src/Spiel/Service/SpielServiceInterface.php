<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 10:02
 */

namespace Spiel\Service;

use Spiel\Model\SpielInterface;

/**
 * Interface SpielServiceInterface
 * @package Spiel\Service
 */
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
     * @return \Spiel\Model\Spiel
     */
    public function findSpiel($s_id);

    /**
     * @param $s_id
     * @return mixed
     */
    public function spielStatus($s_id);

    /**
     * Should save a given implementation of the SpielInterface and return it. If it is an existing Spiel the Spiel
     * should be updated, if it's a new Spiel it should be created.
     *
     * @param SpielInterface $spiel
     * @return SpielInterface
     */
    public function saveSpiel(SpielInterface $spiel);

    /**
     * @param $user_id
     * @return mixed
     */
    public function findTippSpiele($user_id);

    /**
     * @param $modus
     * @return mixed
     */
    public function findModusSpiele($modus);

    /**
     * @return mixed
     */
    public function activateTurnier();

    /**
     * @return mixed
     */
    public function turnierStatus();

    /**
     * @param $m
     * @return mixed
     */
    public function setModus($m);

    /**
     * @return mixed
     */
    public function getModus();

    /**
     * @param $modus
     * @return mixed
     */
    public function crawl($modus);

    /**
     * @param $modus
     * @return mixed
     */
    public function deleteModus($modus);

    /**
     * @param $modus
     * @return mixed
     */
    public function count($modus);

    /**
     * @return mixed
     */
    public function reset();
  
}