<?php
declare(strict_types=1);

use Hamsaz\Core\Evaluator;

require __DIR__ . '/../src/Unknown.php';
require __DIR__ . '/../src/Units.php';
require __DIR__ . '/../src/Evaluator.php';

$definition = json_decode(
	file_get_contents( __DIR__ . '/camera.json' ),
	true,
	512,
	JSON_THROW_ON_ERROR
);

$catalog = [
	101 => [ 'priceMinor' => 4500, 'attributes' => [ 'protocol' => 'IP' ] ],
	201 => [ 'priceMinor' => 12000, 'attributes' => [ 'protocol' => 'IP', 'channels' => 4 ] ],
];

$cases = [
	'Four cameras / four channels' => [ 4, true, 'compatible', true, 'RULE_PASSED', 30000 ],
	'Five cameras / four channels' => [ 5, true, 'incompatible', false, 'CHANNEL_CAPACITY', 34500 ],
	'Four cameras / missing capacity' => [ 4, false, 'unknown', false, 'REQUIRED_ATTRIBUTE_MISSING', 30000 ],
];
$evaluator = new Evaluator();

foreach ( $cases as $label => [ $count, $has_capacity, $status, $can_purchase, $code, $subtotal ] ) {
	$fixture = $catalog;
	if ( ! $has_capacity ) {
		unset( $fixture[201]['attributes']['channels'] );
	}
	$result = $evaluator->evaluate(
		$definition,
		[
			'bundleQuantity' => 1,
			'selections' => [
				'camera'   => [ [ 'productId' => 101, 'quantity' => $count ] ],
				'recorder' => [ [ 'productId' => 201, 'quantity' => 1 ] ],
			],
		],
		$fixture
	);
	if (
		$status !== $result['status'] ||
		$can_purchase !== $result['canPurchase'] ||
		$subtotal !== $result['subtotalMinor'] ||
		! in_array( $code, array_column( $result['results'], 'code' ), true )
	) {
		throw new RuntimeException( 'Unexpected evaluator result for ' . $label . ': ' . json_encode( $result ) );
	}
	printf( "%-35s %-13s %s (subtotal: $%.2f)\n", $label, $status, $code, $subtotal / 100 );
}
