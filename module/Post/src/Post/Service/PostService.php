<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 10:15
 */

namespace Post\Service;

use Post\Mapper\PostMapperInterface;
use Post\Model\PostInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Class PostService
 * @package Post\Service
 */
class PostService implements PostServiceInterface {

    protected $postMapper;

    /**
     * @param PostMapperInterface $postMapper
     */
    public function __construct(PostMapperInterface $postMapper)
    {
        $this->postMapper = $postMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllPosts()
    {
        return $this->postMapper->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findPost($g_id)
    {
        return $this->postMapper->find($g_id);
    }

    /**
     * @inheritDoc
     */
    public function savePost(PostInterface $post, $g_id)
    {
        return $this->postMapper->save($post, $g_id);
    }

    
}