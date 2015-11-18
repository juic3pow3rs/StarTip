<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 15:40
 */

namespace Album2\Controller;

use Album2\Service\AlbumServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Album2\Service\AlbumServiceInterface
     */
    protected $albumService;

    public function __construct(AlbumServiceInterface $albumService)
    {
        $this->albumService = $albumService;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->albumService->findAllAlbums()
        ));
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $album = $this->albumService->findAlbum($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('album2');
        }

        return new ViewModel(array(
            'album' => $album
        ));
    }
}