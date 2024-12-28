<?php
/**
 * Related Posts Class
 * 
 * Handles the logic to display related posts based on category.
 */
class Related_Posts {
    
    /**
     * Initialize the plugin hooks.
     */
    public function init() {
        // Hook to display related posts below the content
        add_filter('the_content', array($this, 'display_related_posts'));
    }

    /**
     * Display related posts after the main content.
     *
     * @param string $content The content of the current post.
     * @return string The modified content with related posts.
     */
    public function display_related_posts($content) {
        // Only display related posts on single post pages
        if (is_single()) {
            $related_posts_html = $this->get_related_posts();
            
            // Append related posts below the content
            $content .= $related_posts_html;
        }

        return $content;
    }

    /**
     * Get related posts based on the current post's categories.
     *
     * @return string HTML content for related posts.
     */
    private function get_related_posts() {
        global $post;
        
        // Get the categories of the current post
        $categories = get_the_category($post->ID);
        if (empty($categories)) {
            return ''; // No categories, no related posts
        }
        
        // Get the first category (you can modify this if you want more)
        $category_ids = array_map(function($category) { return $category->term_id; }, $categories);
        
        // Query posts from the same categories excluding the current post
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'post__not_in' => array($post->ID),
            'cat' => implode(',', $category_ids),
            'orderby' => 'rand', // Shuffle the posts
            'no_found_rows' => true,
        );
        
        $related_posts_query = new WP_Query($args);

        // If there are related posts, create HTML content for them
        if ($related_posts_query->have_posts()) {
            $html = '<h3>Related Posts</h3><ul class="related-posts">';
            
            while ($related_posts_query->have_posts()) {
                $related_posts_query->the_post();
                
                // Get the thumbnail
                $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                
                // Start building the related post HTML
                $html .= '<li class="related-post-item">';
                if ($thumbnail) {
                    $html .= '<div class="related-post-thumbnail">' . $thumbnail . '</div>';
                }
                $html .= '<div class="related-post-title"><a href="' . get_permalink() . '">' . esc_html(get_the_title()) . '</a></div>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            wp_reset_postdata();
            
            return $html;
        }

        return ''; // No related posts found
    }
}
