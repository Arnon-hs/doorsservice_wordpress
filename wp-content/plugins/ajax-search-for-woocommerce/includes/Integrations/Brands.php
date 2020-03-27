<?php


namespace DgoraWcas\Integrations;

/**
 * Class Brands
 * @package DgoraWcas\Integrations
 *
 * Support for plugins:
 * 1. WooCommerce Brands 1.6.9 by WooCommerce
 * 2. YITH WooCommerce Brands Add-on Premium 1.3.3 by YITH
 */
class Brands
{
    /**
     * Brands plugin metadata
     *
     * @var array
     */
    private $pluginInfo = array();

    /**
     * Brands plugin slug
     *
     * @var string
     */
    private $pluginSlug = '';

    /**
     * Brand taxonomy name
     *
     * @var string
     */
    private $brandTaxonomy = '';

    public function __construct()
    {
        $this->setPluginInfo();
        $this->setBrandTaxonomy();
        $this->addSettings();
    }

    /**
     * Set current brands vendor plugin
     *
     * @return void
     */
    private function setPluginInfo()
    {
        foreach ($this->getBrandsPlugins() as $pluginPath) {
            if (is_plugin_active($pluginPath)) {

                $file = WP_PLUGIN_DIR . '/' . $pluginPath;

                if (file_exists($file)) {
                    $this->pluginInfo = get_plugin_data($file);
                    $this->pluginSlug = $pluginPath;
                }

                break;
            }
        }
    }

    /**
     * Set brand taxonomy name
     *
     * @return void
     */
    private function setBrandTaxonomy()
    {
        $brandTaxonomy = 'product_brand';

        if ($this->hasBrands()) {
            switch ($this->pluginSlug) {
                case 'yith-woocommerce-brands-add-on-premium/init.php';
                    $brandTaxonomy = 'yith_product_brand';
                    break;
            }
        }

        $brandTaxonomy = apply_filters('dgwt/wcas/brands/taxonomy', $brandTaxonomy);

        $this->brandTaxonomy = $brandTaxonomy;
    }

    /**
     * Get all supported brands plugins files
     *
     * @return array
     */
    public function getBrandsPlugins()
    {
        return array(
            'woocommerce-brands/woocommerce-brands.php',
            'yith-woocommerce-brands-add-on-premium/init.php',
        );
    }

    /**
     * Check if some brands plugin is enabled
     *
     * @return bool
     */
    public function hasBrands()
    {
        return ! empty($this->pluginInfo);
    }

    /**
     * Get brand taxonomy
     *
     * @return string
     */
    public function getBrandTaxonomy()
    {
        return ! empty($this->brandTaxonomy) ? sanitize_key($this->brandTaxonomy) : '';
    }

    /**
     * Get the name of the plugin vendor
     *
     * @return static
     */
    public function getPluginName()
    {
        return ! empty($this->pluginInfo['Name']) ? sanitize_text_field($this->pluginInfo['Name']) : '';
    }

    /**
     * Get the name of the plugin vendor
     *
     * @return static
     */
    public function getPluginVersion()
    {
        return ! empty($this->pluginInfo['Version']) ? sanitize_text_field($this->pluginInfo['Version']) : '';
    }

    /**
     * Register settings
     *
     * @return void
     */
    private function addSettings()
    {
        if ($this->hasBrands()) {
            add_filter('dgwt/wcas/settings/section=search', function ($settingsScope) {

                $pluginInfo = $this->getPluginName() . ' v' . $this->getPluginVersion();

                $settingsScope[220] = array(
                    'name'    => 'search_in_brands',
                    'label'   => __('Search in brands', 'ajax-search-for-woocommerce'),
                    'class'   => 'dgwt-wcas-premium-only',
                    'type'    => 'checkbox',
                    'default' => 'off',
                    'desc' => sprintf(__('based on the plugin %s','ajax-search-for-woocommerce' ), $pluginInfo)
                );

                return $settingsScope;
            });

            add_filter('dgwt/wcas/settings/section=autocomplete', function ($settingsScope) {

                $pluginInfo = $this->getPluginName() . ' v' . $this->getPluginVersion();

                $settingsScope[1260] = array(
                    'name'    => 'show_matching_brands',
                    'label'   => __('Show brands', 'ajax-search-for-woocommerce'),
                    'class'   => 'dgwt-wcas-premium-only',
                    'type'    => 'checkbox',
                    'default' => 'off',
                    'desc' => sprintf(__('based on the plugin %s','ajax-search-for-woocommerce' ), $pluginInfo)
                );

                return $settingsScope;
            });



        }
    }

}