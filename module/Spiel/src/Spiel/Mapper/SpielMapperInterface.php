<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:01
 */

namespace Spiel\Mapper;

use Spiel\Model\SpielInterface;

/**
 * Interface SpielMapperInterface
 * @package Spiel\Mapper
 */
interface SpielMapperInterface {

    /**
     * @param $s_id
     * @return SpielInterface
     */
    public function find($s_id);

    /**
     * @param $s_id
     * @return mixed
     */
    public function spielStatus($s_id);

    /**
     * @return array|SpielInterface[]
     */
    public function findAll();

    /**
     * @param SpielInterface $spielObject
     *
     * @param SpielInterface $spielObject
     * @return SpielInterface
     * @throws \Exception
     */
    public function save(SpielInterface $spielObject);

    /**
     * @param $user_id
     * @return bool
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