<?php

namespace Bonnier\WP\ContentHub\Editor\Http\Api;

use Bonnier\WP\ContentHub\Editor\Helpers\UpdateEndpointHelper;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\CategoryRepository;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\TagRepository;
use function Patchwork\Config\get;
use WP_Post;
use WP_Query;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class UpdateEndpointController extends WP_REST_Controller
{

    public function register_routes()
    {
        add_action('rest_api_init',function(){
            register_rest_route('content-hub-editor', '/updates', [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'updateCallback']
            ]);
        });
    }

    public function updateCallback(WP_REST_Request $request): WP_REST_Response
    {
        $id = $request->get_param('id');
        $type = $request->get_param('type');

        if($id && $repo = $this->getRepo($type)) {
            $term = $repo->find_by_id($id);
            //dd($term);

            if ($type === "category") {
                UpdateEndpointHelper::updateCategory();
            }

            return;
        }
        return new WP_REST_Response(['error' => 'unknown type'], 400);
    }

    private function getRepo($type)
    {
        $class = collect([
            'category' => CategoryRepository::class,
            'tag' => TagRepository::class,
        ])->get($type);
        return new $class;
    }
}
