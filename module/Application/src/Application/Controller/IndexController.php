<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Spiel\Service\SpielServiceInterface;
use Gruppe\Service\GruppeServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    protected $spielService;
    protected $gruppeService;

    /**
     * @param SpielServiceInterface $spielService
     * @param GruppeServiceInterface $gruppeService
     */
    public function __construct(
        SpielServiceInterface $spielService,
        GruppeServiceInterface $gruppeService
    )
    {
        $this->spielService = $spielService;
        $this->gruppeService = $gruppeService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $turnierstatus = $this->spielService->turnierStatus();
        $modus = $this->spielService->getModus();

        switch ($modus[0]['modus']) {
            case 0:
                $mod = 'Vor Turnier';
                break;
            case 1:
                $mod = 'Vorrunde';
                break;
            case 2:
                $mod = 'Achtelfinale';
                break;
            case 3:
                $mod = 'Viertelfinale';
                break;
            case 4:
                $mod = 'Halbfinale';
                break;
            case 5:
                $mod = 'Finale';
                break;
        }

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        }

        $einladungen = count($this->gruppeService->findAllEinladungen($user_id));

        return new ViewModel(array(
                'turnierstatus' => $turnierstatus[0]['status'],
                'mod' => $mod,
                'modus' => $modus[0]['modus'],
                'einladungen' => $einladungen
            )
        );
    }
}
