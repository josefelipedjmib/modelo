<?php


namespace AdminSocial;

return array(

    'controllers' => array(
        'invokables' => array(
            // 'Admin\Controller\Configuration' => "Admin\Controller\ConfigurationController",
        )
    ),

    'router' => array(
        'routes' => array(
            
            'admin-social' => array(
              'type' => 'Segment',
                'options' => array(
                    'route'=>'/social[/][:provedor[/]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminSocial\Controller',
                        'controller' => 'Auth',
                        'action' => 'social'
                    )
                )
            ),
            
            
        )
    ),



    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    "doctrine" => array(
        "driver" => array(
            __NAMESPACE__ . "_driver" => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . "\Entity" => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
