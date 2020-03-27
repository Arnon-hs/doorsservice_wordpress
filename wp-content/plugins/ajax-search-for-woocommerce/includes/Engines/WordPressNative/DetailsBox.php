<?php

namespace DgoraWcas\Engines\WordPressNative;

use DgoraWcas\Product;
use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

class DetailsBox
{

    public function __construct()
    {

        if (defined('DGWT_WCAS_WC_AJAX_ENDPOINT')) {

            // Searched result details ajax action
            if (DGWT_WCAS_WC_AJAX_ENDPOINT) {
                add_action('wc_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array($this, 'getResultDetails'));
            } else {
                add_action('wp_ajax_nopriv_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array($this, 'getResultDetails'));
                add_action('wp_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array($this, 'getResultDetails'));
            }
        }
    }

    /**
     * Get searched result details
     */

    public function getResultDetails()
    {
        if (!defined('DGWT_WCAS_AJAX_DETAILS_PANEL')) {
            define('DGWT_WCAS_AJAX_DETAILS_PANEL', true);
        }

        $output = array();
        $items  = array();

        if ( ! empty($_POST['items']) && is_array($_POST['items'])) {

            foreach ($_POST['items'] as $item) {

                if (empty($item['objectID'])) {
                    continue;
                }

                $suggestionValue = '';
                $postType        = '';
                $postID          = 0;
                $variationID     = 0;
                $termID          = 0;
                $taxonomy        = '';

                // Suggestion value
                if ( ! empty($item['value'])) {
                    $suggestionValue = sanitize_text_field($item['value']);
                }

                $parts = explode('__', $item['objectID']);
                $type  = ! empty($parts[0]) ? sanitize_key($parts[0]) : '';

                if ($type === 'taxonomy') {
                    $termID   = ! empty($parts[1]) ? absint($parts[1]) : 0;
                    $taxonomy = ! empty($parts[2]) ? sanitize_key($parts[2]) : '';
                } else {
                    $postType    = $type;
                    $postID      = ! empty($parts[1]) ? absint($parts[1]) : 0;
                    $variationID = ! empty($parts[2]) ? absint($parts[2]) : 0;
                }


                // Get product details
                if ('product' === get_post_type($postID)) {
                    $items[] = array(
                        'objectID' => $item['objectID'],
                        'html'     => $this->getProductDetails($postID)
                    );
                }

                // Get taxonomy details
                if ( ! empty($termID) && ! empty($taxonomy)) {
                    $items[] = array(
                        'objectID' => $item['objectID'],
                        'html'     => $this->getTaxonomyDetails($termID, $taxonomy, $suggestionValue)
                    );

                }
            }
            $output['items'] = $items;

            echo json_encode(apply_filters('dgwt/wcas/suggestion_details/output', $output));
            die();
        }
    }

    /**
     * Prepare products details to the ajax output
     *
     * @param int $productID
     * @param string $value Suggestion value
     *
     * @return string HTML
     */

    private function getProductDetails($productID)
    {

        $html = '';

        $product = new Product($productID);

        if (empty($product)) {
            return;
        }

        $details = array(
            'id'   => $product->getID(),
            'desc' => '',
        );


        // Get product desc
        $details['desc'] = Helpers::getProductDesc($product->getWooObject(), 500);


        ob_start();
        include DGWT_WCAS_DIR . 'partials/single-product.php';
        $html = ob_get_clean();


        return $html;
    }

    /**
     * Prepare category details to the ajax output
     *
     * @param int $termID
     * @param string taxonomy
     * @param string $suggestion Suggestion value
     *
     * @return string HTML
     */

    private function getTaxonomyDetails($termID, $taxonomy, $suggestion)
    {

        $html  = '';
        $title = '';

        ob_start();

        $queryArgs = $this->getProductsQueryArgs($termID, $taxonomy);

        $products = new \WP_Query($queryArgs);

        if ($products->have_posts()) {

            $limit         = $queryArgs['posts_per_page'];
            $totalProducts = absint($products->found_posts);
            $showMore      = $limit > 0 && $totalProducts > 0 && $totalProducts - $limit > 0 ? true : false;


            // Details panel title
            $title .= '<span class="dgwt-wcas-datails-title">';
            $title .= '<span class="dgwt-wcas-details-title-tax">';
            if ('product_cat' === $taxonomy) {
                $title .= __('Category', 'woocommerce') . ': ';
            } else {
                $title .= __('Tag') . ': ';
            }
            $title .= '</span>';
            $title .= $suggestion;
            $title .= '</span>';


            echo '<div class="dgwt-wcas-details-inner">';
            echo '<div class="dgwt-wcas-products-in-cat">';

            echo ! empty($title) ? $title : '';

            while ($products->have_posts()) {
                $products->the_post();

                $product = new Product(get_the_ID());

                include DGWT_WCAS_DIR . 'partials/single-product-tax.php';
            }

            echo '</div>';

            if ($showMore) {
                $showMoreUrl = get_term_link($termID, $taxonomy);
                echo '<a class="dgwt-wcas-details-more-products" href="' . esc_url($showMoreUrl) . '">' . __('See all products...', 'ajax-search-for-woocommerce') . ' (' . $totalProducts . ')</a>';
            }

            echo '</div>';
        }

        wp_reset_postdata();


        $html = ob_get_clean();


        return $html;
    }

    /**
     * Get query vars for products that should be displayed in the daxonomy details box
     *
     * @param int $termID
     * @param string $taxonomy
     *
     * @return array
     */

    private function getProductsQueryArgs($termID, $taxonomy)
    {

        $productVisibilityTermIds = wc_get_product_visibility_term_ids();

        $queryArgs = array(
            'posts_per_page' => apply_filters('dgwt/wcas/suggestion_details/taxonomy/limit', 4),
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => false,
            'order'          => 'desc',
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'total_sales',
            'tax_query'      => array()
        );

        // Visibility
        $queryArgs['tax_query'][] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'term_taxonomy_id',
            'terms'    => $productVisibilityTermIds['exclude-from-search'],
            'operator' => 'NOT IN',
        );

        // Out of stock
        if ('yes' === get_option('woocommerce_manage_stock') && DGWT_WCAS()->settings->getOption('exclude_out_of_stock') === 'on') {
            $queryArgs['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $productVisibilityTermIds['outofstock'],
                'operator' => 'NOT IN',
            );
        };

        // Search with specific category
        $queryArgs['tax_query'][] = array(
            'taxonomy'         => $taxonomy,
            'field'            => 'id',
            'terms'            => $termID,
            'include_children' => true,
        );

        return apply_filters('dgwt/wcas/suggestion_details/taxonomy/products_query_args', $queryArgs, $termID, $taxonomy);
    }

}