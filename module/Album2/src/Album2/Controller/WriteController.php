<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 15.11.2015
 * Time: 17:35
 */

namespace Album2\Controller;

use Album2\Service\AlbumServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class WriteController extends AbstractActionController {

    protected $albumService;
    protected $albumForm;

    public function __construct(
        AlbumServiceInterface $albumService,
        FormInterface $albumForm
    ) {
        $this->albumService = $albumService;
        $this->albumForm = $albumForm;
    }

    public function addAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->albumForm->setData($request->getPost());

            if ($this->albumForm->isValid()) {
                try {
                    $this->albumService->saveAlbum($this->albumForm->getData());

                    return $this->redirect()->toRoute('album2');
                } catch (\Exception $e) {
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->albumForm;

        return array(
            'form' => $form
        );
    }
}