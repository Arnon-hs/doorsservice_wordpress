<?php


namespace DgoraWcas;


class Multilingual
{

    /**
     * Check if the website is multilingual
     *
     * @return bool
     */
    public static function isMultilingual()
    {

        $isMultilingual = false;

        if (
            count(self::getLanguages()) > 1
            && (self::isWPML())
        ) {
            $isMultilingual = true;
        }

        return $isMultilingual;
    }

    /**
     * Check if WPMl is active
     *
     * @return bool
     */
    public static function isWPML()
    {
        return class_exists('SitePress');
    }

    /**
     * Check if language code has 2 leters
     *
     * @param $lang
     *
     * @return bool
     */
    public static function isLangCode($lang)
    {
        return (bool)preg_match('/^[A-Za-z]{2}$/', $lang);
    }

    /**
     * Get default language
     *
     * @return string
     */
    public static function getDefaultLanguage()
    {
        $defaultLang = 'en';

        if (self::isWPML()) {
            $defaultLang = apply_filters('wpml_default_language', null);
        }

        if (empty($defaultLang)) {
            $locale      = get_locale();
            $defaultLang = substr($locale, 0, 2);
        }

        return $defaultLang;
    }

    /**
     * Current language
     *
     * @return string
     */
    public static function getCurrentLanguage()
    {
        $currentLang = self::getDefaultLanguage();

        if (self::isWPML()) {
            $currentLang = apply_filters('wpml_current_language', null);
        }

        return $currentLang;
    }

    /**
     * Get Language of product
     *
     * @param int $postID
     *
     * @return string
     */
    public static function getPostLang($postID, $postType = 'product')
    {
        $lang = self::getDefaultLanguage();

        $postType = 'post_' . $postType;

        if (self::isWPML()) {
            global $wpdb;
            $tranlationsTable = $wpdb->prefix . 'icl_translations';
            $sql              = $wpdb->prepare("SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type=%s 
                                          AND element_id=%d", sanitize_key($postType), $postID);
            $query            = $wpdb->get_var($sql);

            if ( ! empty($query) && strlen($query) === 2) {
                $lang = $query;
            }
        }

        return $lang;
    }

    /**
     * Get term lang
     *
     * @param int $term ID
     * @param string $taxonomy
     *
     * @return string
     */
    public static function getTermLang($termID, $taxonomy)
    {
        $lang = self::getDefaultLanguage();

        if (self::isWPML()) {
            global $wpdb;

            $elementType      = 'tax_' . sanitize_key($taxonomy);
            $tranlationsTable = $wpdb->prefix . 'icl_translations';

            $sql = $wpdb->prepare("SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type = %s
                                          AND element_id=%d",
                $elementType, $termID);

            $query = $wpdb->get_var($sql);

            if ( ! empty($query) && strlen($query) == 2) {
                $lang = $query;
            }
        }

        return $lang;
    }

    /**
     * Get permalink
     *
     * @param string $postID
     * @param string $url
     * @param string $lang
     *
     * @return string
     */
    public static function getPermalink($postID, $url = '', $lang = '')
    {
        $permalink = $url;

        if (self::isWPML()) {
            $permalink = apply_filters('wpml_permalink', $url, $lang, true);
        }

        return $permalink;
    }

    /**
     * Active languages
     *
     * @return langs
     */
    public static function getLanguages()
    {

        $langs = array();

        if (self::isWPML()) {
            $wpmlLangs = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));

            if (is_array($wpmlLangs)) {
                foreach ($wpmlLangs as $langCode => $details) {
                    if (self::isLangCode($langCode)) {
                        $langs[] = strtolower($langCode);
                    }
                }
            }
        }

        $hiddenLangs = apply_filters( 'wpml_setting', array(), 'hidden_languages' );
        if(!empty($hiddenLangs) && is_array($hiddenLangs)){
            $langs = array_unique(array_merge($langs, $hiddenLangs));
        }

        if (empty($langs)) {
            $langs[] = self::getDefaultLanguage();
        }

        return $langs;

    }

    /**
     * Get all terms in one taxonomy for all languages
     *
     * @param string $taxonomy
     *
     * @return array of WP_Term objects
     */
    public static function getTermsInAllLangs($taxonomy)
    {
        $terms   = array();
        $usedIds = array();

        if (self::isWPML()) {
            $currentLang = self::getCurrentLanguage();

            foreach (self::getLanguages() as $lang) {
                do_action('wpml_switch_language', $lang);
                $args        = array(
                    'taxonomy'         => $taxonomy,
                    'hide_empty'       => true,
                    'suppress_filters' => false
                );
                $termsInLang = get_terms(apply_filters('dgwt/wcas/search/' . $taxonomy . '/args', $args));

                if ( ! empty($termsInLang) && is_array($termsInLang)) {
                    foreach ($termsInLang as $termInLang) {

                        if ( ! in_array($termInLang->term_id, $usedIds)) {
                            $terms[]   = $termInLang;
                            $usedIds[] = $termInLang->term_id;
                        }
                    }
                }

            }

            do_action('wpml_switch_language', $currentLang);
        }

        return $terms;
    }

    /**
     * Get term in specific language
     *
     * @param int $termID
     * @param string $taxonomy
     * @param string $lang
     *
     * @return object WP_Term
     */
    public static function getTerm($termID, $taxonomy, $lang)
    {
        $term = null;

        if (self::isWPML()) {
            $currentLang = self::getCurrentLanguage();
            do_action('wpml_switch_language', $lang);

            $term = get_term($termID, $taxonomy);

            do_action('wpml_switch_language', $currentLang);
        }

        return $term;
    }

}