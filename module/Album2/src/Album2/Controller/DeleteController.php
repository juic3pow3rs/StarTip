<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.11.2015
 * Time: 16:23
 */

namespace Album2\Controller;

use Album2\Service\AlbumServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DeleteController
 * @package Album2\Controller
 */
class DeleteController extends AbstractActionController
{
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
     * @return \Zend\Http\Response|ViewModel
     */
    public function deleteAction()
    {
        try {
            $album = $this->albumService->findAlbum($this->params('id'));
        } catch (\InvalidArgumentException $e) {
            return $this->redirect()->toRoute('album2');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('delete_confirmation', 'no');

            if ($del === 'yes') {
                $this->albumService->deleteAlbum($album);
            }

            return $this->redirect()->toRoute('album2');
        }

        return new ViewModel(array(
            'album' => $album
        ));
    }
}