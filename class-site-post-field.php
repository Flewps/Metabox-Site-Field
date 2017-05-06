<?php
/**
 * The post field which allows users to select existing posts.
 *
 * @package Meta Box
 */

/**
 * Post field class.
 */
class RWMB_Site_Post_Field extends RWMB_Post_Field {
	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function normalize( $field ) {
		// Set default field args.
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, array(
			'site'			=> get_current_blog_id(),
		) );

		if ( ! isset( $field['site'] ) && is_numeric( $field['site'] ) ) {
			$field['site'] = intval( $field['site'] );
		}

		return $field;
	}

	/**
	 * Get meta value.
	 * If field is cloneable, value is saved as a single entry in DB.
	 * Otherwise value is saved as multiple entries (for backward compatibility).
	 *
	 * @see "save" method for better understanding
	 *
	 * @param int   $post_id Post ID.
	 * @param bool  $saved   Is the meta box saved.
	 * @param array $field   Field parameters.
	 *
	 * @return mixed
	 */
	public static function meta( $post_id, $saved, $field ) {

		switch_to_blog(intval($field['site']));

		if ( isset( $field['parent'] ) && $field['parent'] ) {
			$post = get_post( $post_id );
			restore_current_blog();
			return $post->post_parent;
		}

		$result = parent::meta( $post_id, $saved, $field );

		restore_current_blog();

		return $result;
	}

	/**
	 * Get options for walker.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function get_options( $field ) {
		switch_to_blog(intval($field['site']));

		$query = new WP_Query( $field['query_args'] );
		$result = $query->have_posts() ? $query->posts : array();

		restore_current_blog();

		return $result;
	}

}
