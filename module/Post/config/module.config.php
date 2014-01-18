<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Post\Controller\Post' => 'Post\Controller\PostController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'post' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/Post[/:action][/:id]',
                    'constraints' => array(
                        'action'    =>  'submit|promote|approve|reject|delete|like|share|view', 
                        'id'    =>  '[a-zA-Z][a-zA-Z0-9_-]+',  
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Post\Controller',
                        'controller'    => 'Post',
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
