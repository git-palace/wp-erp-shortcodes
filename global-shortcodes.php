<?php
add_shortcode( 'current-user-avatar', function( $atts ) {
    extract(shortcode_atts(array(
        'size' => 32
    ), $atts));

    $employee = new \WeDevs\ERP\HRM\Employee( get_current_user_id() );

    $template = '';
    ob_start();
?>
    <div class="current-user-avatar">
        <?php _e( $employee->get_avatar( $size ) ); ?>
    </div>

    <style type="text/css">
    /* avatar in nav */
    .sidr-class-current-user-avatar,
    .current-user-avatar {
        text-align: center;
    }

    .sidr-class-current-user-avatar img,
    .current-user-avatar img {
        border-radius: 50%;
    }
    </style>
<?php
    $template = ob_get_contents();
    ob_end_clean();

    return $template;
} );



add_shortcode( 'switch_back_link', function() {
    if ( !class_exists( 'user_switching') )
        return;

    $old_user = user_switching::get_old_user();
    if ( !$old_user )
        return;

    $link = sprintf(
        /* Translators: 1: user display name; 2: username; */
        __( 'Switch back to %1$s (%2$s)', 'user-switching' ),
        $old_user->display_name,
        $old_user->user_login
    );
    $url = add_query_arg( array( 'redirect_to' => urlencode( user_switching::current_url() ) ), user_switching::switch_back_url( $old_user ) );

    return '<a href="' . esc_url( $url ) . '" style="display: block; color: #fff; text-align: center;">' . esc_html( $link ) . '</a>';
} );



add_shortcode( 'sellers-shield-page-content', function() {
    $licenseId = get_user_meta( get_current_user_id(), 'licenseId', true );
    if ( empty( $licenseId ) ) {
        ob_start();
?>
        <script type="text/javascript">
            window.location.href = '<?php _e( home_url( '/dashboard/settings' ) ) ?>';
        </script>
<?php
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    $template = '';
    if ( defined( 'SELLERS_SHIELD_SECRET' ) && empty( SELLERS_SHIELD_SECRET ) )
        return $template;

    $URL = sprintf( 'https://protect.sellersshield.com/api/v1/agent/token?secret=%s&licenseId=%s', SELLERS_SHIELD_SECRET, $licenseId );
    $response = wp_remote_get( $URL );

    if ( is_wp_error( $response ) )
        return $template;

    if ( $response['body'] == 'Unauthorized' )
        return;

    ob_start();
?>

    <iframe style="min-height: 100vh;" src="<?php esc_attr_e( 'https://protect.sellersshield.com/api/v1/agent/auth?token=' . $response['body'] ); ?>"></iframe>

<?php
    $template = ob_get_contents();

    ob_end_clean();

    return $template;
} );
