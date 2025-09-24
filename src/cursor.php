<?php
/**
 * WordPress Custom Cursor Follower - PHP Implementation
 * 
 * @package CustomCursor
 * @version 1.0.0
 * @author YourName
 * @license MIT
 * 
 * USAGE:
 * 1. Install "Code Snippets" plugin
 * 2. Create new snippet
 * 3. Paste this entire code
 * 4. Set to "Only run on site front-end"
 * 5. Save and activate
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom cursor to WordPress footer
 */
function wp_add_custom_cursor_follower() {
    // Only load on front-end
    if (is_admin()) {
        return;
    }
    
    // Check if user is on mobile (optional optimization)
    $is_mobile = wp_is_mobile();
    
    ?>
    <!-- WordPress Custom Cursor Follower v1.0.0 -->
    <style id="wp-custom-cursor-styles">
        /* WordPress Custom Cursor Styles */
        body, html, * { cursor: auto !important; }
        
        .wp-custom-cursor {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 999999;
            opacity: 0;
            transition: opacity 0.2s ease;
            will-change: transform, opacity;
        }
        
        .wp-custom-cursor.active { opacity: 1; }
        
        .wp-cursor-dot {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #000;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.15s ease;
        }
        
        .wp-cursor-circle {
            position: absolute;
            width: 12px;
            height: 12px;
            border: 1px solid rgba(0, 0, 0, 0.4);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.25s ease;
        }
        
        .wp-custom-cursor.hover .wp-cursor-dot {
            width: 3px;
            height: 3px;
        }
        
        .wp-custom-cursor.hover .wp-cursor-circle {
            width: 24px;
            height: 24px;
            border: 1.5px solid rgba(0, 0, 0, 0.6);
        }
        
        .wp-custom-cursor.click .wp-cursor-dot {
            width: 5px;
            height: 5px;
        }
        
        .wp-custom-cursor.click .wp-cursor-circle {
            width: 10px;
            height: 10px;
            border: 2px solid rgba(0, 0, 0, 0.8);
        }
        
        <?php if ($is_mobile): ?>
        .wp-custom-cursor { display: none !important; }
        <?php endif; ?>
        
        @media (hover: none) and (pointer: coarse), (max-width: 768px) {
            .wp-custom-cursor { display: none !important; }
        }
    </style>
    
    <div class="wp-custom-cursor" id="wpCustomCursor">
        <div class="wp-cursor-circle"></div>
        <div class="wp-cursor-dot"></div>
    </div>
    
    <script id="wp-custom-cursor-script">
        (function() {
            'use strict';
            
            document.addEventListener('DOMContentLoaded', function() {
                const cursor = document.getElementById('wpCustomCursor');
                if (!cursor) return;
                
                let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;
                let isMoving = false;
                
                document.addEventListener('mousemove', function(e) {
                    mouseX = e.clientX;
                    mouseY = e.clientY;
                    cursor.classList.add('active');
                    
                    if (!isMoving) {
                        isMoving = true;
                        animate();
                    }
                });
                
                function animate() {
                    cursorX += (mouseX - cursorX) * 0.3;
                    cursorY += (mouseY - cursorY) * 0.3;
                    
                    cursor.style.left = cursorX + 'px';
                    cursor.style.top = cursorY + 'px';
                    
                    if (Math.abs(mouseX - cursorX) + Math.abs(mouseY - cursorY) > 0.5) {
                        requestAnimationFrame(animate);
                    } else {
                        isMoving = false;
                    }
                }
                
                // WordPress-specific clickable elements
                const clickables = 'a, button, input, select, textarea, [onclick], .btn, .wp-block-button, .elementor-button, .menu-item a, .widget a, .post-title a, .entry-title a';
                
                document.addEventListener('mouseover', function(e) {
                    if (e.target.matches(clickables) || e.target.closest(clickables)) {
                        cursor.classList.add('hover');
                    }
                });
                
                document.addEventListener('mouseout', function(e) {
                    if (e.target.matches(clickables) || e.target.closest(clickables)) {
                        cursor.classList.remove('hover');
                    }
                });
                
                document.addEventListener('mousedown', function() {
                    cursor.classList.add('click');
                });
                
                document.addEventListener('mouseup', function() {
                    cursor.classList.remove('click');
                });
                
                document.addEventListener('mouseleave', function() {
                    cursor.classList.remove('active');
                });
                
                document.addEventListener('mouseenter', function() {
                    cursor.classList.add('active');
                });
            });
        })();
    </script>
    <?php
}

// Hook into WordPress footer
add_action('wp_footer', 'wp_add_custom_cursor_follower');

/**
 * Optional: Add cursor settings to Customizer
 */
function wp_custom_cursor_customizer($wp_customize) {
    // Add section
    $wp_customize->add_section('custom_cursor_settings', array(
        'title'    => 'Custom Cursor',
        'priority' => 130,
    ));
    
    // Add cursor color setting
    $wp_customize->add_setting('cursor_color', array(
        'default'   => '#000000',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cursor_color', array(
        'label'    => 'Cursor Color',
        'section'  => 'custom_cursor_settings',
        'settings' => 'cursor_color',
    )));
}
add_action('customize_register', 'wp_custom_cursor_customizer');

/**
 * Apply customizer settings
 */
function wp_custom_cursor_customizer_css() {
    $cursor_color = get_theme_mod('cursor_color', '#000000');
    ?>
    <style>
        .wp-cursor-dot { background-color: <?php echo esc_attr($cursor_color); ?>; }
        .wp-cursor-circle { border-color: <?php echo esc_attr($cursor_color); ?>40; }
    </style>
    <?php
}
add_action('wp_head', 'wp_custom_cursor_customizer_css');

/**
 * Enqueue scripts properly (alternative method)
 */
function wp_custom_cursor_enqueue_scripts() {
    wp_add_inline_style('wp-block-library', '
        .wp-custom-cursor { /* styles here */ }
    ');
}
add_action('wp_enqueue_scripts', 'wp_custom_cursor_enqueue_scripts');
?>
