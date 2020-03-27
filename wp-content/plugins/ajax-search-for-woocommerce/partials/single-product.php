<?php
$rewCount = $product->getReviewCount();
?>
<div class="dgwt-wcas-details-inner">
	<div class="dgwt-wcas-product-details">

		<a class="dgwt-wcas-pd-details" href="<?php echo $product->getPermalink(); ?>" title="<?php echo esc_attr( $product->getName() ); ?>">

			<div class="dgwt-wcas-pd-image">
				<?php echo $product->getThumbnail(); ?>
			</div>

			<div class="dgwt-wcas-pd-rest">

				<span class="product-title"><?php echo $product->getName(); ?></span>

				<?php if ( $rewCount > 0 ): ?>

					<div class="dgwt-wcas-pd-rating">
						<?php echo $product->getRatingHtml() . ' <span class="dgwt-wcas-pd-review">(' . $rewCount . ')</span>'; ?>
					</div>

				<?php endif; ?>

				<div class="dgwt-wcas-pd-price">
					<?php echo $product->getPriceHTML(); ?>
				</div>
			</div>

		</a>

		<?php if ( !empty( $details[ 'desc' ] ) ): ?>
			<div class="dgwt-wcas-pd-desc">
				<?php echo wp_kses_post( $details[ 'desc' ] ); ?>
			</div>
		<?php endif; ?>

		<div class="dgwt-wcas-pd-addtc js-dgwt-wcas-pd-addtc">
            <form class="dgwt-wcas-pd-addtc-form" action="" method="post" enctype="multipart/form-data">
			<?php
            $wooRaw = $product->getWooObject();
            $uid = uniqid();
            if (
                    $wooRaw && $wooRaw->is_type( 'simple' )
                 && $wooRaw->is_purchasable()
                 && $wooRaw->is_in_stock()
                 && ! $wooRaw->is_sold_individually()
                && apply_filters('dgwt/wcas/suggestion_details/show_quantity', true)
            ) {
                woocommerce_quantity_input( array(
                    'input_name'  => 'js-dgwt-wcas-quantity',
                ), $wooRaw, true );
            }

            echo WC_Shortcodes::product_add_to_cart( array(
				'id'		 => $product->getID(),
				'show_price' => false,
				'style'		 => '',
			) );
			?>
            </form>
		</div>


	</div>
</div>

