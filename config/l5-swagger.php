<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'CryptoInvestment API',
                'description' => 'Documentación interactiva de la API para el monitoreo de criptomonedas usando CoinMarketCap',
                'version' => '1.0.0',
            ],

            'routes' => [
                // Ruta principal del UI de Swagger
                'api' => 'api/documentation',
            ],

            'paths' => [
                // Generar rutas absolutas para los assets
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),

                // Ruta a los assets del UI
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),

                // Nombre del archivo JSON generado
                'docs_json' => 'api-docs.json',

                // Nombre del archivo YAML generado (opcional)
                'docs_yaml' => 'api-docs.yaml',

                // Usar formato JSON
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),

                // Carpeta donde buscar las anotaciones
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],

        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
        ],

        'scanOptions' => [
            'default_processors_configuration' => [],
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],

        'securityDefinitions' => [
            'securitySchemes' => [
                'sanctum' => [
                    'type' => 'apiKey',
                    'description' => 'Introduce el token en el formato (Bearer <token>)',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
            ],
            'security' => [],
        ],

        // ⚙️ En desarrollo, regenerar siempre la documentación
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', true),

        // ✅ Generar copia YAML opcional
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', 'alpha'),
        'validator_url' => null,

        'ui' => [
            'display' => [
                'dark_mode' => true,
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'list'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => ['use_pkce_with_authorization_code_grant' => false],
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://127.0.0.1:8002'),
        ],
    ],
];
