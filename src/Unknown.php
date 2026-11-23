<?php
declare(strict_types=1);

namespace Hamsaz\Core;

final class Unknown {
	public function __construct( public readonly string $reason = 'MISSING_DATA' ) {}
}
