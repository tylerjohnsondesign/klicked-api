<?php
/**
Register Widget
**/
function klicked_api_reverereport_load_widget() {
    register_widget('klicked_api_reverereport_widget');
}
add_action('widgets_init', 'klicked_api_reverereport_load_widget');

/**
Create Widget
**/
class klicked_api_reverereport_widget extends WP_Widget {
    // Back End
    function __construct() {
        parent::__construct(
            'klicked_api_reverereport_widget', // ID
            __('Revere Report Articles', 'apiKlicked'), // UI Name
            array(
                'description' => __('Displays articles from RevereReport.com.', 'revereKlicked'), 
            )
        );
    }
    
    // Front End
    public function widget($args, $instance) {
        echo '<div id="revere-report-widget" class="widget">';
        echo '<div class="revere-report-logo"><a href="https://reverereport.com" target="_blank"><img src="'.KLICKEDAPI_BASE_URI.'assets/reverereport.png" alt="RevereReport.com"/></a></div>';
        echo klicked_api_reverereport_widget_output($instance['articles']);
        echo '</div>';
        echo '<style type="text/css">div#revere-report-widget{background:#f8f8f8;padding:15px;margin:20px 0}.revere-report-logo img{max-width:50%;display:inline-block}.revere-report-logo{text-align:center;margin-bottom:20px}.revere-title{font-family:sans-serif;font-weight:700;font-size:18px;line-height:20px;margin-bottom:10px}.revere-title a{color:#b23636}.revere-image{margin-bottom:5px}.revere-article-cont{margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid rgba(0,0,0,.15)}.revere-image img{width:100%}.revere-meta{font-family:sans-serif;font-size:12px;text-align:center}.revere-title a:hover{color:#1f3b50}.revere-meta a,.revere-meta a:hover{color:#1f3a4f}span.revere-date,span.revere-site{display:inline-block;margin:3px}.revere-article-cont:last-child{border-bottom:none}.revere-image {width: auto; max-width: 40%;float: left;margin-right: 5%;}.revere-clear {clear: both !important;}</style>';
    }
    
    // Back End
    public function form($instance) {
        if(isset($instance['articles'])) {
            $articles = $instance['articles'];
        } else {
            $articles = __('3', 'revereKlicked');
        } ?>
        <p>
            <label for="<?php echo $this->get_field_id('articles'); ?>">Article Count</label>
            <input class="widefat" id="<?php echo $this->get_field_id('articles'); ?>" name="<?php echo $this->get_field_name('articles'); ?>" type="text" value="<?php echo esc_attr($articles); ?>" />
        </p>
    <?php
    }
    
    // Update widget and replace old instances with new
    public function update($new, $old) {
        $instance = array();
        $instance['articles'] = (!empty($new['articles'])) ? strip_tags($new['articles']) : '';
        return $instance;
    }
}

/**
Widget Output
**/
function klicked_api_reverereport_widget_output($articles) {
    // Update the amount of articles by one, so we can do less than.
    $articles = $articles + 1;
    
    // Let's get the data.
    $saved = json_decode(get_option('revere_articles', true), true);

    // Check if it's empty.
    if(!empty($saved) && !isset($saved['code'])) {
        // Counter
        $count = 0;

        // Store which sites have been up.
        $check = array();

        // Loop through the saved posts.
        foreach($saved as $post) {
            // Check if this site already has an article displayed.
            if(isset($post['site']) && in_array($post['site'], $check)) {
                // Let's skip this one, we already have an article from this site.
            } elseif(!empty($post['site'])) {
            // Count this post.
            $count++;
            // Add this post to the array of sites displayed.
            if(isset($post['site'])) {
                $check[] .= $post['site'];   
            }
                if($count < $articles) { ?>
                    <div class="revere-article-cont">
                        <div class="revere-image">
                            <a href="<?php echo $post['link']; ?>" target="_blank"><img src="<?php echo $post['thumb']; ?>" alt="<?php echo $post['title']; ?>" /></a>
                        </div>
                        <div class="revere-title">
                            <a href="<?php echo $post['link']; ?>" target="_blank"><?php echo $post['title']; ?></a>
                        </div>
                        <div class="revere-meta">
                            <span class="revere-date"><?php echo $post['date']; ?></span>
                            <span class="revere-site"><a href="<?php echo $post['siteurl']; ?>" target="_blank"><?php echo $post['site']; ?></a></span>
                        </div>
                        <div class="revere-clear"></div>
                    </div>
        <?php   } else {
                    // Do nothing.
                }
            } else {
                // Do nothing.
            }
        }
    } else {
        echo 'No articles were found at this time.';
    }
}