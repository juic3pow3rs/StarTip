<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:35
 */

namespace Benutzer\Controller;

use Benutzer\Service\BenutzerServiceInterface;
use ZfcUser\Entity\UserInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Zend\View\Model\ViewModel as ViewM;

class WriteController extends AbstractActionController {

    protected $benutzerService;
    protected $suchBenutzerForm;

    public function __construct(
        BenutzerServiceInterface $benutzerService,
        FormInterface $suchBenutzerForm
    ) {
       	$this->benutzerService = $benutzerService;
        $this->suchBenutzerForm = $suchBenutzerForm;
    }

   
    public function sucheAction()
    {
        
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->suchBenutzerForm->setData($request->getPost());

            if ($this->suchBenutzerForm->isValid()) {
                try {
					$benutzer =array();
                    $benutzer  = $this->benutzerService->suchBenutzer($this->suchBenutzerForm->get('username')->getValue());


         		    $viewM =  new ViewM(array(
                        'benutzer' => $benutzer
                    ));
                    $viewM->setTemplate('benutzer/write/ergebnis.phtml');
                    return $viewM;
                   
                } catch (\InvalidArgumentException $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->suchBenutzerForm;

        return array(
            'form' => $form
        );
    }
    
    
    

}