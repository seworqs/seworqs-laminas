<?php

declare(strict_types=1);

namespace Seworqs\Laminas;

use Doctrine\ORM\EntityManager;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Seworqs\Laminas\Controller\AuthController;
use Seworqs\Laminas\Controller\BaseActionControllerFactory;
use Seworqs\Laminas\Controller\ErrorController;
use Seworqs\Laminas\Controller\MenuController;
use Seworqs\Laminas\Doctrine\EventListener\RecordDateListener;
use Seworqs\Laminas\Factory\CacheFactory;
use Seworqs\Laminas\Factory\SessionFactory;
use Seworqs\Laminas\Factory\TranslatorFactory;
use Seworqs\Laminas\Form\Login;
use Seworqs\Laminas\Form\Registration;
use Seworqs\Laminas\I18n\Translator\Translator;
use Seworqs\Laminas\Mail\Message;
use Seworqs\Laminas\Mail\Transport\Smtp;
use Seworqs\Laminas\Model\Account\AccountService;
use Seworqs\Laminas\Model\Service\ServiceFactory;
use Seworqs\Laminas\Navigation\SeworqsMenuFactory;
use Seworqs\Laminas\Permission\Acl;
use Seworqs\Laminas\Permission\AclFactory;
use Seworqs\Laminas\Service\CurrentUserService;
use Seworqs\Laminas\Service\MenuStateService;
use Seworqs\Laminas\View\Helper\Acl as AclViewHelper;
use Seworqs\Laminas\View\Helper\CurrentUser as CurrentUserViewHelper;
use Seworqs\Laminas\View\Helper\DateTime;
use Seworqs\Laminas\View\Helper\FormElementHelpOrErrors as FormElementHelpOrErrorsViewHelper;
use Seworqs\Laminas\View\Helper\HtmlClass;
use Seworqs\Laminas\View\Helper\MenuState as MenuStateViewHelper;
use Seworqs\Laminas\View\Helper\Session as SessionViewHelper;

