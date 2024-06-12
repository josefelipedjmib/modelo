<?php


namespace Admin;

return array(

    'servicos' => [
                [
                    'nome' => 'Usuários',
                    'url' => array('admin-admin/default',array('controller'=>'usuarios')),
                    'icon' => '<i class="fa fa-users"></i>'
                ],
                [
                    'nome' => 'Lista de Controle de Acesso',
                    'url' => array('acl-admin'),
                    'icon' => '<i class="fa fa-id-badge"></i>'
                ],
            ],

    'controllers' => array(
        'invokables' => array(
            // 'Admin\Controller\Configuration' => "Admin\Controller\ConfigurationController",
        )
    ),

    'router' => array(
        'routes' => array(
            
            'teste'=> array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/mibteste/?',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action'     => 'teste',
                    ),
                    'spec' => '/teste'
                ),     
            ),

            'termosdeservico' => array(
                'type' => 'Segment',
                  'options' => array(
                      'route'=>'/termosdeservico[/]',
                      'defaults' => array(
                          '__NAMESPACE__' => 'Admin\Controller',
                          'controller' => 'Index',
                          'action' => 'termosdeservico'
                      )
                  )
              ),

            'faleconosco' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'=>'/faleconosco[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'faleconosco'
                    )
                )
            ),
            'politicadeprivacidade' => array(
                'type' => 'Segment',
                    'options' => array(
                        'route'=>'/politicadeprivacidade[/]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Admin\Controller',
                            'controller' => 'Index',
                            'action' => 'politicadeprivacidade'
                        )
                    )
                ),
                'termosdeservico' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route'=>'/termosdeservico[/]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Admin\Controller',
                            'controller' => 'Index',
                            'action' => 'termosdeservico'
                        )
                    )
                ),
            
            'admin-default' => array(
                'type' => 'Segment',
                  'options' => array(
                      'route'=>'/',
                      'defaults' => array(
                          '__NAMESPACE__' => 'Admin\Controller',
                          'controller' => 'Auth',
                          'action' => 'index'
                      )
                  )
              ),

            'busca-enderecos' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/buscaenderecos[/:busca]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Configuration',
                        'action' => 'buscaenderecos',
                    )
                )
            ),
            'upload-endpoint' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/uploadendpoint[/:uuid]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Configuration',
                        'action' => 'upload',
                    )
                )
            ),
            'preview-link' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/previewlink[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Configuration',
                        'action' => 'previewlink',
                    )
                )
            ),
            'check-login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/checklogin[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Configuration',
                        'action' => 'checklogin',
                    )
                )
            ),
            

            'admin-register' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cadastro[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'register',
                    )
                )
            ),
            'admin-activate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cadastro/ativa[/:key]',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'activate'
                    )
                )
            ),
            'admin-email' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cadastro/email[/]',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'senha'
                    )
                )
            ),
            'admin-auth' => array(
              'type' => 'Segment',
                'options' => array(
                    'route'=>'/entrar[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Auth',
                        'action' => 'index'
                    )
                )
            ),
            'admin-logout' => array(
              'type' => 'Segment',
                'options' => array(
                    'route'=>'/sair[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Auth',
                        'action' => 'logout'
                    )
                )
            ),
            
            'admin-admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/admin[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller[/][:action[/][:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Admin\Controller',
                                'controller' => 'Index'
                            )
                        )
                    ),
                    'paginator' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller[[/]:action][[/]pagina/:page]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '(?!pagina)[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '\d+'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Admin\Controller',
                                'controller' => 'Index'
                            )
                        )
                    ),
                    'telefoneouenderecoououtros' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller/edit/:id/:action/[:recursoid]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '(?!pagina)[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]+',
                                'recursoid' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Admin\Controller',
                                'controller' => 'Index'
                            )
                        )
                    )
                ),
                'priority' => -1,  
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
    'data-fixture' => array(
        'Admin_fixture' => __DIR__ . "/../src/Admin/Fixture",
    )
);
