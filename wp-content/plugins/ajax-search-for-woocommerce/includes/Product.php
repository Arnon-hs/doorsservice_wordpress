<?php

namespace DgoraWcas;


use DgoraWcas\Engines\TNTSearchMySQL\Indexer\Utils;

class Product
{
    private $productID = 0;
    private $wcProduct = null;
    private $variations = array();
    private $langCode = 'en';

    public function __construct($product)
    {
        if ( ! empty($product) && is_object($product) && is_a($product, 'WC_Product')) {
            $this->productID = $product->get_id();
            $this->wcProduct = $product;
        }

        if ( ! empty($product) && is_object($product) && is_a($product, 'WP_Post')) {
            $this->productID = absint($product->ID);
            $this->wcProduct = wc_get_product($product);
        }

        if (is_numeric($product) && 'product' === get_post_type($product)) {
            $this->productID = absint($product);
            $this->wcProduct = wc_get_product($product);
        }

        $this->setLanguage();
    }

    /**
     * Set info about product language
     *
     * @return void
     */
    public function setLanguage()
    {
        $this->langCode = Multilingual::getPostLang($this->getID());
    }

    /**
     * Get product ID (post_id)
     * @return INT
     */
    public function getID()
    {
        return $this->productID;
    }

    /**
     * Get created date
     *
     * @return mixed
     */
    public function getCreatedDate()
    {
        $date = $this->wcProduct->get_date_created();
        if ( ! $date) {
            $date = '0000-00-00 00:00:00';
        }

        return $date;
    }

    /**
     * Get product name
     * @return string
     */
    public function getName()
    {
        return apply_filters('dgwt/wcas/product/name', $this->wcProduct->get_name());
    }

    /**
     * Get prepared product description
     *
     * @param string $type full|short|suggestions
     *
     * @return string
     */
    public function getDescription($type = 'full')
    {

        $output = '';

        if ($type === 'full') {
            $output = $this->wcProduct->get_description();
        }

        if ($type === 'short') {
            $output = $this->wcProduct->get_short_description();
        }

        if ($type === 'suggestons') {

            $desc = $this->wcProduct->get_short_description();

            if (empty($desc)) {
                $desc = $this->wcProduct->get_description();
            }

            if ( ! empty($desc)) {
                $output = Utils::clearContent($desc);
                $output = html_entity_decode($output);
                $output = Helpers::strCut($output, 120);
            }
        }

        return apply_filters('dgwt/wcas/product/description', $output);
    }

    /**
     * Get product permalink
     *
     * @return string
     */
    public function getPermalink()
    {
        $permalink = $this->wcProduct->get_permalink();

        if (Multilingual::isMultilingual()) {
            $permalink = Multilingual::getPermalink($this->getID(), $permalink, $this->langCode);
        }

        return apply_filters('dgwt/wcas/product/permalink', $permalink);
    }

    /**
     * Get product thumbnail url
     *
     * @return string
     */
    public function getThumbnailSrc()
    {
        $src = '';

        $imageID = $this->wcProduct->get_image_id();

        if ( ! empty($imageID)) {
            $imageSrc = wp_get_attachment_image_src($imageID, 'dgwt-wcas-product-suggestion');

            if (is_array($imageSrc) && ! empty($imageSrc[0])) {
                $src = $imageSrc[0];
            }
        }

        if (empty($src)) {
            $src = wc_placeholder_img_src();
        }

        return apply_filters('dgwt/wcas/product/thumbnail_src', $src, $this->productID, $this->wcProduct);
    }

    /**
     * Get product thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return '<img src="' . $this->getThumbnailSrc() . '" alt="' . $this->getName() . '" />';
    }


    /**
     * Get HTML code with the product price
     *
     * @return string
     */
    public function getPriceHTML()
    {
        return (string) apply_filters('dgwt/wcas/product/html_price', $this->wcProduct->get_price_html(), $this->productID, $this->wcProduct);
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return (string) $this->wcProduct->get_price();
    }

    /**
     * Get average rating
     *
     * @return float
     */
    public function getAverageRating()
    {
        return (float) $this->wcProduct->get_average_rating();
    }

    /**
     * Get review count
     *
     * @return int
     */
    public function getReviewCount()
    {
        return (int) $this->wcProduct->get_review_count();
    }

    /**
     * Get rating HTML
     *
     * @return string
     */
    public function getRatingHtml()
    {
        return (string) wc_get_rating_html($this->getAverageRating());
    }


    /**
     * Get total sales
     *
     * @return int
     */
    public function getTotalSales()
    {
        return (int) $this->wcProduct->get_total_sales();
    }

    /**
     * Get SKU
     * @return string
     */
    public function getSKU()
    {
        return (string) apply_filters('dgwt/wcas/product/sku', $this->wcProduct->get_sku());
    }

    /**
     * Get available variations
     * @return array
     */
    public function getAvailableVariations()
    {

        if (empty($this->variations) && is_a($this->wcProduct, 'WC_Product_Variable')) {
            return $this->wcProduct->get_available_variations();
        }

        return $this->variations;

    }

