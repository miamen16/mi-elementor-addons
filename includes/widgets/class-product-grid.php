<?php
/**
 * Product Grid widget.
 *
 * @package MI_Elementor_Addons
 */

namespace MI_Elementor_Addons\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Product_Grid extends Widget_Base {

	public function get_name() {
		return 'mi-product-grid';
	}

	public function get_title() {
		return esc_html__( 'Product Grid', 'mi-elementor-addons' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( 'mi-elementor-addons' );
	}

	public function get_style_depends() {
		return array( 'mi-elementor-addons', 'mi-elementor-addons-product-grid' );
	}

	public function get_keywords() {
		return array( 'products', 'product grid', 'woocommerce', 'shop', 'mi' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Products', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Products', 'mi-elementor-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'default' => 8,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'mi-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mi-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'  => esc_html__( 'Date', 'mi-elementor-addons' ),
					'title' => esc_html__( 'Title', 'mi-elementor-addons' ),
					'price' => esc_html__( 'Price', 'mi-elementor-addons' ),
					'rand'  => esc_html__( 'Random', 'mi-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'mi-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => esc_html__( 'Descending', 'mi-elementor-addons' ),
					'ASC'  => esc_html__( 'Ascending', 'mi-elementor-addons' ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! function_exists( 'wc_get_products' ) ) {
			echo '<div class="mi-product-card__notice">' . esc_html__( 'WooCommerce is required for this widget.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		$products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => absint( $settings['limit'] ),
				'orderby' => sanitize_key( $settings['orderby'] ),
				'order'   => 'ASC' === $settings['order'] ? 'ASC' : 'DESC',
				'return'  => 'objects',
			)
		);

		if ( empty( $products ) ) {
			echo '<div class="mi-product-card__notice">' . esc_html__( 'No products found.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		$columns = max( 1, min( 6, absint( $settings['columns'] ) ) );
		?>
		<div class="mi-product-grid" style="--mi-product-grid-columns: <?php echo esc_attr( $columns ); ?>;">
			<?php foreach ( $products as $product ) : ?>
				<article class="mi-product-grid__item">
					<?php if ( $product->get_image_id() ) : ?>
						<a class="mi-product-grid__image" href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo wp_get_attachment_image( $product->get_image_id(), 'woocommerce_thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>

					<h3 class="mi-product-grid__title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</h3>

					<div class="mi-product-grid__price">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>

					<a class="button mi-product-grid__button" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>">
						<?php echo esc_html( $product->add_to_cart_text() ); ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
