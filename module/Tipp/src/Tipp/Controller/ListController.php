<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 13.12.2015
 * Time: 15:40
 */

namespace Tipp\Controller;

use Tipp\Service\TippServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Album2\Service\AlbumServiceInterface
     */
    protected $tippService;

    public function __construct(TippServiceInterface $tippService)
    {
        $this->tippService = $tippService;
    }

    public function indexAction()
    {
            return new ViewModel(array(
                'tipps' => $this->tippService->findAllTipps()
            ));
    }

    public function detailAction()
    {
        $t_id = $this->params()->fromRoute('t_id');

        try {
            $tipp= $this->tippService->findTipp($t_id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('tipp');
        }

        return new ViewModel(array(
            'tipp' => $tipp
        ));
    }
}