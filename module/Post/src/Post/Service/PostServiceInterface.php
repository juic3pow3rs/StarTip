<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 10:02
 */

namespace Post\Service;

use Post\Model\PostInterface;

/**
 * Interface PostServiceInterface
 * @package Post\Service
 */
interface PostServiceInterface {
    
    public function findAllPosts();

    /**
     * @param $g_id
     * @return mixed
     */
    public function findPost($g_id);

    /**
     * @param PostInterface $post
     * @param $g_id
     * @return mixed
     */
    public function savePost(PostInterface $post, $g_id);

}