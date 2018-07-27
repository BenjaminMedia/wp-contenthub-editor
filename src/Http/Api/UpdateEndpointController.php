<?php

namespace Bonnier\WP\ContentHub\Editor\Http\Api;

use Bonnier\WP\ContentHub\Editor\Helpers\TermImportHelper;
use wpSiteManager\Services\CategoryService;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class UpdateEndpointController extends WP_REST_Controller
{
    public function register_routes()
    {
        add_action('rest_api_init', function () {
            register_rest_route('content-hub-editor', '/updates', [
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'updateCallback'],
            ]);
        });
    }

    public function updateCallback(WP_REST_Request $request): WP_REST_Response
    {
        $resource = $this->formatResource($request->get_param('data'));
        $meta = $request->get_param('meta');
        $entityType = $meta['entity_type'];
        $actionType = $meta['action_type'];

        if ($resource && $entityType) {
            $termImporter = $this->getTermImporter($entityType);
            if (in_array($actionType, ['create', 'update'])) {
                $termImporter->importTermAndLinkTranslations($resource);
            }
            if ($actionType === 'delete') {
                $termImporter->deleteTermAndTranslations($resource);
            }

            $this->refreshCache($resource, $meta);

            return new WP_REST_Response(['status' => 'OK']);
        }

        return new WP_REST_Response(['error' => 'unknown type'], 400);
    }

    private function getTermImporter($entityType) : TermImportHelper
    {
        return new TermImportHelper($entityType);
    }

    private function formatResource($resource)
    {
        // Convert array to object
        return json_decode(json_encode($resource));
    }

    private function refreshCache($resource, $meta)
    {
        if ($meta['entity_type'] !== 'category' && (!class_exists('WpSiteManager\Plugin'))) {
            return;
        }

        foreach ($resource->content_hub_ids as $contentHubId) {
            CategoryService::clearCache($contentHubId);
        }
    }
}
