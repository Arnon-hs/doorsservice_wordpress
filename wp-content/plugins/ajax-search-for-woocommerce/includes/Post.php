<?php

namespace DgoraWcas;

use DgoraWcas\Engines\TNTSearchMySQL\Indexer\Utils;

class Post
{
    private $postID = 0;
    private $post;
    private $postType = 'post';
    private $langCode = 'en';

    public function __construct($post)
    {
        if ( ! empty($post) && is_object($post) && is_a($post, 'WP_Post')) {
            $this->postID   = absint($post->ID);
            $this->postType = $post->post_type;
        }

        if (is_numeric($post)) {
            $this->postID   = absint($post);
            $this->postType = get_post_type($post);
        }

        $this->post = get_post($this->postID);

        $this->setLanguage();
    }

    /**
     * Set info about product language
     *
     * @return void
     */
    public function setLanguage()
    {
        $this->langCode = Multilingual::getPostLang($this->getID(), $this->postType);
    }

    /**
     * Get product ID (post_id)
     * @return INT
     */
    public function getID()
    {
        return $this->postID;
    }


    /**
     * Get post title
     *
     * @return string
     */
    public function getTitle()
    {
        return apply_filters('dgwt/wcas/post/title', $this->post->post_title, $this->postType);
    }

    /**
     * Get prepared product description
     *
     * @param string $type full|short|suggestions
     *
     * @return string
     */
    public function getDescription()
    {

        $output = '';

        $desc = $this->post->post_excerpt;

        if (empty($desc)) {
            $desc = $this->post->post_content;
        }

        if ( ! empty($desc)) {
            $output = Utils::clearContent($desc);
            $output = Helpers::strCut($output, 120);
            $output = html_entity_decode($output);
        }


        return apply_filters('dgwt/wcas/post/description', $output, $this->postType);
    }

    /**
     * Get product permalink
     *
     * @return string
     */
    public function getPermalink()
    {
        $permalink = get_permalink($this->getID());

        if (Multilingual::isMultilingual()) {
            $permalink = Multilingual::getPermalink($this->getID(), $permalink, $this->langCode);
        }

        return apply_filters('dgwt/wcas/post/permalink', $permalink, $this->postType);
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
     * Check, if class is initialized correctly
     *
     * @return bool
     */
    public function isValid()
    {
        $isValid = false;

        if (is_a($this->post, 'WP_Post')) {
            $isValid = true;
        }

        return $isValid;
    }

}