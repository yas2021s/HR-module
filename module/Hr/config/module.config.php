<?php
namespace Hr;

use Laminas\Router\Http\Segment;

return [
    'router' => [ 
        'routes' =>[       
            'hr' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/hr[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\IndexController::class,
							'action'     => 'index',
						],
					],
				], 
			'employee' => [
				'type'    => Segment::class,
				'options' => [
						'route'       => '/emp[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],        		
						'defaults' => [
								'controller' => Controller\EmployeeController::class,
								'action'     => 'index',
						],
				],
				'swagger-json' => [
    'type' => Segment::class,
    'options' => [
        'route' => '/swagger.json',
        'defaults' => [
            'controller' => Controller\PayrollController::class,
            'action' => 'swagger',
        ],
    ],
],
 'api-docs' => [
	'type' => Segment::class,
	'options' => [
		'route' => '/api-docs.json',
		'defaults' => [
			'controller' => Controller\PayrollController::class,
			'action' => 'apiDocs'
        ],
    ],
],

				'may_terminate' => true,
				'child_routes'  => [
					'defaults' => [
						'type'      => Segment::class,
						'options'   => [
							'route' => '/[:controller[/:action][/:id]]',
							'constraints' => [
								'controller'  => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'  => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'      => '[0-9]*',
							],
							'defaults' => [
							],
						],
					],
					'paginator' => [
						'type' => Segment::class,
						'options' => [
							'route' => '/[page/:page]',
							'constraints' => [
								'action'  => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'      => '[0-9]*', 
							],
							'defaults' => [
								'__NAMESPACE__' => 'Hr\Controller',
								'controller' => Controller\EmployeeController::class,
							],
						],
					],
				],
			],				
			'payroll' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/pr[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\PayrollController::class,
							'action'     => 'index',
						],
					],
				],  
			'leave' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/leave[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\LeaveController::class,
							'action'     => 'index',
						],
					],
				],      
			'preport' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/pr-report[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\PayrollReportController::class,
							'action'     => 'htpitreport',
						],
					],
				],      
			'master' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/master[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\MasterController::class,
							'action'     => 'emptype',
						],
					],
				],      
			'payincrement' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/payincrement[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\PayIncrementController::class,
							'action'     => 'index',
						],
					],
				],    
			'hreport' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/hreport[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\HreportController::class,
							'action'     => 'index',
						],
					],
				], 
			'notesheet' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/notesheet[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\NotesheetController::class,
							'action'     => 'index',
						],
					],
				],   
				'travel' => [
            		'type'    => Segment::class,
            		'options'   => [
						'route'       => '/travel[/:action][/:id]',
						'constraints' => [
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						],  
						'defaults' => [
							'controller' => Controller\TravelController::class,
							'action'     => 'index',
						],
					],
				],  
				
		],
	],	
	'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];