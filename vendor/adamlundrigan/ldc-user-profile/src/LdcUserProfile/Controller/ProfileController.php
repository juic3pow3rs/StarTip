<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Controller;

use LdcUserProfile\Options\ModuleOptions;
use LdcUserProfile\Service\ProfileService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @method \ZfcUser\Controller\Plugin\ZfcUserAuthentication zfcUserAuthentication()
 */
class ProfileController extends AbstractActionController
{
    protected $moduleOptions;

    protected $profileService;

    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login', array(), array('query' => array('redirect' => 'ldc-user-profile')));
        }

        $form = $this->getService()->constructFormForUser($this->zfcUserAuthentication()->getIdentity());

        // Setzt den Username und die E-Mail auf readonly, da diese nicht ver�ndert werden d�rfen
        $form->get('zfcuser')->get('username')->setAttributes(array('readonly' => 'readonly'));
        $form->get('zfcuser')->get('email')->setAttributes(array('readonly' => 'readonly'));

        $vm = new ViewModel(array(
            'profileForm' => $form,
            'options'     => $this->getModuleOptions(),
        ));

        $prg = $this->fileprg($form, $this->url()->fromRoute('ldc-user-profile'), true);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return $vm;
        }

        $fm = $this->flashMessenger()->setNamespace('ldc-user-profile');

        // Ensure that the user can't change the account ID during update
        $prg['zfcuser']['id'] = $this->zfcUserAuthentication()->getIdentity()->getId();

        if (! $this->getService()->validate($form, $prg)) {
            $fm->addErrorMessage('One or more of the values you provided is invalid.');

            return $vm;
        }

        if (! $this->getService()->save($form->getData())) {
            $fm->addErrorMessage('There was a problem saving your profile update.');

            return $vm;
        }

        $fm->addSuccessMessage('Profile updated successfully!');

        return $this->redirect()->toRoute('ldc-user-profile');
    }

    public function setService(ProfileService $svc)
    {
        $this->profileService = $svc;

        return $this;
    }

    public function getService()
    {
        if (! $this->profileService instanceof ProfileService) {
            $this->profileService = $this->getServiceLocator()->get(
               'ldc-user-profile_service'
            );
        }

        return $this->profileService;
    }

    public function setModuleOptions(ModuleOptions $obj)
    {
        $this->moduleOptions = $obj;

        return $this;
    }

    public function getModuleOptions()
    {
        if (! $this->moduleOptions instanceof ModuleOptions) {
            $this->moduleOptions = $this->getServiceLocator()->get(
               'ldc-user-profile_module_options'
            );
        }

        return $this->moduleOptions;
    }
}
