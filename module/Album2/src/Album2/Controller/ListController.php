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

/**
 * Class ListController
 * @package Album2\Controller
 */
class ListController extends  AbstractActionController {

    /**
     * @var \Album2\Service\AlbumServiceInterface
     */
    protected $albumService;

    /**
     * @param AlbumServiceInterface $albumService
     */
    public function __construct(AlbumServiceInterface $albumService)
    {
        $this->albumService = $albumService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {

        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $return['messages'] = $flashMessenger->getMessages();
        }

        return new ViewModel(array(
            'albums' => $this->albumService->findAllAlbums(),
            'message' => $this->flashMessenger()->getMessages()
        ));
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
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