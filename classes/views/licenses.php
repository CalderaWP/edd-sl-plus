<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 1/10/17
 * Time: 10:22 PM
 */

namespace calderawp\eddslplus\views;

use calderawp\eddslplus\links;

class licenses {

	public function add_hooks(){
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}


	public function enqueue_scripts(){
		wp_enqueue_script( 'vuejs', '//cdn.jsdelivr.net/vue/2.1.8/vue.min.js' );
		wp_enqueue_script( 'cwp-edd-sl-plus', plugin_dir_url( __FILE__ ) . '/assets/cwp-edd-sl-plus.js', ['jquery', 'vuejs' ], '0.0.1' );
		wp_localize_script( 'cwp-edd-sl-plus', 'CWPEDDSLPLUS', [
			'route' => esc_url_raw( add_query_arg( [
				'_wp_nonce' =>  wp_create_nonce( 'wp_rest' ),
				'return' => 'full',
				], rest_url( 'edd-sl-api/v1/licenses/user/' . get_current_user_id()  ) ) ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'download_link' => links::download_alt( '' ),
			'strings' => [
				'not_logged_in' => esc_html__( 'Please login to continue', 'cwp-edd-sl-plus' ),
				'error' => esc_html__( 'Error', 'cwp-edd-sl-plus' ),
				'nothing' => esc_html__( 'No Downloads Found', 'cwp-edd-sl-plus' ),
			]
		]);
	}

	public function content(){
		return '<style type="text/css">' . $this->style() . '</style><div id="cwp-edd-sl-plus"></div>' . $this->template();
	}

	protected function style(){
		return 'button.btn.btn-default {
        background: #a3bf61;
    color: #ffffff !important;
    border-color: #ff7e30;;

    height: 3.5em;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    white-space: nowrap;
    letter-spacing: 0.35em;
    font-size: 17px;
}

button.btn.btn-default: hover{
    background: #ff7e30;
}';
	}

	protected function template(){
		ob_start();
		?>
		<script type="text/html" id="cwp-edd-sl-plus-tmpl">
			<div class="row">
				<div v-for="download in downloads">

					<div class="col-sm-12 col-md-6 well ">
						<h4>{{download.title}}</h4>
						<button type="button" class="btn btn-default" v-on:click.prevent="downloadButton(download.code)" >
							<?php esc_html_e( 'Download', 'cwp-edd-sl-plus' ); ?>
						</button>
						<ul>
							<li>
								<?php esc_html_e( 'License Code', 'cwp-edd-sl-plus' ); ?>

							</li>
						</ul>
						<pre>{{download.code}}</pre>


					</div>

				</div>
			</div>
		</script>
<?php

		return ob_get_clean();
	}
}