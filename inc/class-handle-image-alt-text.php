<?php
namespace AltTextGenerator;

/**
 * Image Alt Text generator handler activation handler.
 *
 * @package AltTextGenerator
 */
class HandleImageAltText {

	/** @var $endpoint Defines the Open AI endpoint. */
	public $endpoint;
	/** @var $api_key The Private API Key. */
	public $api_key;
	/** @var $chat_model The chat model to retrieve the data from. */
	public $chat_model;

	/**
	 * Initializes the required variables and methods.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$this->endpoint   = '';
		$this->api_key    = '';
		$this->chat_model = '';
	}
	/**
	 * Gets the alt text for the image based on the attachetment_id
	 *
	 * @param int $attachment_id The current attachtment ID.
	 * @since 0.0.1
	 * @return string attachment_id if the image was found.
	 * @throws Exception Exception if the image could not be found.
	 */
	public function assign_alt_tex_to_image( $attachment_id ): string {
		$attachment_url = wp_get_attachment_url( $attachment_id );
		if ( false === $attachment_url ) { // TODO: check if file exists as well.
			throw new \Exception( esc_html__( 'It looks like the image could not be found, please try again.', 'alt-text-gen' ) );
		}
		$alt_text = $this->get_openai_response( $attachment_url );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt_text );
		return $alt_text;
	}


	/**
	 * Process the request to retrieve the Alt text description from Open AI.
	 *
	 * @param string $image_url The current attachtment URL.
	 * @return string
	 * @since 0.0.1
	 */
	public function get_openai_response( $image_url ): string {
		$body = [
			'model'       => $this->chat_model, // Adjust the model name as needed.
			'messages'    => [
				[
					'role'    => 'user',
					'content' => [
						[
							'type' => 'text',
							'text' => 'Can you describe the best "alt" description for this image in less than 20 characters?',
						],
						[
							'type'      => 'image_url',
							'image_url' => [
								'url' => $image_url,
							],
						],
					],
				],
			],
			'n'           => 1,                  // Set to 1 to get only one response.
			'max_tokens'  => 100,       // Adjust as needed.
			'temperature' => 0.5,  // Optional: Controls randomness, adjust as needed.
		];
		// Set up the request arguments.
		$args = [
			'body'    => wp_json_encode( $body ),
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $this->api_key,
			],
			'method'  => 'POST',
			'timeout' => 30,
		];
		$response = wp_remote_post( $this->endpoint, $args );

		// Check for errors.
		if ( is_wp_error( $response ) ) {
			// translators: %s for the error response.
			return sprintf( esc_html__( 'Error: %s', 'alt-text-gen' ), $response->get_error_message() );
		}

		// code the response body.
		$response_body = wp_remote_retrieve_body( $response );
		$data          = json_decode( $response_body, true );

		// Check if the response is valid and contains the message content.
		if ( ! empty( $data['choices'][0]['message']['content'] ) ) {
			return $data['choices'][0]['message']['content'];
		} else {
			// translators: %s for the data response.
			return sprintf( esc_html__( 'Error: Invalid response from API: %s', 'alt-text-gen' ), $data );
		}
	}
}
