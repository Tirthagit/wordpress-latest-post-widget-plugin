<?php
declare(strict_types=1);

namespace Includes\Api;

use WP_Widget;
use WP_Error;
use WP_Query;

defined('ABSPATH') || exit;

class LatestPostsWidget extends WP_Widget
{
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
            echo '<p>' . esc_html__('No category selected', 'paktoluspostwidget') . '</p>';
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


        if ($query->have_posts()):
            echo '<ul>';
            while ($query->have_posts()):
                $query->the_post();
                echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . ' </a></li>';
            endwhile;
            echo '</ul>';
        else:
            echo '<p>' . esc_html__('No posts found', 'paktoluspostwidget') . '</p>';
        endif;


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
        $title = esc_attr($instance['title'] ?? __('Latest Posts from Category', 'paktoluspostwidget'));
        $number = esc_attr($instance['number'] ?? 5);
        $category = $instance['category'] ?? '';

        $categories = get_categories(['hide_empty' => false]);

        ?>
        <p>
            <label
                for="<?= esc_attr($this->get_field_id('title')); ?>"><?= esc_html__('Title:', 'paktoluspostwidget'); ?></label>
            <input class="widefat" id="<?= esc_attr($this->get_field_id('title')); ?>"
                name="<?= esc_attr($this->get_field_name('title')); ?>" type="text" value="<?= $title; ?>">
        </p>
        <p>
            <label
                for="<?= esc_attr($this->get_field_id('number')); ?>"><?= esc_html__('Number of posts to show:', 'paktoluspostwidget'); ?>
            </label>
            <input class="tiny-text" id="<?= esc_attr($this->get_field_id('number')); ?>"
                name="<?= esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?= $number; ?>"
                size="3">
        </p>
        <p>
            <label for="<?= esc_attr($this->get_field_id('category')); ?>"><?= esc_html__('Category:', 'paktoluspostwidget'); ?>
            </label>
            <select class="widefat" id="<?= esc_attr($this->get_field_id('category')); ?>"
                name="<?= esc_attr($this->get_field_name('category')); ?>">
                <option value=""><?= esc_html__('Select a Category', 'paktoluspostwidget'); ?></option>
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
