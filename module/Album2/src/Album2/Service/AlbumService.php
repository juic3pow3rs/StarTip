<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 15:48
 */

namespace Album2\Service;

use Album2\Mapper\AlbumMapperInterface;
use Album2\Model\AlbumInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class AlbumService implements AlbumServiceInterface {

    protected $albumMapper;

    /**
     * @param AlbumMapperInterface $albumMapper
     */
    public function __construct(AlbumMapperInterface $albumMapper)
    {
        $this->albumMapper = $albumMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllAlbums()
    {
        return $this->albumMapper->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findAlbum($id)
    {
        return $this->albumMapper->find($id);
    }

    /**
     * @inheritDoc
     */
    public function saveAlbum(AlbumInterface $album)
    {
        return $this->albumMapper->save($album);
    }
}