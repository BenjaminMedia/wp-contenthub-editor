<?php

namespace Bonnier\WP\ContentHub\Editor\Http\Api;

use Bonnier\WP\ContentHub\Editor\Helpers\UpdateEndpointHelper;
use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager\TaxonomyContract;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\CategoryRepository;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\TagRepository;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class UpdateEndpointController extends WP_REST_Controller
{
    const REPOSITORY_MAPPER = [
        'category' => CategoryRepository::class,
        'tag' => TagRepository::class,
    ];

    public function register_routes()
    {
        add_action('rest_api_init', function () {
            register_rest_route('content-hub-editor', '/updates', [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'updateCallback'],
            ]);
        });
    }

    public function updateCallback(WP_REST_Request $request): WP_REST_Response
    {
        $resource = $request->get_param('resource');
        $type = $request->get_param('entity_type');

        if ($resource && $type && ($termId = $resource['id']) && $repo = $this->getRepo($type)) {
            $term = $repo->find_by_id($termId);

            if ($type === "category") {
                UpdateEndpointHelper::updateCategory($term);
            } elseif ($type === 'tag') {
                UpdateEndpointHelper::updateTag($term);
            }

            return new WP_REST_Response(['status' => 'OK']);
        }

        return new WP_REST_Response(['error' => 'unknown type'], 400);
    }

    private function getRepo($type): ?TaxonomyContract
    {
        if ($class = collect(self::REPOSITORY_MAPPER)->get($type)) {
            return new $class;
        }

        return null;
    }
}
