<?php
/**
 * Product Grid widget.
 *
 * @package MI_Elementor_Addons
 */

namespace MI_Elementor_Addons\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
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

	public function get_keywords() {
		return array( 'products', 'product grid', 'woocommerce', 'shop', 'catalog', 'mi' );
	}

	public function get_style_depends() {
		return array( 'mi-elementor-addons-product-grid' );
	}

	protected function register_controls() {
		$this->register_query_controls();
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_style_controls();
	}

	private function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query', 'mi-elementor-addons' ),
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
			'category',
			array(
				'label'       => esc_html__( 'Categories', 'mi-elementor-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_product_categories(),
				'description' => esc_html__( 'Leave empty to show products from all categories.', 'mi-elementor-addons' ),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'       => esc_html__( 'Tags', 'mi-elementor-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_product_tags(),
				'description' => esc_html__( 'Leave empty to show products with any tag.', 'mi-elementor-addons' ),
			)
		);

		$this->add_control(
			'featured',
			array(
				'label'        => esc_html__( 'Featured Only', 'mi-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mi-elementor-addons' ),
				'label_off'    => esc_html__( 'No', 'mi-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'on_sale',
			array(
				'label'        => esc_html__( 'On Sale Only', 'mi-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mi-elementor-addons' ),
				'label_off'    => esc_html__( 'No', 'mi-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'stock_status',
			array(
				'label'   => esc_html__( 'Stock Status', 'mi-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'any',
				'options' => array(
					'any'         => esc_html__( 'Any', 'mi-elementor-addons' ),
					'instock'     => esc_html__( 'In Stock', 'mi-elementor-addons' ),
					'outofstock'  => esc_html__( 'Out of Stock', 'mi-elementor-addons' ),
					'onbackorder' => esc_html__( 'On Backorder', 'mi-elementor-addons' ),
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
					'date'     => esc_html__( 'Date', 'mi-elementor-addons' ),
					'title'    => esc_html__( 'Title', 'mi-elementor-addons' ),
					'price'    => esc_html__( 'Price', 'mi-elementor-addons' ),
					'popularity' => esc_html__( 'Popularity', 'mi-elementor-addons' ),
					'rating'   => esc_html__( 'Rating', 'mi-elementor-addons' ),
					'rand'     => esc_html__( 'Random', 'mi-elementor-addons' ),
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

	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach (
			array(
				'show_image'  => esc_html__( 'Show Image', 'mi-elementor-addons' ),
				'show_badge'  => esc_html__( 'Show Sale Badge', 'mi-elementor-addons' ),
				'show_rating' => esc_html__( 'Show Rating', 'mi-elementor-addons' ),
				'show_price'  => esc_html__( 'Show Price', 'mi-elementor-addons' ),
				'show_button' => esc_html__( 'Show Button', 'mi-elementor-addons' ),
			) as $control_id => $label
		) {
			$this->add_control(
				$control_id,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'mi-elementor-addons' ),
					'label_off'    => esc_html__( 'No', 'mi-elementor-addons' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->end_controls_section();
	}

	private function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'mi-elementor-addons' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mi-product-grid' => '--mi-product-grid-columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'mi-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mi-product-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => esc_html__( 'Card', 'mi-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mi-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mi-product-grid__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mi-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mi-product-grid__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .mi-product-grid__item',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mi-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mi-product-grid__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mi-product-grid__title a',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'mi-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mi-product-grid__price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Sale Badge Color', 'mi-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mi-product-grid__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function get_product_categories() {
		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	private function get_product_tags() {
		if ( ! taxonomy_exists( 'product_tag' ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_tag',
				'hide_empty' => true,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! function_exists( 'wc_get_products' ) ) {
			echo '<div class="mi-product-grid__notice">' . esc_html__( 'WooCommerce is required for this widget.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		$allowed_orderby = array( 'date', 'title', 'price', 'popularity', 'rating', 'rand' );
		$orderby        = in_array( $settings['orderby'], $allowed_orderby, true ) ? $settings['orderby'] : 'date';
		$args            = array(
			'status'  => 'publish',
			'limit'   => max( 1, min( 100, absint( $settings['limit'] ) ) ),
			'orderby' => $orderby,
			'order'   => 'ASC' === $settings['order'] ? 'ASC' : 'DESC',
			'return'  => 'objects',
		);

		$categories = array_filter( array_map( 'sanitize_title', (array) $settings['category'] ) );
		$tags       = array_filter( array_map( 'sanitize_title', (array) $settings['tag'] ) );

		if ( $categories ) {
			$args['category'] = $categories;
		}

		if ( $tags ) {
			$args['tag'] = $tags;
		}

		if ( 'yes' === $settings['featured'] ) {
			$args['featured'] = true;
		}

		if ( 'yes' === $settings['on_sale'] ) {
			$args['on_sale'] = true;
		}

		if ( 'any' !== $settings['stock_status'] ) {
			$args['stock_status'] = sanitize_key( $settings['stock_status'] );
		}

		$products = wc_get_products( $args );

		if ( empty( $products ) ) {
			echo '<div class="mi-product-grid__notice">' . esc_html__( 'No products found for the selected filters.', 'mi-elementor-addons' ) . '</div>';
			return;
		}

		?>
		<div class="mi-product-grid">
			<?php foreach ( $products as $product ) : ?>
				<article class="mi-product-grid__item">
					<?php if ( 'yes' === $settings['show_image'] && $product->get_image_id() ) : ?>
						<a class="mi-product-grid__image" href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo wp_get_attachment_image( $product->get_image_id(), 'woocommerce_thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_badge'] && $product->is_on_sale() ) : ?>
						<span class="mi-product-grid__badge"><?php echo esc_html__( 'Sale', 'mi-elementor-addons' ); ?></span>
					<?php endif; ?>

					<h3 class="mi-product-grid__title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</h3>

					<?php if ( 'yes' === $settings['show_rating'] ) : ?>
						<div class="mi-product-grid__rating" aria-label="<?php echo esc_attr( sprintf( esc_html__( 'Rated %s out of 5', 'mi-elementor-addons' ), $product->get_average_rating() ) ); ?>">
							<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_price'] ) : ?>
						<div class="mi-product-grid__price">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_button'] ) : ?>
						<a class="button mi-product-grid__button" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>">
							<?php echo esc_html( $product->add_to_cart_text() ); ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
