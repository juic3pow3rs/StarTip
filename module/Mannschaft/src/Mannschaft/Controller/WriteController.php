<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 17:35
 */

namespace Mannschaft\Controller;

use Mannschaft\Model\Mannschaft;
use Mannschaft\Service\MannschaftServiceInterface;
use Spiel\Service\SpielServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

/**
 * Class WriteController
 * @package Mannschaft\Controller
 */
class WriteController extends AbstractActionController {

    protected $mannschaftService;
    protected $mannschaftForm;
    protected $spielService;

    /**
     * @param MannschaftServiceInterface $mannschaftService
     * @param FormInterface $mannschaftForm
     * @param SpielServiceInterface $spielService
     */
    public function __construct(
        MannschaftServiceInterface $mannschaftService,
        FormInterface $mannschaftForm,
        SpielServiceInterface $spielService
    ) {
        $this->mannschaftService = $mannschaftService;
        $this->mannschaftForm = $mannschaftForm;
        $this->spielService = $spielService;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction() {

        $status = $this->spielService->turnierStatus();

        if ($status[0]['status'] == 1) {

            $this->flashMessenger()->addErrorMessage('Mannschaft anlegen nicht mehr moeglich. Turnier bereits aktiviert');

            return  $this->redirect()->toRoute('zfcadmin');
        }

        $anzahl = $this->mannschaftService->count();

        if ($anzahl[0]['num'] == 24) {

            $this->flashMessenger()->addErrorMessage('Das Maximum an Mannschaften (24) wurde bereits erreicht!');

            return  $this->redirect()->toRoute('zfcadmin');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->mannschaftForm->setData($request->getPost());

            $name = $this->mannschaftForm->get('mannschaft-fieldset')->get('name')->getValue();

            $double = $this->mannschaftService->findId($name);

            $check = 0;

            if (!empty($double)) {

                $this->mannschaftForm->get('mannschaft-fieldset')->get('name')->setMessages(array('Mannschaft mit Namen '.$name.' existiert bereits!'));

                $check = 1;
            }

            if ($this->mannschaftForm->isValid() && $check == 0) {
                try {
                    //\Zend\Debug\Debug::dump($this->mannschaftForm->getData());die();
                    $this->mannschaftService->saveMannschaft($this->mannschaftForm->getData());

                    $this->flashMessenger()->addSuccessMessage('Mannschaft erfolgreich angelegt!');

                    return $this->redirect()->toRoute('zfcadmin');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->mannschaftForm;

        return array(
            'form' => $form
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {

        $status = $this->spielService->turnierStatus();

        if ($status[0]['status'] == 1) {

            $this->flashMessenger()->addErrorMessage('Mannschaften editieren nicht mehr moeglich. Turnier bereits aktiviert');

            return  $this->redirect()->toRoute('zfcadmin');
        }

        $request = $this->getRequest();
        $mannschaft  = $this->mannschaftService->findMannschaft($this->params('id'));

        $this->mannschaftForm->bind($mannschaft);

        if ($request->isPost()) {
            $this->mannschaftForm->setData($request->getPost());

            $name = $this->mannschaftForm->get('mannschaft-fieldset')->get('name')->getValue();

            $double = $this->mannschaftService->findId($name);

            $check = 0;

            if (!empty($double)) {

                $this->mannschaftForm->get('mannschaft-fieldset')->get('name')->setMessages(array('Mannschaft mit Namen '.$name.' existiert bereits!'));

                $check = 1;
            }

            if ($this->mannschaftForm->isValid() && $check == 0) {
                try {
                    $this->mannschaftService->saveMannschaft($mannschaft);

                    return $this->redirect()->toRoute('zfcadmin');

                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->mannschaftForm
        );
    }

    /**
     * @return \Zend\Http\Response
     */
    public function crawlAction()
    {
        $status = $this->spielService->turnierStatus();

        if ($status[0]['status'] == 1) {

            $this->flashMessenger()->addErrorMessage('Mannschaften crawlen nicht mehr moeglich. Turnier bereits aktiviert');

            return  $this->redirect()->toRoute('zfcadmin');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {

            $crwl = $request->getPost('crawl_confirmation', 'nein');

            if ($crwl === 'ja') {

                $mannschaften = $this->mannschaftService->crawl();

                $this->mannschaftService->delete();

                foreach ($mannschaften as $m) {

                    $team = new Mannschaft();

                    $team->setName($m[0]);
                    $team->setKuerzel($m[1]);
                    $team->setGruppe($m[2]);

                    $this->mannschaftService->saveMannschaft($team);
                }


                $this->flashMessenger()->addSuccessMessage('Crawlen erfolgreich!');

                return $this->redirect()->toRoute('zfcadmin');
            }

            return $this->redirect()->toRoute('mannschaft');
        }

    }

}