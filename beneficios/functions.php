<?php

class Beneficios_Assets {
    
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'styles']);
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
    }
    
    public function styles()
    {
       
        wp_enqueue_style('beneficios-main-css', get_template_directory_uri() . '/beneficios/css/main.css');
        wp_enqueue_style('beneficios-article-css', get_template_directory_uri() . '/beneficios/css/articles-block.css');
        wp_enqueue_style('beneficios-especial-css', get_template_directory_uri() . '/beneficios/css/simple-especial.css');
        wp_enqueue_style('beneficios-front-css', get_template_directory_uri() . '/beneficios/css/beneficios.css');
        
    }

    public function scripts()
    {
        wp_enqueue_script('beneficios-front-js', get_template_directory_uri() . '/beneficios/js/script.js', array(), null, true);
    }
}

function beneficios_assets()
{
    return new Beneficios_Assets();
}

beneficios_assets();