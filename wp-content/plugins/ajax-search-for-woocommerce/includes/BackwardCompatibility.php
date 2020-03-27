<?php

namespace DgoraWcas;

class BackwardCompatibility
{

    private $remindMeLaterTransient = 'dgwt_wcas_bc_remind_me_later';

    public function __construct()
    {
        $this->maybeEnsureCompatibility();

        add_action('wp_ajax_dgwt_wcas_bc_keep_latest', array($this, 'wipeAllAjax'));
        add_action('wp_ajax_dgwt_wcas_bc_toggle', array($this, 'toggleAjax'));
        add_action('wp_ajax_dgwt_wcas_bc_remind_me_later', array($this, 'remindMeLaterAjax'));

        add_action('wp_ajax_dgwt_wcas_bc_dismiss_ar_notice', array($this, 'dismissActionRequiredNotice'));

        add_action('admin_notices', array($this, 'actionRequiredNotice'));
    }

    /**
     * Show admin notice
     *
     * @return void
     */
    public function actionRequiredNotice()
    {

        if (defined('DISABLE_NAG_NOTICES') && DISABLE_NAG_NOTICES) {
            return;
        }

        if(get_option('dgwt_wcas_dismiss_bc_action_required_notice')){
            return;
        }

        $cs    = get_current_screen();
        $views = array(
            'dashboard',
            'update-core',
            'plugins'
        );


        if ( ! empty($cs->id) && in_array($cs->id, $views) && $this->canShowNotice()) {
            ?>
            <div class="js-dgwt-wcas-bc-action-required notice notice-info is-dismissible">
                <p>
                    <b><?php _e('AJAX Search for WooCommerce', 'sample-text-domain'); ?></b>
                    <b>[<?php _e('Action Required', 'sample-text-domain'); ?>]</b>
                    - <?php printf(__('You have updated the plugin to the latest version %s. You need to complete the updating process manually. <a href="%s">Go to the plugin page</a>.', 'sample-text-domain'), '(v' . DGWT_WCAS_VERSION . ')', Helpers::getSettingsUrl()); ?>
                </p>
            </div>
            <?php

            add_filter('admin_footer', function(){
                ?>
                <script>
                    jQuery(document).on('click', '.js-dgwt-wcas-bc-action-required .notice-dismiss', function(e){
                        e.preventDefault();
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: {
                                action: 'dgwt_wcas_bc_dismiss_ar_notice'
                            }
                        });
                    })
                </script>
                <?php
            });
        }

    }

    /**
     * Get last backward compatible version
     *
     * @return void
     */
    public static function getCompatibleVersion()
    {
        $version = get_option('dgwt_wcas_backward_compatibility_version');

        return empty($version) ? '' : $version;
    }

    /**
     * Enable backward compatibility
     *
     * @return bool true if the action was successful
     */
    private function enable()
    {
        $enabled = false;
        $cv      = self::getCompatibleVersion();
        if ( ! empty($cv)) {
            $enabled = update_option('dgwt_wcas_backward_compatibility', 'on');
        }

        return $enabled;
    }

    /**
     * Disable backward compatibility
     *
     * @return bool true if the action was successful
     */
    public function disable()
    {
        return update_option('dgwt_wcas_backward_compatibility', 'off');
    }

    /**
     * Enable or disable BC via ajax
     *
     * @return string json
     */
    public function toggleAjax()
    {
        $status = false;

        if ( ! empty($_REQUEST['state'])) {
            switch ($_REQUEST['state']) {
                case 'enable':
                    $status = $this->enable();
                    break;
                case 'disable':
                    $status = $this->disable();
                    break;
            }
        }

        if ($status) {
            wp_send_json_success();
        }

        wp_send_json_error();

    }

    /**
     * Set transient (one week) for remind me later in notice
     *
     * @return void
     */
    public function remindMeLaterAjax()
    {

        if (set_transient($this->remindMeLaterTransient, true, (60 * 60 * 24 * 3))) {
            wp_send_json_success();
        }

        wp_send_json_error();

    }

    /**
     * Dismiss extra notice to inform about manually updating
     *
     * @return void
     */
    public function dismissActionRequiredNotice()
    {

        if (add_option('dgwt_wcas_dismiss_bc_action_required_notice', true)) {
            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Check if backward compatibility options exist.
     *
     * @return bool
     */
    public function optionsExists()
    {
        $optBc        = get_option('dgwt_wcas_backward_compatibility');
        $optBcVersion = get_option('dgwt_wcas_backward_compatibility_version');

        return ! empty($optBc) && ! empty($optBcVersion);
    }

    /**
     * Check if backward compatibility is enabled
     *
     * @return bool
     */
    private function isEnabled()
    {
        $opt = get_option('dgwt_wcas_backward_compatibility');

        return ! empty($opt) && $opt === 'on';
    }

    /**
     * Load backward asstes and partials if it is necessary
     *
     * @return bool true if loading backward compatible files was necessary
     */
    private function maybeEnsureCompatibility()
    {
        $ensured = false;
        $cv      = self::getCompatibleVersion();
        if (
            $this->isEnabled()
            && $cv
            && version_compare($cv, '0.0.1', '>=') >= 0 // Check if version is correct
            && version_compare(DGWT_WCAS_VERSION, $cv, '>')
        ) {
            $methodName = 'compatibleWith_' . str_replace('.', '_', $cv);

            if (method_exists($this, $methodName)) {
                $this->$methodName();
                $ensured = true;
            }


        }

        return $ensured;
    }

    /**
     * Wipe all information about backward compatibility
     *
     * @return bool true on success
     */
    private function wipeAll()
    {

        $bc  = delete_option('dgwt_wcas_backward_compatibility');
        $bcv = delete_option('dgwt_wcas_backward_compatibility_version');

        return ($bc && $bcv);
    }

    public function wipeAllAjax()
    {
        $status = $this->wipeAll();

        wp_send_json_success($status);
    }

    /**
     * Load v1.1.7 assets and partials
     * style.css, jquery.dgwt-wcas.min.js and search-form.php
     *
     * @return void
     */
    public function compatibleWith_1_1_7()
    {
        // CSS
        add_filter('dgwt/wcas/scripts/css_style_url', function ($url) {
            return DGWT_WCAS_URL . 'backward-compatibility/1-1-7/style.css';
        });

        // JS
        add_filter('dgwt/wcas/scripts/js_url', function ($url) {
            return DGWT_WCAS_URL . 'backward-compatibility/1-1-7/jquery.dgwt-wcas.min.js';
        });

        // Form partial path
        add_filter('dgwt/wcas/form/partial_path', function ($url) {
            return DGWT_WCAS_DIR . 'backward-compatibility/1-1-7/search-form.php';
        });

        // Body classes
        add_filter('body_class', function ($classes) {

            $classes[] = 'dgwt-wcas-bc-1-1-7';

            return $classes;

        });

    }

    /**
     *
     */
    public function canShowNotice()
    {
        $canShow = false;

        $optBc      = get_option('dgwt_wcas_backward_compatibility');
        $optVersion = get_option('dgwt_wcas_backward_compatibility_version');

        $remindMeLater = get_transient($this->remindMeLaterTransient);

        if ($remindMeLater) {
            return false;
        }

        if (
            ! empty($optBc)
            && ! empty($optVersion)
            && in_array($optBc, array('on', 'off'))
            && version_compare($optVersion, '0.0.1', '>=') >= 0
            && version_compare(DGWT_WCAS_VERSION, $optVersion, '>')
        ) {
            $canShow = true;

        } else {
            $this->wipeAll();
        }

        return $canShow;
    }

    /**
     * Notice
     *
     * @return void
     */
    public function notice()
    {

        if ( ! $this->canShowNotice()) {
            return;
        }

        $theme          = wp_get_theme();
        $themeName      = $theme->get('Name');
        $themeUri       = $theme->get('ThemeURI');
        $themeAuthor    = $theme->get('Author');
        $themeAuthorURL = $theme->get('Author URI');
        $themeLink      = ! empty($themeUri) ? '<a href="' . esc_url($themeUri) . '">' . esc_html($themeName) . '</a>' : '<b>"' . esc_html($themeName) . '"</b>';

        $vompatibleVersion = self::getCompatibleVersion();

        $troubleshootingLink = '<a href="#TB_inline?width=600&height=550&inlineId=dgwt-wcas-troubleshooting" class="thickbox" title="' . __('Troubleshooting',
                'ajax-search-for-woocommerce') . '">' . __('troubleshooting',
                'ajax-search-for-woocommerce') . '</a>';
        add_thickbox();
        ob_start();
        ?>


        <div class="dgwt-wcas-settings-info dgwt-wcas-bc-notice js-dgwt-wcas-bc-notice">
            <div class="dgwt-wcas-bc-main-notice">
                <span class="dgwt-wcas-bc-notice-head"><?php echo __('<span style="color:#9b5c8f">Complete the update process</span>', 'ajax-search-for-woocommerce'); ?></span>- <?php _e('In the last update we have changed the AJAX search form on the frontend (CSS styles, names of the HTML classes etc.). You have to check if the AJAX search form looks correct with your theme after these changes.', 'ajax-search-for-woocommerce') ?>
            </div>

            <div class="dgwt-wcas-container">

                <div class="dgwt-wcas-bc-todo-wrapp">

                    <div class="js-dgwt-wcas-todo-old<?php echo ! $this->isEnabled() ? ' dgwt-wcas-hidden' : ''; ?>">
                        <span class="dgwt-wcas-bc-todo-head"><?php _e('What should you do now?', 'ajax-search-for-woocommerce'); ?></span>

                        <ol class="dgwt-wcas-bc-steps">
                            <li><?php _e('Switch manually to the latest version to check if the AJAX search form displays correctly with your theme', 'ajax-search-for-woocommerce'); ?></li>
                        </ol>
                    </div>

                    <div class="js-dgwt-wcas-todo-latest<?php echo $this->isEnabled() ? ' dgwt-wcas-hidden' : ''; ?>">
                        <span class="dgwt-wcas-bc-todo-head dgwt-wcas-bc-todo-head--latest"><?php _e('You have activated the latest version. Follow the steps below:', 'ajax-search-for-woocommerce'); ?></span>

                        <ol class="dgwt-wcas-bc-steps">
                            <li><?php printf(__('Visit your <a target="_blank" href="%s">store</a>', 'ajax-search-for-woocommerce'), get_permalink(wc_get_page_id('shop'))); ?></li>
                            <li><?php _e('Check if the AJAX search form displays correctly', 'ajax-search-for-woocommerce'); ?></li>
                            <li><?php printf(__('If it is ok, click %s', 'ajax-search-for-woocommerce'), '<button class="button button-small dgwt-wcas-bc-button-approve js-dgwt-wcas-bc-wipe-all">' . __('finish the update', 'ajax-search-for-woocommerce') . '</button>'); ?></li>
                            <li><?php printf(__('If not, switch back to <code>previous version %s</code> and see %s', 'ajax-search-for-woocommerce'), '(v' . $vompatibleVersion . ')', $troubleshootingLink); ?></li>
                        </ol>
                    </div>

                </div>

                <div class="dgwt-wcas-bc-switcher">
                    <h3 class="dgwt-wcas-bc-switcher-head"><?php _e('Version switcher', 'ajax-search-for-woocommerce'); ?></h3>
                    <div class="dgwt-wcas-bc-error dgwt-wcas-hidden"><?php _e('Something went wrong. Refresh the page and try again.', 'ajax-search-for-woocommerce'); ?></div>
                    <div class="dgwt-wcas-bc-success dgwt-wcas-hidden"><?php _e('Saved!', 'ajax-search-for-woocommerce'); ?></div>

                    <label class="dgwt-wcas-toggler js-dgwt-wcas-switch-left<?php echo $this->isEnabled() ? ' dgwt-wcas-toggler--is-active' : ''; ?>"><?php echo __('previous version', 'ajax-search-for-woocommerce') . ' <small>( v' . $vompatibleVersion . ')</small>'; ?></label>
                    <div class="dgwt-wcas-toggle<?php echo $this->isEnabled() ? ' dgwt-wcas-toggle--mute' : ''; ?>">
                        <input type="checkbox" <?php checked(true, $this->isEnabled(), true); ?> class="dgwt-wcas-check js-dgwt-wcas-switcher">
                        <div class="dgwt-wcas-switch">
                            <img class="js-dgwt-wcas-bc-spinner dgwt-wcas-bc-spinner dgwt-wcas-hidden" src="<?php echo DGWT_WCAS_URL; ?>/assets/img/preloader.gif">
                        </div>
                    </div>
                    <label class="dgwt-wcas-toggler js-dgwt-wcas-switch-right<?php echo ! $this->isEnabled() ? ' dgwt-wcas-toggler--is-active' : ''; ?>"><?php echo __('latest version', 'ajax-search-for-woocommerce') . ' <small>( v' . DGWT_WCAS_VERSION . ')</small>'; ?></label>
                </div>

            </div>


            <br/>
            <small><?php printf(__('If the AJAX search form displays incorrectly after switching to the latest version, see the  %s', 'ajax-search-for-woocommerce'), $troubleshootingLink); ?></small>


            <div id="dgwt-wcas-troubleshooting" class="js-dgwt-wcas-read-more-wrapp hidden">


                <h3>
                    #1 <?php echo __('Your AJAX search form does not display correctly after manual switching to the latest version.', 'ajax-search-for-woocommerce'); ?>
                </h3>

                <p>
                    <?php printf(__('Probably your theme uses additional CSS styles to customize the appearance of the AJAX search form. Contact the authors of your theme. Maybe they have to update their CSS styles if necessary.', 'ajax-search-for-woocommerce'), $themeLink); ?>
                </p>

                <table>
                    <tbody>
                    <tr>
                        <td colspan="2" style="letter-spacing: 3px;border-bottom: 1px solid #eee;"><?php _e('Your Theme Details', 'ajax-search-for-woocommerce'); ?></td>
                    </tr>

                    <?php if ( ! empty($themeName)): ?>
                        <tr>
                            <th><?php _ex('Name:', 'Wordpress theme name', 'ajax-search-for-woocommerce'); ?></th>
                            <td>
                                <?php
                                if ( ! empty($themeUri)) {
                                    echo '<a href="' . $themeUri . '" target="_blank">' . $themeName . '</a>';
                                } else {
                                    echo $themeName;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( ! empty($themeAuthor)): ?>
                        <tr>
                            <th><?php _e('Author:', 'ajax-search-for-woocommerce'); ?></th>
                            <td>
                                <?php
                                if ( ! empty($themeAuthorURL)) {
                                    echo '<a href="' . $themeAuthorURL . '" target="_blank">' . $themeAuthor . '</a>';
                                } else {
                                    echo $themeAuthor;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>

                <p>
                    <?php printf(__('A sample e-mail message that you can send them:', 'ajax-search-for-woocommerce'), $themeLink); ?>
                </p>

                <div class="dgwt-wcas-settings-info" style="font-size: 85%;line-height: 120%">
                    <?php
                    echo sprintf(__(
                        "Hello,
                    <br /><br />
                    I use your %s theme with AJAX Search for WooCommerce plugin - (https://wordpress.org/plugins/ajax-search-for-woocommerce/)
                    <br /><br />
                    In the lates version of AJAX Search for WooCommerce %s they have changed the search form on the frontend (CSS, HTML etc.).
                    <br /><br />
                    Could you check it and update your CSS if necessary?", 'ajax-search-for-woocommerce'),
                        $themeName,
                        '(v' . DGWT_WCAS_VERSION . ')');
                    ?>
                </div>

                <br/>
                <br/>
                <p>
                    <?php _e('If you have any questions, do not hesitate contact <a href="mailto:dgoraplugins@gmail.com?subject=Ajax Search for WooCommerce - Update problem">our support </a> or <a href="https://wordpress.org/support/plugin/ajax-search-for-woocommerce" target="_blank">write on the WordPress.org Support</a>.', 'ajax-search-for-woocommerce'); ?>
                </p>

            </div>

            <a href="#" class="dgwt-wcas-bc-remind-me js-dgwt-wcas-bc-remind-me">
                <span class="dashicons dashicons-no-alt"></span>
                <?php _e('Remind me later', 'ajax-search-for-woocommerce') ?>
            </a>
        </div>
        <?php

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

}