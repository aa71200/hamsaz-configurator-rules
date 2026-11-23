# Hamsaz Configurator Rules

A code sample from **Hamsaz**, CodexaWP's visual product configurator for WooCommerce. It demonstrates how a merchant-defined rule can block an incompatible bundle and how the engine responds when the catalogue lacks a required attribute.

## Try it

Run the three checked scenarios on PHP 8.2 or newer. No WordPress installation or catalogue connection is required.

```sh
php examples/check.php
```

| Configuration | Engine status | Purchase allowed |
| --- | --- | --- |
| Four cameras with a four-channel recorder | Compatible | Yes |
| Five cameras with a four-channel recorder | Incompatible | No |
| Four cameras with a recorder whose capacity is missing | Unknown | No |

The fixture also checks the returned rule code and the exact subtotal in integer cents. A subtotal is informational; purchase eligibility comes from the rules.

## Follow the code

- [Camera definition](examples/camera.json) — the original camera-pack rule compares the number of cameras with the recorder's channel count.
- [Evaluator](src/Evaluator.php) — reads the definition, selections and catalogue attributes, then returns a verdict, purchase eligibility, rule results and subtotal.
- [Executable scenarios](examples/check.php) — pass synthetic products to the same evaluator used by the source plugin and fail if any expected result changes.

## Source and license

The evaluator, unit helper, unknown-value type and camera definition are selected from **Hamsaz 0.1.0**. The adapter uses the bundled fixture to illustrate the PHP engine. See [LICENSE](LICENSE) for GPL-2.0-or-later terms.
