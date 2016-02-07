<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 11:13
 */

namespace Mannschaft\Mapper;

use Mannschaft\Model\MannschaftInterface;

/**
 * Interface MannschaftMapperInterface
 * @package Mannschaft\Mapper
 */
interface MannschaftMapperInterface {

    /**
     * @param $m_id
     * @return MannschaftInterface
     */
    public function find($m_id);

    /**
     * @return array|MannschaftInterface[]
     */
    public function findAll();

    /**
     * @param MannschaftInterface $mannschaftObject
     * @return MannschaftInterface
     */
    public function save(MannschaftInterface $mannschaftObject);

    /**
     * @param $m_id
     * @return mixed
     */
    public function findName($m_id);

    /**
     * @param $name
     * @return mixed
     */
    public function findId($name);

    /**
     * @return mixed
     */
    public function crawl();

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return mixed
     */
    public function count();
}