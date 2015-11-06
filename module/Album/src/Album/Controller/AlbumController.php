<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 03.11.2015
 * Time: 17:41
 */

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class AlbumController extends AbstractActionController {
    protected $albumTable;

    public function indexAction() {
        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
            )
        );
    }

    public function addAction() {
    }

    public function editAction() {
    }

    public function deleteAction() {
    }

    public function getAlbumTable() {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}