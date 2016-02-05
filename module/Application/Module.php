<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\View\Helper\Navigation;

/**
 * Class Module
 * @package Application
 */
class Module
{
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @param $e
     */
    public function onBootstrap($e)
    {
        /**$sm = $e->getApplication()->getServiceManager();

        // Add ACL information to the Navigation view helper
        $authorize = $sm->get('BjyAuthorizeServiceAuthorize');
        $acl = $authorize->getAcl();
        $role = $authorize->getIdentity();
        Navigation::setDefaultAcl($acl);
        Navigation::setDefaultRole($role);
        **/

        $sm   = $e->getApplication()->getServiceManager();
        $auth = $sm->get('BjyAuthorize\Service\Authorize');

        $acl  = $auth->getAcl();
        $role = $auth->getIdentity();
        \Zend\View\Helper\Navigation::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation::setDefaultRole($role);

        /**
        $em = $e->getApplication()->getEventManager();
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($e) {
            $messenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
            if ($messenger->hasMessages()) {
                $messages = $messenger->getMessages();
                $e->getViewModel()->setVariable('flashMessages', $messages);
            }
        });
         **/
    }
}
