<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:01
 */

namespace Post\Mapper;

use Post\Model\PostInterface;

interface PostMapperInterface {

    /**
     * @param int|string $id
     * @return PostInterface
     * @throws \InvalidArgumentException
     */
    public function find($g_id);

    /**
     * @return array|PostInterface[]
     */
    public function findAll();

    /**
     * @param PostInterface $postObject
     *
     * @param PostInterface $postObject
     * @return PostInterface
     * @throws \Exception
     */
    public function save(PostInterface $postObject, $g_id);

    /**
     * @param PostInterface $postObject
     *
     * @return bool
     * @throws \Exception
     */
    
}