return [
    'caches'             => [
        'seworqs-cache' => [
            'adapter' => Filesystem::class,
            'options' => [
                'cache_dir' => realpath(__DIR__ . '/../../../../data/cache'),
                'ttl'       => 3600
            ],
            'plugins' => [
                ['name' => 'serializer']
            ]
        ]
    ],
    'controllers'        => [
        'factories' => [
            AuthController::class  => BaseActionControllerFactory::class,
            ErrorController::class => BaseActionControllerFactory::class,
            MenuController::class  => BaseActionControllerFactory::class
        ]
    ],
    'doctrine'           => [
        'driver'       => [
            'seworqs-driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    realpath(__DIR__ . '/../src/Model'),
                ]
            ],
            'orm_default'    => [
                'drivers' => [
                    'Seworqs\Laminas\Model' => 'seworqs-driver'
                ]
            ]
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    RecordDateListener::class
                ]
            ]
        ]
    ],
    'form_elements'      => [
        'factories' => [
            Login::class        => function ($container, $requestedName, array $options = []) {
                $currentUser   = $container->get(CurrentUserService::class);
                $entityManager = $container->get(EntityManager::class);
                return new Login($currentUser, $options);
            },
            Registration::class => function ($container, $requestedName, array $options = []) {
                $currentUser   = $container->get(CurrentUserService::class);
                $entityManager = $container->get(EntityManager::class);
                return new Registration($entityManager, $currentUser, $options);
            }
        ]
    ],
    'navigation'         => [
        'seworqs_menu' => [
            [
                'label'     => t('Login'),
                'route'     => 'login',
                'icon'      => '<i class="bi bi-box-arrow-in-right me-2"></i>',
                'resource'  => AuthController::class,
                'privilege' => 'login'
            ],
            [
                'label'     => t('Logout'),
                'route'     => 'logout',
                'icon'      => '<i class="bi bi-box-arrow-right me-2"></i>',
                'resource'  => AuthController::class,
                'privilege' => 'logout'
            ]
        ]
    ],
    'router'             => [
        'routes' => [
            'error'    => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/error/[:status]',
                    'constraints' => [
                        'status' => '\d{3}'
                    ],
                    'defaults'    => [
                        'controller' => ErrorController::class,
                        'action'     => 'error',
                        'status'     => 400
                    ],
                ]
            ],
            'register' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'register'
                    ]
                ]
            ],
            'login'    => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login'
                    ]
                ]
            ],
            'logout'   => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
                        'redirect'   => [
                            'route' => '/home'
                        ]
                    ]
                ]
            ],
            'menu'     => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/menu',
                    'defaults' => [
                        'controller' => MenuController::class,
                        'action'     => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'click' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/click/:click/:item',
                            'constraints' => [
                                'click' => '(show|hide)'
                            ],
                            'defaults'    => [
                                'controller' => MenuController::class,
                                'action'     => 'ajaxClick',
                                'isAjax'     => true
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ],
    'service_manager'    => [
        'factories' => [
            Acl::class                         => AclFactory::class,
            BaseActionControllerFactory::class => BaseActionControllerFactory::class,
            Message::class                     => function ($container) {
                $config = $container->get('config');
                return new Message($config['seworqs']['mail']['message']);
            },
            Smtp::class                        => function ($container) {
                $config = $container->get('config');
                return new Smtp($config['seworqs']['mail']['transport']['smtp']);
            },
            'SeworqsCache'                     => CacheFactory::class,
            'SeworqsSession'                   => SessionFactory::class,
            Translator::class                  => TranslatorFactory::class,
            'seworqs_menu'                     => SeworqsMenuFactory::class,
            'main_menu'                        => SeworqsMenuFactory::class,
            AccountService::class              => ServiceFactory::class,
            CurrentUserService::class          => function ($container) {
                $session = $container->get('SeworqsSession');
                return new CurrentUserService($session);
            },
            MenuStateService::class            => function ($container) {
                $session = $container->get('SeworqsSession');
                return new MenuStateService($session);
            },
            RecordDateListener::class          => InvokableFactory::class
        ]
    ],
    'view_manager'       => [
        'layout'              => 'seworqs/layout/leftmenu',
        'strategies'          => [
            'ViewJsonStrategy'
        ],
        'template_map'        => [
            'layout/layout'                    => __DIR__ . '/../view/layout/leftmenu.phtml',
            'error/403'                        => __DIR__ . '/../view/error/403.phtml',
            'error/404'                        => __DIR__ . '/../view/error/404.phtml',
            'error/index'                      => __DIR__ . '/../view/error/index.phtml',
            'seworqs/layout/nomenu'            => __DIR__ . '/../view/layout/nomenu.phtml',
            'seworqs/layout/leftmenu'          => __DIR__ . '/../view/layout/leftmenu.phtml',
            'seworqs/partial/menu/bootstrap'   => __DIR__ . '/../view/partial/menu/bootstrap.phtml',
            'seworqs/partial/menu/breadcrumbs' => __DIR__ . '/../view/partial/menu/breadcrumb.phtml',
            'seworqs/partial/menu/sidebar'     => __DIR__ . '/../view/partial/menu/sidebar.phtml',
            'seworqs/partial/paginator'        => __DIR__ . '/../view/partial/paginator.phtml',
            'seworqs/partial/pagetitle'        => __DIR__ . '/../view/partial/pagetitle.phtml',
            'seworqs/partial/flashmessages'    => __DIR__ . '/../view/partial/flashmessages.phtml',
            'seworqs/partial/user'             => __DIR__ . '/../view/partial/user.phtml',
            'seworqs/login'                    => __DIR__ . '/../view/layout/leftmenu.phtml',
            'seworqs/register'                 => __DIR__ . '/../view/layout/leftmenu.phtml'
        ],
        'template_path_stack' => [
            'user_management' => __DIR__ . '/../view',
        ],
    ],
    'view_helper_config' => [
        'flashmessenger' => [
            'default' => [
                'message_open_format'      => '<div%s role="alert">',
                'message_close_string'     => '</div>',
                'message_separator_string' => '</div><div%s role="alert">',
                'classes'                  => 'alert alert-primary alert-dismissible',
            ],
            'success' => [
                'message_open_format'      => '<div%s role="alert"><i class="bi bi-check-circle me-2"></i>',
                'message_close_string'     => '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
                'message_separator_string' => '</div><div%s role="alert"><i class="bi bi-check-circle me-2"></i>',
                'classes'                  => 'alert alert-success alert-dismissible',
            ],
            'warning' => [
                'message_open_format'      => '<div%s role="alert"><i class="bi bi-exclamation-triangle me-2"></i>',
                'message_close_string'     => '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
                'message_separator_string' => '</div><div%s role="alert"><i class="bi bi-exclamation-triangle me-2"></i>',
                'classes'                  => 'alert alert-success alert-dismissible',
            ],
            'error'   => [
                'message_open_format'      => '<div%s role="alert"><i class="bi bi-exclamation-octagon-fill me-2"></i>',
                'message_close_string'     => '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
                'message_separator_string' => '</div><div%s role="alert"><i class="bi bi-exclamation-octagon-fill me-2"></i>',
                'classes'                  => 'alert alert-success alert-dismissible',
            ],
            'info'    => [
                'message_open_format'      => '<div%s role="alert"><i class="bi bi-exclamation-circle me-2"></i>',
                'message_close_string'     => '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>',
                'message_separator_string' => '</div><div%s role="alert"><i class="bi bi-exclamation-circle me-2"></i>',
                'classes'                  => 'alert alert-success alert-dismissible',
            ],
        ],
    ],
    'view_helpers'       => [
        'factories' => [
            'acl'                                    => function ($container) {
                $acl = $container->get(Acl::class);
                return new AclViewHelper($acl);
            },
            'session'                                => function ($container, $a, $b) {
                $session = $container->get('SeworqsSession');
                return new SessionViewHelper($session);
            },
            'currentUser'                            => function ($container) {
                $currentUser = $container->get(CurrentUserService::class);
                return new CurrentUserViewHelper($currentUser);
            },
            'menuState'                              => function ($container) {
                $currentUser = $container->get(MenuStateService::class);
                return new MenuStateViewHelper($currentUser);
            },
            FormElementHelpOrErrorsViewHelper::class => InvokableFactory::class,
            HtmlClass::class                         => InvokableFactory::class,
            DateTime::class                          => InvokableFactory::class
        ],
        'aliases'   => [
            'formElementHelpOrErrors' => FormElementHelpOrErrorsViewHelper::class,
            'htmlClass'               => HtmlClass::class,
            'dateTime'                => DateTime::class
        ]
    ],
    'seworqs'            => [
        'acl'     => [
            'enabled'     => false,
            'cache'       => false,
            'roles'       => [
                Acl::ROLE_GUEST,
                Acl::ROLE_CUSTOMER,
                Acl::ROLE_BACKOFFICE,
                Acl::ROLE_ADMINISTRATOR
            ],
            'resources'   => [
                AuthController::class,
                ErrorController::class,
                MenuController::class,
            ],
            'permissions' => [
                'allow' => [
                    [
                        [],
                        [ErrorController::class],
                        []
                    ],
                    [
                        [],
                        [MenuController::class],
                        []
                    ],
                    [
                        [Acl::ROLE_GUEST],
                        [AuthController::class],
                        ['login', 'register']
                    ],
                    [
                        [Acl::ROLE_ADMINISTRATOR, Acl::ROLE_BACKOFFICE, Acl::ROLE_CUSTOMER],
                        [AuthController::class],
                        ['logout']
                    ],
                ], // [roles=>[],resources=>[], privileges=[]]
                'deny'  => [] // [roles=>[],resources=>[], privileges=[]]
            ]
        ],
        'cache'   => 'seworqs-cache',
        'mail'    => [ // Override these settings in a configuration file!
            'message'   => [
                //                'KEY_TO_USE' => [
                //                    'from' => [], //    ['email' => 'john.doe@somewhere.com', 'name' => 'John Doe'],
                //                    'reply_to' => [], //    ['email' => 'john.doe@somewhere.com', 'name' => 'John Doe'],
                //                    'to' => [
                //                        //    ['email' => 'john.doe@somewhere.com', 'name' => 'John Doe'],
                //                    ],
                //                    'cc' => [
                //                        //    ['email' => 'john.doe@somewhere.com', 'name' => 'John Doe'],
                //                    ],
                //                    'bcc' => [
                //                        //    ['email' => 'john.doe@somewhere.com', 'name' => 'John Doe'],
                //                    ],
                //                    'encoding' => 'UTF-8',
                //                    'headers' => [
                //                        'X-Mailer' => 'Seworqs',
                //                    ]
                //                ]
            ],
            'transport' => [
                //                'smtp' => [
                //                    'KEY_TO_USE' => [
                //                        'host' => 'SMTP HOST',
                //                        'port' => 25,
                //                        'connection_class' => 'login',
                //                        'connection_config' => [
                //                            'username' => 'n/a',
                //                            'password' => 'n/a',
                //                            'use_complete_quit' => false
                //                        ]
                //                    ]
                //                ]
            ]
        ],
        'session' => 'seworqs'
    ]
];
