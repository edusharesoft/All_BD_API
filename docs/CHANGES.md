# Change log (this update)

- Moved `payment-gateway-banglaqr.1.2.4/` → `payment/banglaqr/` (contents unchanged).
- README: removed the broken **ShatajPay** row (it pointed to bKash docs and contained `[SHATAJ]` placeholders); added Bangla QR row and a *Local documentation* index.
- Added docs: SSLCOMMERZ, bKash, aamarPay, shurjoPay, Steadfast, Pathao, BulkSMSBD; stubs for the rest.
- Added PHP examples (not executed – PHP wasn't available in the build environment; test in sandbox).

## Known gaps
- `payment/banglaqr/README.md` references `assets/` images that are not in the folder.
- Not downloaded (merchant-only or PDF): Nagad, Upay, PortPos, RedX, Paperfly, eCourier PDFs, pandago, SSL Wireless, Alpha, GreenWeb, MiM.
- Pathao live base URL differs between sources.

## Review of your edits (round 2)
- Kept: ShatajPay and Shataj SMS additions.
- Fixed: truncated README ending (Bangla NLP, Multi-purpose, Integration Tips, Contributing, License restored); bKash row (its links had been replaced with Shataj ones); eCourier row, pandago row and multi-courier tip that were cut off; broken `#how-to-read-this-list` link; `<description>` placeholder in the Shataj SMS row; empty `` `` `` links in `payment/shatajpay/API.md` (it was a copy of the Nagad stub, so its static-IP claim was also unverified and removed).
- Removed the duplicate `payment-gateway-banglaqr.1.2.4/` folder (identical to `payment/banglaqr/`).

## Round 3
- `payment/shatajpay/API.md` rewritten from your `API_Doc.md` (endpoint, headers, body, callback); status ⚠️ (vendor-supplied, unverified).
- Added `examples/php/shatajpay_init.php`.
- README: ShatajPay row updated; HTTPS column set to "Unverified" because the supplied doc uses `http://`.
- Flagged in the doc: inverted `sandbox` flag (0 = sandbox), sample posts to bare base URL, no verify endpoint/signature for the callback. Removed a tracking parameter (`utm_source=chatgpt.com`) from the registration link.

## Round 4
- Merged `shataj-payment-gateway-integration.zip` into `payment/shatajpay/` (`.git` excluded): vendor `readme.md`, plus `laravel/` with migrations, models, controller, route and `ControllerMethods.txt`. Files copied unmodified.
- `payment/shatajpay.md` moved to `payment/shatajpay/API.md`; added a list of problems found in the sample code.

## Round 5
- Added `payment/shatajpay/laravel-fixed/`: config, migration, model, service, controller, routes, `.env.example` and README. The vendor sample in `laravel/` is untouched. Not executed (no PHP in the build environment).
