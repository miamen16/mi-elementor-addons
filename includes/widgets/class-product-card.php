<?php
/**
 * Product Card widget.
 *
 * @package MI_Elementor_Addons
 */

namespace MI_Elementor_Addons\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Product_Card extends Widget_Base {

	public function get_name() {
		return 'mi-product-card';
	}

	public function get_title() {
		return esc_html__( 'Product Card', 'mi-elementor-addons' );
	}

	public function get_icon() {
		return 'eicon-product-images';
	}

	public function get_categories() {
		return array( 'mi-elementor-addons' );
	}

	public function get_keywords() {
		return array( 'product', 'woocommerce', 'shop', 'card', 'mi' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Product', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => esc_html__( 'Product ID', 'mi-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'description' => esc_html__( 'Enter a WooCommerce product ID.', 'mi-elementor-addons' ),
			)
		);

		foreach ( array( 'image' => 'Show Image', 'price' => 'Show Price', 'button' => 'Show Button' ) as $key => $label ) {
			$this->add_control(
				'show_' . $key,
				array(
					'label'        => esc_html__( $label, 'mi-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'mi-elementor-addons' ),
					'label_off'    => esc_html__( 'No', 'mi-elementor-addons' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mi-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mi-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mi-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mi-product-card__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mi-product-card__title a',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$product_id = absint( $settings['product_id'] ?? 0 );

		if ( ! function_exists( 'wc_get_product' ) ) {
			echo '<div class="mi-product-card__notice">' . esc_html__( 'WooCommerce is required for this widget.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		if ( ! $product_id ) {
			echo '<div class="mi-product-card__notice">' . esc_html__( 'Enter a product ID to display the Product Card.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			echo '<div class="mi-product-card__notice">' . esc_html__( 'The selected product could not be found.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		wp_enqueue_style( 'mi-elementor-addons' );

		$product_url = $product->get_permalink();
		$title       = $product->get_name();
		$image_id    = $product->get_image_id();
		?>
		<article class="mi-product-card">
			<?php if ( 'yes' === $settings['show_image'] && $image_id ) : ?>
				<div class="mi-product-card__image">
					<a href="<?php echo esc_url( $product_url ); ?>">
						<?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="mi-product-card__content">
				<h3 class="mi-product-card__title">
					<a href="<?php echo esc_url( $product_url ); ?>">
						<?php echo esc_html( $title ); ?>
					</a>
				</h3>

				<?php if ( 'yes' === $settings['show_price'] ) : ?>
					<div class="mi-product-card__price">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_button'] ) : ?>
					<div class="mi-product-card__button">
						<a class="button" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>">
							<?php echo esc_html( $product->add_to_cart_text() ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}
}
