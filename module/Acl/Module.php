<?php

namespace Acl;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__=> __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {

        return array(
            'factories' => array(
                'Acl\Form\Role' => function ($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    $repo = $em->getRepository('Acl\Entity\Role');
                    $parent = $repo->fetchParent();
                    unset($parent[0]);

                    return new Form\Role('role', $parent);
                },
                'Acl\Form\Privilege' => function ($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    $repoRoles = $em->getRepository('Acl\Entity\Role');
                    $roles = $repoRoles->fetchParent();
                    unset($roles[0]);

                    $repoResources = $em->getRepository('Acl\Entity\Resource');
                    $resources = $repoResources->fetchPairs();

                    return new Form\Privilege("privilege", $roles, $resources);
                },
                'Acl\Form\Resource' => function ($sm) {
                    return new Form\Resource('resource');
                },

                'Acl\Service\Role' => function ($sm) {
                    return new Service\Role($sm);
                },
                'Acl\Service\Resource' => function ($sm) {
                    return new Service\Resource($sm);
                },
                'Acl\Service\Privilege' => function ($sm) {
                    return new Service\Privilege($sm);
                },

                'Acl\Permissions\Acl' => function ($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');

                    $repoRole = $em->getRepository("Acl\Entity\Role");
                    $roles = $repoRole->findAll();

                    $repoResource = $em->getRepository("Acl\Entity\Resource");
                    $resources = $repoResource->findAll();

                    $repoPrivilege = $em->getRepository("Acl\Entity\Privilege");
                    $privileges = $repoPrivilege->findAll();

                    return new Permissions\Acl($sm, $roles, $resources, $privileges);
                },
            ),
        );

    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                'Acl\Controller\Roles' => function ($sm) {
                    $rolesController = new Controller\RolesController($sm->getServiceLocator());
                    return $rolesController;
                },
                'Acl\Controller\Resources' => function ($sm) {
                    $resourcesController = new Controller\ResourcesController($sm->getServiceLocator());
                    return $resourcesController;
                },
                'Acl\Controller\Privileges' => function ($sm) {
                    $privilegesController = new Controller\PrivilegesController($sm->getServiceLocator());
                    return $privilegesController;
                },
            ],
        ];
    }

}
