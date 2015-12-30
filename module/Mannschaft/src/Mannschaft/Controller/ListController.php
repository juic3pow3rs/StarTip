<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 13:50
 */

namespace Mannschaft\Controller;

use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Mannschaft\Service\MannschaftServiceInterface
     */
    protected $mannschaftService;

    public function __construct(MannschaftServiceInterface $mannschaftService)
    {
        $this->mannschaftService = $mannschaftService;
    }

    public function indexAction()
    {
            return new ViewModel(array(
                'mannschaften' => $this->mannschaftService->findAllMannschaften()
            ));
    }

}