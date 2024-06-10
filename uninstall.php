<?php
// プラグインがアンインストールされる場合のみ実行されるようにする
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// いいねカウントメタデータを削除する関数
function custom_like_button_delete_plugin() {
    global $wpdb;

    // すべての投稿メタデータを削除
    $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = 'custom_like_count'" );

    // 必要に応じて、プラグインで作成されたその他のオプションやデータを削除
    // delete_option('custom_like_button_option');
}

// アンインストール時にクリーンアップ関数を呼び出す
custom_like_button_delete_plugin();
?>
