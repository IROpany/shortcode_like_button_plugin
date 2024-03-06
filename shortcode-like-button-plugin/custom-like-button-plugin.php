<?php
/*
Plugin Name: Shortcode Like Button Plugin
Description: Adds a Shortcode like button to posts.
Version: 2.0
Author: Iro
*/

// ファイルへの直接アクセスした場合は終了する
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}



// スクリプトを読み込む
function custom_like_button_enqueue_scripts() {
    wp_enqueue_script('jquery'); // jQueryを読み込む
    wp_enqueue_script('custom-like-button', plugin_dir_url(__FILE__) . 'js/custom-like-button.js', array('jquery'), false , true); // カスタムスクリプトを読み込む
    wp_localize_script('custom-like-button', 'custom_like_button_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('custom-like-button-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'custom_like_button_enqueue_scripts');

// ショートコードを処理するための関数
function custom_like_button_shortcode($atts) {
    // 投稿のIDを取得
    $post_id = get_the_ID();

    // カウントを取得または初期化
    $like_count = get_post_meta($post_id, 'custom_like_count', true);
    if (!$like_count) {
        $like_count = 0;
    }

    // ボタンのHTMLを生成
    $button_html = '
        <p>いいねの数: <span class="like-count-' . $post_id . '">' . $like_count . '</span></p>
        <button class="my-like-button" data-post-id="' . $post_id . '" data-action="increment_likes">いいね</button>
    ';

    return $button_html;
}
add_shortcode('custom_like_button', 'custom_like_button_shortcode');

// カウントを増やすためのAJAX処理
function increment_likes() {
    check_ajax_referer('custom-like-button-nonce', 'security');

    $post_id = intval($_POST['post_id']);
    $like_count = get_post_meta($post_id, 'custom_like_count', true);
    $like_count++;

    update_post_meta($post_id, 'custom_like_count', $like_count);

    echo $like_count;
    wp_die();
}
add_action('wp_ajax_increment_likes', 'increment_likes');
add_action('wp_ajax_nopriv_increment_likes', 'increment_likes');

// カウントの初期化
function init_like_count() {
    $post_id = get_the_ID();
    add_post_meta($post_id, 'custom_like_count', 0, true);
}
add_action('wp', 'init_like_count');
