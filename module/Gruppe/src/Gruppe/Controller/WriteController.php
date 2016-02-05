<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:35
 */

namespace Gruppe\Controller;

use Gruppe\Model\Gruppe;
use Gruppe\Service\GruppeServiceInterface;
use Benutzer\Form\PictureInputFilter;
use Benutzer\Service\BenutzerServiceInterface;
use ZfcUser\Entity\UserInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

/**
 * Class WriteController
 * @package Gruppe\Controller
 */
class WriteController extends AbstractActionController {

    protected $gruppeService;
    protected $gruppeForm;
    protected $benutzerService;
    protected $inviteGruppeForm;
    protected $pictureForm;

    /**
     * @param GruppeServiceInterface $gruppeService
     * @param FormInterface $gruppeForm
     * @param BenutzerServiceInterface $benutzerService
     * @param FormInterface $inviteGruppeForm
     * @param FormInterface $pictureForm
     */
    public function __construct(
        GruppeServiceInterface $gruppeService,
        FormInterface $gruppeForm,
        BenutzerServiceInterface $benutzerService,
        FormInterface $inviteGruppeForm,
        FormInterface $pictureForm
    ) {
        $this->gruppeService = $gruppeService;
        $this->gruppeForm = $gruppeForm;
        $this->benutzerService = $benutzerService;
        $this->inviteGruppeForm = $inviteGruppeForm;
        $this->pictureForm = $pictureForm;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->gruppeForm->setData($request->getPost());
          
            //Prüft ob der Name der Tippgemeinschaft bereits existiert
            $name = $this->gruppeForm->get('gruppe-fieldset')->get('name')->getValue();
          	$pruef = $this->gruppeService->pruefGruppe($name);
          	if($pruef == 1){
          		$this->gruppeForm->get('gruppe-fieldset')->get('name')->setMessages(array('Name existiert bereits!'));
          	}
           
          	//Anlegen der neuen Tippgemeinschaft
            if ($this->gruppeForm->isValid() && $pruef == 0) {
                try {
					
                    $this->gruppeService->saveGruppe($this->gruppeForm->getData());

                    $this->flashMessenger()->addSuccessMessage('Die Tippgemeinschaft "'.$name.'" wurde erfolgreich angelegt.');
                    return $this->redirect()->toRoute('gruppe');

                } catch (\Exception $e) {
                    die($e->getMessage());
                    
                }
            }
        }

        $form = $this->gruppeForm;

        return array(
            'form' => $form,
        	'message' => $this->flashMessenger()->getMessages()
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $request = $this->getRequest();
        //Lädt die Infos der Tippgemeinschaft mit der g_id aus der Route
        $gruppe  = $this->gruppeService->findGruppe($this->params('g_id'));

        if($gruppe == 0){
            $this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
            return $this->redirect()->toRoute('gruppe');
        }

		$nameGruppe = $gruppe->getName();

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        }

        if ($gruppe->getUser_id() != $user_id) {

            $this->flashMessenger()->addErrorMessage('Keine Berechtigung!');

            return $this->redirect()->toRoute('gruppe');
        }
		
		//Übergibt die Infos der Gruppe an die View
        $this->gruppeForm->bind($gruppe);
        
       
        if ($request->isPost()) {
            $this->gruppeForm->setData($request->getPost());
           
            //Prüft ob der Name der Tippgemeinschaft bereits existiert
            $name=$this->gruppeForm->get('gruppe-fieldset')->get('name')->getValue();
            if($nameGruppe != $name){
            	$pruef=$this->gruppeService->pruefGruppe($name);
            }
            
            if($pruef == 1){
            	 $this->gruppeForm->get('gruppe-fieldset')->get('name')->setMessages(array('Name existiert bereits.'));
            }

            if ($this->gruppeForm->isValid() && $pruef == 0) {
                try {
                    $this->gruppeService->saveGruppe($gruppe);
                    $this->flashMessenger()->addSuccessMessage('Die Tippgemeinschaft wurde erfolgreich bearbeitet.');
                    return $this->redirect()->toRoute('gruppe/detail', array('g_id' => ($this->params('g_id'))));
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->gruppeForm,
        	'message' => $this->flashMessenger()->getMessages()
        );
    }

    /**
     * @return array|\Zend\Http\Response
    
     * @todo: Evtl. Absender (der Inviter) noch hinzufügen, sodass ersichtlich ist wer eingeladen hat
     */
    public function inviteAction()
    {
        $g_id = $this->params()->fromRoute('id');
        $request = $this->getRequest();

        $gruppe  = $this->gruppeService->findGruppe($g_id);

        if($gruppe == 0){
            $this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
            return $this->redirect()->toRoute('gruppe');
        }

        //Id des Users holen
        $user  = $this->zfcUserAuthentication()->getIdentity();
        $leiter = $user->getId();

        if ($gruppe->getUser_id() != $leiter) {

            $this->flashMessenger()->addErrorMessage('Keine Berechtigung!');

            return $this->redirect()->toRoute('gruppe');
        }
        
        if ($request->isPost()) {
            $this->inviteGruppeForm->setData($request->getPost());

            if ($this->inviteGruppeForm->isValid()) {
                try {

                    $benutzer  = $this->benutzerService->findBenutzer($this->inviteGruppeForm->get('username')->getValue());
                   	$fehler=0;

                    if(($this->gruppeService->bereitsEingeladen($benutzer['user_id'], $g_id)) == true)
                    {
                  		$fehler = 1;

                        $this->flashMessenger()->addErrorMessage('Benutzer bereits eingeladen!');

                        return $this->redirect()->toRoute('gruppe/invite', array('id' => $g_id));

                    } else {

                        if ($fehler==0 && $this->benutzerService->inviteBenutzer($g_id, $benutzer['user_id'], $leiter)) {

                            $this->flashMessenger()->addSuccessMessage('Benutzer erfolgreich eingeladen!');

                            return $this->redirect()->toRoute('gruppe');
                        }
                    }

                } catch (\Exception $e) {

                    $this->flashMessenger()->addErrorMessage($e->getMessage());

                    return $this->redirect()->toRoute('gruppe/invite', array('id' => $g_id));
                }
            }
        }

        $form = $this->inviteGruppeForm;

        return array(
            'form' => $form,
        	'message' => $this->flashMessenger()->getMessages(),
        	'fehler' => $fehler
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function profilePictureAction() {

        $request = $this->getRequest();
        $g_id = $this->params('g_id');
        //Lädt die Infos der Tippgemeinschaft mit der g_id aus der Route
        $gruppe  = $this->gruppeService->findGruppe($g_id);

        if($gruppe == 0){
            $this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
            return $this->redirect()->toRoute('gruppe');
        }

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        }

        if ($gruppe->getUser_id() != $user_id) {

            $this->flashMessenger()->addErrorMessage('Keine Berechtigung!');

            return $this->redirect()->toRoute('gruppe');
        }

        $user  = $this->zfcUserAuthentication()->getIdentity();
        $id = $user->getId();

        if ($request->isPost()) {

            $inputFilter = new PictureInputFilter();
            $inputFilter->init();
            $this->pictureForm->setInputFilter($inputFilter);

            $this->pictureForm->setData($request->getPost());

            if ($this->pictureForm->isValid()) {
                try {
                    $url = $this->pictureForm->get('image-url')->getValue();

                    $this->gruppeService->setAva($g_id, $url);

                    return $this->redirect()->toRoute('gruppe');

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