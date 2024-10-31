<?php
/*
Plugin Name: Quotes and Sayings | QuoteTour
Plugin URI: https://www.quotetour.com/wordpress-plugin
Description: Show a random quote on your site's sidebar. To install, click activate and then go to Appearance > Widgets and look for the 'Quotes and Sayings | QuoteTour'. Next, drag the widget to your sidebar. You can change the title and category and choose if you want to display the quote author.
Version: 1.1
Author: QuoteTour
Author URI: https://www.quotetour.com
*/

/**
 * Adds QuoteTour_Widget widget.
 */
class QuoteTour_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'foo_widget', // Base ID
			__('QuoteTour Widget', 'text_domain'), // Name
			array( 'description' => __( 'Display a random quote from a category by your choise in your blog.  Choose between 20 popular categories like: friendship, funny, love, inspirational..!', 'text_domain' ), ) // Args
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
	public function widget( $args, $instance ) {
		$qtype = apply_filters( 'widget_title', $instance['qtype'] );
		$qauthor = $instance[ 'qauthor' ] ? 'true' : 'false';
		$qtitle = empty($instance['qtitle']) ? ' ' : apply_filters('widget_title', $instance['qtitle']);

        $title_hash = array(
             "random" => "Quotes and Sayings",
             "art" => "Art | Quotes and Sayings",
             "beauty" => "Beauty | Quotes and Sayings",
             "friendship" => "Friendship | Quotes and Sayings",
             "future" => "Future | Quotes and Sayings",
             "happiness" => "Happiness | Quotes and Sayings",
             "hope" => "Hope |Quotes and Sayings",
             "inspirational" => "Inspirational | Quotes and Sayings",
             "leadership" => "Leadership | Quotes and Sayings",
             "life" => "Life | Quotes and Sayings",
             "love" => "Love | Quotes and Sayings",
             "morning" => "Morning | Quotes and Sayings",
             "motivational" => "Motivational | Quotes and Sayings",
             "parenting" => "Parenting | Quotes and Sayings",
             "positive" => "Positive | Quotes and Sayings",
             "romantic" => "Romantic | Quotes and Sayings",
             "success" => "Success | Quotes and Sayings",
             "time" => "Time | Quotes and Sayings",
             "travel" => "Travel | Quotes and Sayings",
             "trust" => "Trust | Quotes and Sayings",
             "truth" => "Truth | Quotes and Sayings"
         );

        $arguments = [
        	"dataType" => "json",
        ];
        if ($qtype == 'random') {
        	$category = '';
        } else {
        	$category = $qtype;
        }
        $api_url = 'https://www.quotetour.com/api/quote/'.$category;

    	$response = wp_remote_get( $api_url, $arguments);
    	if( is_array($response) ) {
		  $header = $response['headers']; // array of http header lines
		  $body = json_decode($response['body']); // use the content
		}
        
		echo $args['before_widget'];
		if ( strlen($qtitle) > 1 ){
			echo $args['before_title'] . $qtitle. $args['after_title'];
		} else if ( ! empty( $qtype) ){
			echo $args['before_title'] . $title_hash[$qtype]. $args['after_title'];
		}
		echo '<p class="quote-body">'.$body->quote.'</p>';
		if ($qauthor == 'true') {
			echo '<p class="quote-author" style="float:left;">-'.$body->author.'</p>';
		}
		
		if ( ! empty( $qtype) ) {
			echo __( '<small style="float:right;"><i><a href="https://www.quotetour.com/category/'.$qtype.'" target="_blank">more Quotes</a></i></small>', 'text_domain' );
		}
		else {
			echo __( '<small style="float:right;"><i><a href="https://www.quotetour.com/" target="_blank">more Quotes</a></i></small>', 'text_domain' );
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'qtype' ] ) ) {
			$qtype = $instance[ 'qtype' ];
		}
		else {
			$qtype = "qotd";
		}
		if ( isset( $instance[ 'qauthor' ] ) ) {
			$qauthor = $instance[ 'qauthor' ];
		}
		else {
			$qauthor = "true";
		}
		if ( isset( $instance[ 'qtitle' ] ) ) {
			$qtitle = $instance[ 'qtitle' ];
		}
		else {
			$qtitle = "";
		}
		?>
		<p>
		    <label for="<?php echo $this->get_field_id('qtitle'); ?>">Title: 
		    <input class="widefat" id="<?php echo $this->get_field_id('qtitle'); ?>" 
		             name="<?php echo $this->get_field_name('qtitle'); ?>" type="text" 
		             value="<?php echo attribute_escape($qtitle);?>" placeholder="Default title"/>
		    </label>
		</p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'qtype' ); ?>">Category</label> 
		<select id="<?php echo $this->get_field_id( 'qtype' ); ?>" name="<?php echo $this->get_field_name( 'qtype' ); ?>" class="widefat" style="width:100%;">
			<option value="random" <?php if('random'==$qtype) echo 'selected="selected"';?>>Random</option>
			<option value="art" <?php if('art'==$qtype) echo 'selected="selected"';?>>Art</option>
			<option value="beauty" <?php if('beauty'==$qtype) echo 'selected="selected"';?>>Beauty</option>
            <option value="friendship" <?php if('friendship'==$qtype) echo 'selected="selected"';?>>Friendship</option>
            <option value="future" <?php if('future'==$qtype) echo 'selected="selected"';?>>Future</option>
            <option value="happiness" <?php if('happiness'==$qtype) echo 'selected="selected"';?>>Happiness</option>
            <option value="hope" <?php if('hope'==$qtype) echo 'selected="selected"';?>>Hope</option>
            <option value="inspirational" <?php if('inspirational'==$qtype) echo 'selected="selected"';?>>Inspirational</option>
            <option value="leadership" <?php if('leadership'==$qtype) echo 'selected="selected"';?>>Leadership</option>
            <option value="life" <?php if('life'==$qtype) echo 'selected="selected"';?>>Life</option>
            <option value="love" <?php if('love'==$qtype) echo 'selected="selected"';?>>Love</option>
            <option value="morning" <?php if('morning'==$qtype) echo 'selected="selected"';?>>Morning</option>
            <option value="motivational" <?php if('motivational'==$qtype) echo 'selected="selected"';?>>Motivational</option>
            <option value="parenting" <?php if('parenting'==$qtype) echo 'selected="selected"';?>>Parenting</option>
            <option value="positive" <?php if('positive'==$qtype) echo 'selected="selected"';?>>Positive</option>
            <option value="romantic" <?php if('romantic'==$qtype) echo 'selected="selected"';?>>Romantic</option>
            <option value="success" <?php if('success'==$qtype) echo 'selected="selected"';?>>Success</option>
            <option value="time" <?php if('time'==$qtype) echo 'selected="selected"';?>>Time</option>
            <option value="travel" <?php if('travel'==$qtype) echo 'selected="selected"';?>>Travel</option>
            <option value="trust" <?php if('trust'==$qtype) echo 'selected="selected"';?>>Trust</option>
            <option value="truth" <?php if('truth'==$qtype) echo 'selected="selected"';?>>Truth</option>
		</select>
		</p>
		<p>
		    <input class="checkbox" type="checkbox" <?php checked( $instance[ 'qauthor' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'qauthor' ); ?>" name="<?php echo $this->get_field_name( 'qauthor' ); ?>" /> 
		    <label for="<?php echo $this->get_field_id( 'qauthor' ); ?>">Show author</label>
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['qtype'] = ( ! empty( $new_instance['qtype'] ) ) ? strip_tags( $new_instance['qtype'] ) : '';
		$instance['qauthor'] = $new_instance[ 'qauthor' ];
		$instance['qtitle'] = $new_instance['qtitle'];
		return $instance;
	}

} // class QuoteTour_Widget

// register QuoteTour_Widget widget
function register_foo_widget() {
    register_widget( 'QuoteTour_Widget' );
}
add_action( 'widgets_init', 'register_foo_widget' );
?>
