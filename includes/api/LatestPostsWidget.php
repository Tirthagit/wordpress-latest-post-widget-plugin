<?php
declare(strict_types=1);

namespace LatestPostWidget\Includes\Api;

use WP_Widget;
use WP_Query;

defined('ABSPATH') || exit;

class LatestPostsWidget extends WP_Widget
{
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct(

            // Base ID
            'latest-post-widget',

            // Widget name to show in the UI
            __('Latest Post Widget', 'latestpostwidget'),

            // Description
            ['description' => __('Displays recent posts from a selected category.', 'latestpostwidget')]
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        // extract($args);
        echo $args['before_widget'];

        $defaultTitle = __('Latest Posts from Category');
        $title = !empty($instance['title']) ? $instance['title'] : $defaultTitle;

        $title = apply_filters('widget_title', $title, $instance);
        $number = (int) ($instance['number'] ?? 5);
        $category = $instance['category'] ?? '';

        if (!empty($title)) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        if (empty($category)) {
            echo '<p>' . esc_html__('No category selected', 'latestpostwidget') . '</p>';
            echo $args['after_widget'];
            return;
        }

        $query = new WP_Query(
            [
                'cat' => (int) $category,
                'posts_per_page' => $number,
                'post_status' => 'publish'
            ]
        );


        if ($query->have_posts()): ?>
            <ul
                style="list-style: none; padding: 0; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif;">
                <?php while ($query->have_posts()):
                    $query->the_post(); ?>
                    <li style="padding: 15px 0; border-bottom: 1px solid #eaeaea; margin-bottom: 10px;">
                        <a href="<?php echo esc_url(get_permalink()); ?>"
                            style="display: block; font-size: 1.1em; font-weight: 600; color: #1a73e8; text-decoration: none; margin-bottom: 5px;">
                            <?php echo esc_html(get_the_title()); ?>
                        </a>

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; font-size: 0.85em; color: #5f6368;">
                            <span style="display: inline-flex; align-items: center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    style="margin-right: 5px;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                    style="color: #5f6368; text-decoration: none;">
                                    <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </span>

                            <span style="display: inline-flex; align-items: center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    style="margin-right: 5px;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php echo esc_html(get_the_date('M j, Y')); ?>
                            </span>
                            <small>
                                Posted
                                <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'); ?>
                            </small>
                        </div>
                    </li>
                <?php endwhile ?>
            </ul>
        <?php else: ?>
            <p style="padding: 15px; background: #f8f9fa; border-radius: 4px; color: #5f6368; text-align: center;">
                <?php echo esc_html__('No posts found', 'latestpostwidget'); ?>
            </p>
        <?php endif;

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title = esc_attr($instance['title'] ?? __('Latest Posts from Category', 'latestpostwidget'));
        $number = esc_attr($instance['number'] ?? 5);
        $category = $instance['category'] ?? '';

        $categories = get_categories(['hide_empty' => false]);

        ?>
        <p>
            <label
                for="<?= esc_attr($this->get_field_id('title')); ?>"><?= esc_html__('Title:', 'latestpostwidget'); ?></label>
            <input class="widefat" id="<?= esc_attr($this->get_field_id('title')); ?>"
                name="<?= esc_attr($this->get_field_name('title')); ?>" type="text" value="<?= $title; ?>">
        </p>
        <p>
            <label
                for="<?= esc_attr($this->get_field_id('number')); ?>"><?= esc_html__('Number of posts to show:', 'latestpostwidget'); ?>
            </label>
            <input class="tiny-text" id="<?= esc_attr($this->get_field_id('number')); ?>"
                name="<?= esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?= $number; ?>"
                size="3">
        </p>
        <p>
            <label for="<?= esc_attr($this->get_field_id('category')); ?>"><?= esc_html__('Category:', 'latestpostwidget'); ?>
            </label>
            <select class="widefat" id="<?= esc_attr($this->get_field_id('category')); ?>"
                name="<?= esc_attr($this->get_field_name('category')); ?>">
                <option value=""><?= esc_html__('Select a Category', 'latestpostwidget'); ?></option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc_attr($cat->term_id); ?>" <?= selected((int) $category, $cat->term_id, false); ?>>
                        <?= esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = [];

        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = (int) ($new_instance['number'] ?? 5);
        $instance['category'] = (int) ($new_instance['category'] ?? 0);

        if ($instance['number'] <= 0) {
            $instance['number'] = 5;
        }

        return $instance;
    }
}
