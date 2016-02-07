<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:01
 */

namespace Post\Mapper;

use Post\Model\PostInterface;

/**
 * Interface PostMapperInterface
 * @package Post\Mapper
 */
interface PostMapperInterface {

    /**
     * @param $g_id
     * @return PostInterface
     */
    public function find($g_id);

    /**
     * @return array|PostInterface[]
     */
    public function findAll();

    /**
     * @param PostInterface $postObject
     * @param $g_id
     * @return PostInterface
     */
    public function save(PostInterface $postObject, $g_id);

}