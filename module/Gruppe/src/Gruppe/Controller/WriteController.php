<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:35
 */

namespace Gruppe\Controller;

use Gruppe\Service\GruppeServiceInterface;
use Benutzer\Service\BenutzerServiceInterface;
use ZfcUser\Entity\UserInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class WriteController extends AbstractActionController {

    protected $gruppeService;
    protected $gruppeForm;
    protected $benutzerService;
    protected $inviteGruppeForm;

    public function __construct(
        GruppeServiceInterface $gruppeService,
        FormInterface $gruppeForm,
        BenutzerServiceInterface $benutzerService,
        FormInterface $inviteGruppeForm
    ) {
        $this->gruppeService = $gruppeService;
        $this->gruppeForm = $gruppeForm;
        $this->benutzerService = $benutzerService;
        $this->inviteGruppeForm = $inviteGruppeForm;
    }

    public function addAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->gruppeForm->setData($request->getPost());

            if ($this->gruppeForm->isValid()) {
                try {
                	
                    $this->gruppeService->saveGruppe($this->gruppeForm->getData());

                    return $this->redirect()->toRoute('gruppe');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->gruppeForm;

        return array(
            'form' => $form
        );
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Prüfen, ob User_id des aktuellen Users mi ID in der Gruppen Tabelle (leiter) uebereinstimmt
     */
    public function editAction()
    {
        $request = $this->getRequest();
        $gruppe  = $this->gruppeService->findGruppe($this->params('g_id'));

        $this->gruppeForm->bind($gruppe);

        if ($request->isPost()) {
            $this->gruppeForm->setData($request->getPost());

            if ($this->gruppeForm->isValid()) {
                try {
                    $this->gruppeService->saveGruppe($gruppe);

                    return $this->redirect()->toRoute('gruppe');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->gruppeForm
        );
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Checken, ob der eingeloggte Benuzter (der Inviter) Ersteller der Gruppe ist
     * @todo: Checken, ob der zu einladende Benutzer, schon Mitglied der Gruppe ist
     * @todo: Evtl. Absender (der Inviter) noch hinzufügen, sodass ersichtlich ist wer eingeladen hat
     */
    public function inviteAction()
    {
        $g_id = $this->params()->fromRoute('id');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->inviteGruppeForm->setData($request->getPost());

            if ($this->inviteGruppeForm->isValid()) {
                try {

                    $benutzer  = $this->benutzerService->findBenutzer($this->inviteGruppeForm->get('username')->getValue());
                    //return print_r($benutzer['user_id']);
                    /**
                    if ($benutzer instanceof UserInterface) {
                        $this->benutzerService->inviteBenutzer($g_id, $benutzer->getId());
                        return $this->redirect()->toRoute('gruppe');
                    }**/

                    if ($this->benutzerService->inviteBenutzer($g_id, $benutzer['user_id'])) {
                        return $this->redirect()->toRoute('gruppe');
                    }

                    //return print('Fehler!');
                } catch (\InvalidArgumentException $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->inviteGruppeForm;

        return array(
            'form' => $form
        );
    }
    
    

}