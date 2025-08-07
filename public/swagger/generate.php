<?php
/**
 * Swagger Documentation Generator
 * 
 * This script can be used to generate Swagger documentation dynamically
 * from controller annotations. Currently, it serves as a template for
 * future implementation.
 */

// Set content type to JSON for API responses
header('Content-Type: application/json');

// Configuration
$config = [
    'controllers_path' => '../../module/Hr/src/Controller/',
    'output_file' => 'hr-payroll-api.yaml',
    'base_url' => 'http://localhost:8080',
    'api_title' => 'HR Payroll API',
    'api_version' => '1.0.0',
    'api_description' => 'Comprehensive API documentation for HR Payroll Management System'
];

/**
 * Generate Swagger documentation from controller files
 */
function generateSwaggerDocs($config) {
    $swagger = [
        'openapi' => '3.0.0',
        'info' => [
            'title' => $config['api_title'],
            'description' => $config['api_description'],
            'version' => $config['api_version'],
            'contact' => [
                'name' => 'HR Development Team',
                'email' => 'hr@company.com'
            ],
            'license' => [
                'name' => 'MIT',
                'url' => 'https://opensource.org/licenses/MIT'
            ]
        ],
        'servers' => [
            [
                'url' => $config['base_url'],
                'description' => 'Development server'
            ]
        ],
        'tags' => [
            [
                'name' => 'Payroll Management',
                'description' => 'Core payroll operations'
            ],
            [
                'name' => 'Pay Structure',
                'description' => 'Pay structure and payhead management'
            ],
            [
                'name' => 'Employee Payroll',
                'description' => 'Employee-specific payroll operations'
            ],
            [
                'name' => 'Salary Booking',
                'description' => 'Salary booking and advance salary operations'
            ],
            [
                'name' => 'PEPF',
                'description' => 'Public Enterprise Provident Fund operations'
            ],
            [
                'name' => 'PBVI',
                'description' => 'Payroll Bank Voucher Issue operations'
            ]
        ],
        'paths' => generatePaths(),
        'components' => generateComponents()
    ];
    
    return $swagger;
}

/**
 * Generate API paths from controller methods
 */
function generatePaths() {
    $paths = [];
    
    // Payroll Management endpoints
    $paths['/payroll'] = [
        'get' => [
            'tags' => ['Payroll Management'],
            'summary' => 'Get payroll overview',
            'description' => 'Retrieve payroll overview for a specific year',
            'parameters' => [
                [
                    'name' => 'year',
                    'in' => 'query',
                    'description' => 'Year for payroll data (defaults to current year)',
                    'required' => false,
                    'schema' => [
                        'type' => 'integer',
                        'example' => 2024
                    ]
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'Payroll overview retrieved successfully',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => [
                                        'type' => 'string',
                                        'example' => 'Payroll'
                                    ],
                                    'payroll' => [
                                        'type' => 'array',
                                        'items' => [
                                            '$ref' => '#/components/schemas/Payroll'
                                        ]
                                    ],
                                    'year' => [
                                        'type' => 'integer',
                                        'example' => 2024
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '401' => [
                    'description' => 'Unauthorized - User not authenticated'
                ]
            ]
        ]
    ];
    
    // Add more endpoints here based on your controller methods
    // This is a template - you can expand this based on your actual controller methods
    
    return $paths;
}

/**
 * Generate component schemas
 */
function generateComponents() {
    return [
        'schemas' => [
            'Payroll' => [
                'type' => 'object',
                'properties' => [
                    'id' => [
                        'type' => 'integer',
                        'example' => 1
                    ],
                    'month' => [
                        'type' => 'integer',
                        'example' => 12
                    ],
                    'year' => [
                        'type' => 'integer',
                        'example' => 2024
                    ],
                    'status' => [
                        'type' => 'string',
                        'enum' => ['draft', 'submitted', 'committed'],
                        'example' => 'draft'
                    ]
                ]
            ],
            'EmployeePayroll' => [
                'type' => 'object',
                'properties' => [
                    'employee_id' => [
                        'type' => 'integer',
                        'example' => 123
                    ],
                    'name' => [
                        'type' => 'string',
                        'example' => 'John Doe'
                    ],
                    'basic_salary' => [
                        'type' => 'number',
                        'format' => 'float',
                        'example' => 50000.00
                    ],
                    'net_salary' => [
                        'type' => 'number',
                        'format' => 'float',
                        'example' => 55000.00
                    ]
                ]
            ]
        ],
        'securitySchemes' => [
            'bearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
                'description' => 'JWT token for API authentication'
            ]
        ]
    ];
}

/**
 * Parse controller file and extract method information
 */
function parseControllerFile($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    
    $content = file_get_contents($filePath);
    $methods = [];
    
    // Simple regex to find public methods ending with 'Action'
    preg_match_all('/public\s+function\s+(\w+Action)\s*\([^)]*\)\s*\{/', $content, $matches);
    
    if (isset($matches[1])) {
        foreach ($matches[1] as $method) {
            $methods[] = $method;
        }
    }
    
    return $methods;
}

/**
 * Convert method name to API endpoint
 */
function methodToEndpoint($methodName) {
    // Remove 'Action' suffix and convert to kebab-case
    $endpoint = str_replace('Action', '', $methodName);
    $endpoint = preg_replace('/([a-z])([A-Z])/', '$1-$2', $endpoint);
    return strtolower($endpoint);
}

// Handle different request types
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = $_SERVER['REQUEST_URI'] ?? '';

switch ($requestMethod) {
    case 'GET':
        if (strpos($requestPath, '/generate') !== false) {
            // Generate and return Swagger documentation
            $swagger = generateSwaggerDocs($config);
            echo json_encode($swagger, JSON_PRETTY_PRINT);
        } else {
            // Return current documentation
            $currentDoc = file_get_contents($config['output_file']);
            if ($currentDoc) {
                echo $currentDoc;
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Documentation not found']);
            }
        }
        break;
        
    case 'POST':
        if (strpos($requestPath, '/update') !== false) {
            // Update documentation (placeholder for future implementation)
            $swagger = generateSwaggerDocs($config);
            $yamlContent = jsonToYaml($swagger);
            
            if (file_put_contents($config['output_file'], $yamlContent)) {
                echo json_encode(['success' => 'Documentation updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update documentation']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

/**
 * Convert JSON to YAML (simplified version)
 * In a real implementation, you might want to use a proper YAML library
 */
function jsonToYaml($json) {
    // This is a simplified conversion - in production, use a proper YAML library
    $yaml = "openapi: 3.0.0\n";
    $yaml .= "info:\n";
    $yaml .= "  title: " . $json['info']['title'] . "\n";
    $yaml .= "  version: " . $json['info']['version'] . "\n";
    $yaml .= "  description: " . $json['info']['description'] . "\n";
    
    // Add more YAML conversion logic here
    
    return $yaml;
}

/**
 * API endpoint to get controller methods
 */
if (isset($_GET['action']) && $_GET['action'] === 'methods') {
    $controllerFile = $config['controllers_path'] . 'PayrollController.php';
    $methods = parseControllerFile($controllerFile);
    
    echo json_encode([
        'controller' => 'PayrollController',
        'methods' => $methods,
        'endpoints' => array_map('methodToEndpoint', $methods)
    ]);
}
?> 