# ShatajPay – corrected Laravel integration

A cleaned-up version of the vendor sample in `../laravel/` (which is kept untouched).
**Not executed**: no PHP/Laravel was available when this was written, so run it in a sandbox project first. Requires Laravel 10+ / PHP 8.1+.

## Install
1. Copy `config/shatajpay.php`, `app/Models/Transaction.php`, `app/Services/ShatajPayService.php`, `app/Http/Controllers/ShatajPayController.php` and the migration into your project (merge with your own `Transaction` model if you have one).
2. Add the routes from `routes/web.php.snippet`.
3. Copy `.env.example` values into `.env` and fill in your ShatajPay credentials.
4. `php artisan migrate`
5. Set the callback URL in your ShatajPay panel to `https://your-domain/gateway/payment/callback`.
6. POST `name`, `mobile` (and optional `email`, `address`) to `route('shatajpay.checkout')`.

## What changed from the vendor sample
| Problem in the sample | Fix |
|---|---|
| Sandbox mode returned "Your Api Must be Live" | One code path; `SHATAJPAY_MODE` only changes the `sandbox` header |
| `return redirect() #that you want;` (syntax error) | Real redirects via config (`SUCCESS_URL`, `FAILED_URL`) |
| Redirect to the gateway was discarded | `initialize()` returns the URL and the controller does `redirect()->away()` |
| Model/migration mismatch (`event_data`, `SeminarParticipant`) | Clean model; free-form `meta` JSON column instead |
| `pending` set before calling the gateway; docs say `initialize` | `initialize` → `pending` only after the gateway accepts → final state from callback |
| Credentials in a plain-text DB table | Read from `.env` / config |
| Race/duplicate callbacks | `lockForUpdate()` inside a DB transaction; only `pending` can change |
| Failure paths silently swallowed | Logged with `Log::warning/error` |
| Amount from client in typical use | Marked TODO: compute server-side |
| `getway_status` string vs boolean cast | Table removed |

## Still your responsibility
- **The callback is unsigned** and no verification API is documented, so anyone can open `/gateway/payment/callback?status=Complete&orderId=...` for an order ID they know. Order IDs are random UUIDs (hard to guess), but they pass through the customer's browser. Ask Shataj for a verify endpoint or signature. Until then you can set `SHATAJPAY_TRUST_CALLBACK=false`, which stores `awaiting_verification` so you can confirm in the ShatajPay panel before fulfilling.
- Confirm with Shataj: the `sandbox` flag meaning (0 = sandbox in their sample), whether `https` works, and whether a real sandbox exists for your account.
