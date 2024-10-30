<?php

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'ISV_Widget' ) ) {

	class ISV_Widget extends WP_Widget {

		public function __construct() {

			parent::__construct(
				'isv_widget',
				__( 'ISV Feedwidget', 'isv-connect' ),
				array( 'description' => __( 'Show a feed of planningitems from the ISV system', 'isv-connect' ), )
			);
		}

		public function widget( $args, $instance ) {

			$feedcode = $instance['feedcode'];
			$content  = $instance['content'];
			$filters  = empty( $instance['filters'] ) ? false : true;

			//if a feedcode was entered, go fetch
			if ( isset( $feedcode ) && ( ! empty( $feedcode ) ) ) {

				echo $args['before_widget'];

				$isv_feed = new ISV_Feedcontainer( $feedcode, $filters, $content );
				echo $isv_feed->get_HTML();

				echo $args['after_widget'];
			}
		}

		public function form( $instance ) {

			$title    = isset( $instance['title'] ) ? $instance['title'] : '';
			$feedcode = isset( $instance['feedcode'] ) ? $instance['feedcode'] : '';
			$filters  = isset( $instance['filters'] ) ? $instance['filters'] : '';
			$content  = isset( $instance['content'] ) ? $instance['content'] : '';

			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',
						'isv-connect' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'feedcode' ); ?>"><?php _e( 'Feedcode:',
						'isv-connect' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'feedcode' ); ?>"
                       name="<?php echo $this->get_field_name( 'feedcode' ); ?>" type="text"
                       value="<?php echo esc_attr( $feedcode ); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'filters' ); ?>"><?php _e( 'Show filters?',
						'isv-connect' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'filters' ); ?>"
                       name="<?php echo $this->get_field_name( 'filters' ); ?>" type="checkbox" <?php checked( 'on',
					$filters ); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content when there are no results:',
						'isv-connect' ); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>"
                          name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo $content; ?></textarea>
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance             = array();
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['feedcode'] = ( ! empty( $new_instance['feedcode'] ) ) ? sanitize_text_field( $new_instance['feedcode'] ) : '';
			$instance['filters']  = ( ! empty( $new_instance['filters'] ) ) ? sanitize_text_field( $new_instance['filters'] ) : '';
			$instance['content']  = ( ! empty( $new_instance['content'] ) ) ? sanitize_text_field( $new_instance['content'] ) : '';

			return $instance;
		}
	}
}