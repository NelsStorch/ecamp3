<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

/**
 * Decorates the OpenApi factory to add API docs for the oauth endpoints.
 */
final readonly class OAuthDecorator implements OpenApiFactoryInterface {
    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi {
        $openApi = ($this->decorated)($context);

        $pathItemGoogle = new Model\PathItem(
            ref: 'Google OAuth',
            get: new Model\Operation(
                operationId: 'oauthGoogleRedirect',
                tags: ['OAuth'],
                responses: [
                    '302' => [
                        'description' => 'Redirect to the Google OAuth authorization endpoint',
                    ],
                ],
                summary: 'Log in using Google Oauth.',
                parameters: [
                    new Model\Parameter(
                        name: 'callback',
                        in: 'path',
                        schema: [
                            'type' => 'string',
                        ]
                    ),
                ],
            ),
        );
        $openApi->getPaths()->addPath('/auth/google', $pathItemGoogle);

        $pathItemPbsmidata = new Model\PathItem(
            ref: 'PBS MiData OAuth',
            get: new Model\Operation(
                operationId: 'oauthPbsmidataRedirect',
                tags: ['OAuth'],
                responses: [
                    '302' => [
                        'description' => 'Redirect to the PBS MiData OAuth authorization endpoint',
                    ],
                ],
                summary: 'Log in using PBS MiData Oauth.',
                parameters: [
                    new Model\Parameter(
                        name: 'callback',
                        in: 'path',
                        schema: [
                            'type' => 'string',
                        ]
                    ),
                ],
            ),
        );
        $openApi->getPaths()->addPath('/auth/pbsmidata', $pathItemPbsmidata);

        $pathItemCevidb = new Model\PathItem(
            ref: 'CeviDB OAuth',
            get: new Model\Operation(
                operationId: 'oauthCevidbRedirect',
                tags: ['OAuth'],
                responses: [
                    '302' => [
                        'description' => 'Redirect to the CeviDB OAuth authorization endpoint',
                    ],
                ],
                summary: 'Log in using CeviDB Oauth.',
                parameters: [
                    new Model\Parameter(
                        name: 'callback',
                        in: 'path',
                        schema: [
                            'type' => 'string',
                        ]
                    ),
                ],
            ),
        );
        $openApi->getPaths()->addPath('/auth/cevidb', $pathItemCevidb);

        $pathItemJubladb = new Model\PathItem(
            ref: 'JublaDB OAuth',
            get: new Model\Operation(
                operationId: 'oauthJubladbRedirect',
                tags: ['OAuth'],
                responses: [
                    '302' => [
                        'description' => 'Redirect to the JublaDB OAuth authorization endpoint',
                    ],
                ],
                summary: 'Log in using JublaDB Oauth.',
                parameters: [
                    new Model\Parameter(
                        name: 'callback',
                        in: 'path',
                        schema: [
                            'type' => 'string',
                        ]
                    ),
                ],
            ),
        );
        $openApi->getPaths()->addPath('/auth/jubladb', $pathItemJubladb);

        return $openApi;
    }
}
