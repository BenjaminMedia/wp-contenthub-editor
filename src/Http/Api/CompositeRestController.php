<?php

namespace Bonnier\WP\ContentHub\Editor\Http\Api;

use WP_REST_Posts_Controller;

/**
 * Class CompositeRestController
 *
 * @package \Bonnier\WP\ContentHub\Editor\Http\Api
 */
class CompositeRestController extends WP_REST_Posts_Controller
{
    public function check_read_permission($post)
    {
        return true;
    }
    public function sanitize_post_statuses($statuses, $request, $parameter)
    {
        return $statuses;
    }
}