    /**
     * Get all SKUs for variations
     * @return array
     */
    public function getVariationsSKUs()
    {
        $skus = array();

        $variations = $this->getAvailableVariations();

        foreach ($variations as $variation) {

            if (is_array($variation) && ! empty($variation['sku'])) {
                $skus[] = sanitize_text_field($variation['sku']);
            }
        }

        return apply_filters('dgwt/wcas/product/variations_skus', $skus);
    }

    /**
     * Get description of all product variations
     *
     * @return array
     */
    public function getVariationsDescriptions()
    {

        $descriptions = array();

        $variations = $this->getAvailableVariations();

        foreach ($variations as $variation) {

            if (is_array($variation) && ! empty($variation['variation_description'])) {
                $descriptions[] = sanitize_text_field($variation['variation_description']);
            }
        }

        return $descriptions;
    }


    /**
     * Get attributes
     *
     * @param bool $onlyNames
     *
     * @return array
     */
    public function getAttributes($onlyNames = false)
    {
        $terms = array();
        $attributes = apply_filters('dgwt/wcas/product/attributes', $this->wcProduct->get_attributes());

        foreach ($attributes as $attribute) {

            if ($onlyNames) {


                if ($attribute->is_taxonomy()) {

                    // Global attributes
                    $attrTerms = $attribute->get_terms();
                    if ( ! empty($attrTerms) && is_array($attrTerms)) {
                        foreach ($attrTerms as $attrTerm) {
                            $terms[] = $attrTerm->name;
                        }
                    }

                } else {

                    // Custom attributes
                    $attrOptions = $attribute->get_options();
                    if ( ! empty($attrOptions) && is_array($attrOptions)) {
                        $terms = array_merge($terms, $attrOptions);
                    }
                }

            } else {
                //@TODO future use
            }

        }

        return apply_filters('dgwt/wcas/product/attribute_terms', $terms);
    }

    /**
     * Get product language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->langCode;
    }

    /**
     * Get custom field value
     *
     * @param string $metaKey
     *
     * @return string
     */
    public function getCustomField($metaKey)
    {
        return get_post_meta($this->productID, $metaKey, true);
    }

    /**
     * Get brand name
     *
     * @return string
     */
    public function getBrand()
    {
        $brand    = '';
        $taxonomy = DGWT_WCAS()->brands->getBrandTaxonomy();

        if ( ! empty($taxonomy)) {

            $terms = get_the_terms($this->productID, $taxonomy);

            if ($terms && ! is_wp_error($terms)) {
                $brand = ! empty($terms[0]->name) ? $terms[0]->name : '';
            }

        }

        return $brand;
    }

    /**
     * Get terms form specific taxonomy
     *
     * @param $taxonomy
     * @param $format output format
     *
     * @return string
     *
     */
    public function getTerms($taxonomy = 'product_cat', $format = 'array')
    {
        $items = array();

        if ( ! empty($taxonomy)) {

            $terms = get_the_terms($this->productID, $taxonomy);

            if (!empty($terms) && is_array($terms)) {
                foreach ($terms as $term){
                    if(!empty($term->name)){
                        $items[] = $term->name;
                    }

                }
            }

        }

        return $format === 'string' ? implode(' | ', $items) :  $items;
    }

    /**
     * Check, if class is initialized correctly
     * @return bool
     */
    public function isValid()
    {
        $isValid = false;

        if (is_a($this->wcProduct, 'WC_Product')) {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * WooCommerce raw product object
     *
     * @return object
     */
    public function getWooObject()
    {
        return $this->wcProduct;
    }

    /**
     * Get custom attributes
     *
     * @param int productID
     *
     * @return array
     */
    public static function getCustomAttributes($productID)
    {
        global $wpdb;

        $terms = array();

        $sql = $wpdb->prepare("SELECT meta_value
                                      FROM $wpdb->postmeta
                                      WHERE post_id = %d
                                      AND meta_key = '_product_attributes'
                                     ", $productID);

        $optValue = $wpdb->get_var($sql);

        if ( ! empty($optValue) && strpos($optValue, 'a:') === 0) {

            $rawAttributes = unserialize($optValue);

            if (is_array($rawAttributes) && ! empty($rawAttributes)) {

                $rawAttributes = apply_filters('dgwt/wcas/product/custom_attributes', $rawAttributes);

                foreach ($rawAttributes as $rawAttribute) {
                    if ($rawAttribute['is_taxonomy'] == 0 && ! empty($rawAttribute['value'])) {
                        $partTerms = explode(' | ', $rawAttribute['value']);

                        $terms = array_merge($terms, $partTerms);
                    }
                }
            }

        }

        return $terms;

    }

    /**
     * Check product type
     *
     * @return bool
     */
    public function isType($type){
        return $this->wcProduct->is_type($type);
    }


}