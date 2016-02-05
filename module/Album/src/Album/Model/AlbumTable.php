<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 04.11.2015
 * Time: 13:27
 */

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Class AlbumTable
 * @package Album\Model
 */
class AlbumTable {
    protected $tableGateway;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @param $id
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function getAlbum($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could no find row $id");
        }
        return $row;
    }

    /**
     * @param Album $album
     * @throws \Exception
     */
    public function saveAlbum(Album $album) {
        $data = array(
            'artist' => $album->artist,
            'title' => $album->title,
        );

        $id = (int) $album->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception("Album id does not exist");
            }
        }
    }

    /**
     * @param $id
     */
    public function deleteAlbum($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}