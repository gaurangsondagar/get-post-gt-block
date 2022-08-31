<?php

class Get_Block_Register {

    function __construct() {

        add_action('init', array($this, 'get_register_block'));


        add_action('rest_api_init', function () {
            register_rest_route('post_data/v1', '/post_detail/(?P<id>\d+)', [
                'method' => 'GET',
                'callback' => array($this, 'get_single_post_data')
            ]);
        });
    }


    function get_register_block() {

        // Register JavasScript File build/index.js
        wp_register_script(
            'gtgt-block-edior-script',
            GPGB_PLUGIN_URL_PATH . '/build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-api'),
            filemtime(GPGB_PLUGIN_DIR_PATH . 'build/index.js')
        );

        // Register editor style src/editor.css
        wp_register_style(
            'gtgt-block-editor-style',
            GPGB_PLUGIN_URL_PATH . '/src/editor.css',
            array('wp-edit-blocks'),
            filemtime(GPGB_PLUGIN_DIR_PATH . 'src/editor.css')
        );

        // Register front end block style src/style.css
        wp_register_style(
            'gtgt-block-front-end-style',
            GPGB_PLUGIN_URL_PATH . '/src/style.css',
            array(),
            filemtime(GPGB_PLUGIN_DIR_PATH . 'src/style.css')
        );

        // Register your block
        register_block_type('gtgt-block/get-post-data', array(
            'editor_script' => 'gtgt-block-edior-script',
            'editor_style' => 'gtgt-block-editor-style',
            'style' => 'gtgt-block-front-end-style',
        ));
    }


    function get_single_post_data($data) {

        $post = get_post($data['id']);
        $data = array();
        $data['id'] = $post->ID;
        $data['post_title'] = $post->post_title;
        $data['post_excerpt'] = $post->post_excerpt;

        $content = $post->post_content;
        $content = apply_filters('the_content', $content);
        //$content = str_replace(']]>', ']]&gt;', $content);
        $data['post_content'] = strip_tags($content);
        $feature_image = '';
        if (has_post_thumbnail( $post->ID ) ){
            $feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $feature_image = $feature_image[0];
        }
        $data['feature_image'] = $feature_image;
        $data['post_date'] = date("d M Y", strtotime($post->post_date));
        echo json_encode($data);
    }
}
new Get_Block_Register();
