<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/User[/:action][/:id]',
                    'constraints' => array(
                        // 'resource'  =>  'User',
                        'action'    =>  'register|login|resetPassword|changePassword|checkAcl', 
                        'id'    =>  '[a-zA-Z][a-zA-Z0-9_-]+',  
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'User',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
