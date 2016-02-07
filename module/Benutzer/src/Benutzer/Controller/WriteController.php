<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:35
 */

namespace Benutzer\Controller;

use Benutzer\Form\PictureInputFilter;
use Benutzer\Service\BenutzerServiceInterface;
use ZfcUser\Entity\UserInterface;
use Zend\Form\FormInterface;
use Zend\Filter\File\RenameUpload;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Zend\View\Model\ViewModel as ViewM;

/**
 * Class WriteController
 * @package Benutzer\Controller
 */
class WriteController extends AbstractActionController {

    protected $benutzerService;
    protected $suchBenutzerForm;
    protected $pictureForm;

    /**
     * @param BenutzerServiceInterface $benutzerService
     * @param FormInterface $suchBenutzerForm
     * @param FormInterface $pictureForm
     */
    public function __construct(
        BenutzerServiceInterface $benutzerService,
        FormInterface $suchBenutzerForm,
        FormInterface $pictureForm
    ) {
       	$this->benutzerService = $benutzerService;
        $this->suchBenutzerForm = $suchBenutzerForm;
        $this->pictureForm = $pictureForm;
    }


    /**
     * Benutzersuche
     * @return array|ViewM
     */
    public function sucheAction()
    {
        
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->suchBenutzerForm->setData($request->getPost());

            if ($this->suchBenutzerForm->isValid()) {
                try {
					$benutzer = array();
                    $benutzer  = $this->benutzerService->suchBenutzer($this->suchBenutzerForm->get('username')->getValue());

					$anzahl = count($benutzer);

                    // Gefundene Benutzer und die Anzahl an das ViewModel übergeben
         		    $viewM =  new ViewM(array(
                        'benutzer' => $benutzer,
         		    	'anzahl' => $anzahl
                    ));
                    //Das Template des ViewModels setzen und zurückgeben
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

    /**
     * Benutzer Avatar setzen
     * @return array
     */
    public function profilePictureAction() {

        $request = $this->getRequest();

        $user  = $this->zfcUserAuthentication()->getIdentity();
        $id = $user->getId();

        if ($request->isPost()) {

            /**
             * Filter zum Validieren der URL, die Eingegeben wurde
             */
            $inputFilter = new PictureInputFilter();
            $inputFilter->init();
            $this->pictureForm->setInputFilter($inputFilter);

            $this->pictureForm->setData($request->getPost());

            if ($this->pictureForm->isValid()) {
                try {
                    $url = $this->pictureForm->get('image-url')->getValue();

                    $this->benutzerService->setAva($id, $url);

                    $this->redirect()->toRoute('user');

                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->pictureForm;

        return array(
            'form' => $form
        );

    }
}