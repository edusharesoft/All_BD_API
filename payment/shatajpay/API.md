# ShatajPay (Shataj Soft)

- Website: http://pay.shataj.com/ · Merchant registration: https://pay.shataj.com/register
- Company GitHub: https://github.com/shatajsoft · Integration repo: https://github.com/mspmedisoft/shataj-payment-gateway-integration
- **Doc status:** ⚠️ based on the vendor's `readme.md` and reference Laravel code in this folder (not verified against a live account)

## Base URL
`http://pay.shataj.com` — the supplied doc uses plain **http**. Check whether `https://pay.shataj.com` works for your account and prefer it, since credentials travel in headers.

## Credentials (from the merchant panel after registration)
App username, app password, app key, app secret, gateway ID.

## Initialize payment

`POST {base}/api/v1/user/payment-system`

**Headers** (all required): `username`, `password`, `appkey`, `appsecret`, `gatewayid`, `sandbox` (integer).
> ⚠️ In the vendor's Laravel sample, `sandbox` is `0` for sandbox mode and `1` for live (`mode === 'sandbox' ? 0 : 1`). That is the opposite of what most people expect, so confirm with Shataj before going live.

**Body (JSON)**

| Field | Required | Notes |
|---|---|---|
| `amount` | Yes | number |
| `currency` | Yes | e.g. `BDT` |
| `name`, `email`, `mobile` | Yes | customer |
| `Address` | No | note the capital A |
| `opt_value_a`, `opt_value_b` | No | free-form values |
| `transactionId` | Yes | unique per payment (UUID recommended) |
| `orderId` | Yes | unique per order (UUID recommended) |

**Success:** `{ "status": true, "status_code": 200, "message": "Payment initialized successfully", "redirect_url": "..." }` – redirect the customer to `redirect_url`.
**Error:** `{ "status": false, "status_code": 400, "message": "Payment request failed" }`

> The vendor's Laravel sample posts to `$apiUrl` (the bare base URL). Use the full path above unless Shataj tells you otherwise.

## Callback
After payment, the customer is redirected (GET) to your configured callback URL with:

| Param | Meaning |
|---|---|
| `status` | `Complete` or `Failed` |
| `orderId` | your original order ID |

Map `Complete` → `completed`, `Failed` → `failed`. Local status lifecycle: `initialize` → `pending` → `completed` / `failed`.

## Security notes
- Keep all credentials on the backend; never ship `appsecret`/password to a frontend.
- Never mark an order paid just because initialization succeeded.
- ⚠️ The documented callback is an unsigned GET carrying only `status` and `orderId`, and no verify/query endpoint is documented. Anyone could forge that URL. Ask Shataj for a server-side verification endpoint or a signature, and until then confirm the payment in the ShatajPay panel before shipping high-value orders.

## Reference Laravel implementation (`laravel/`)

Files copied unchanged from `mspmedisoft/shataj-payment-gateway-integration` (one commit, "first commit"; the `.git` folder was not included). `readme.md` in this folder is the vendor's API doc.

| File | Purpose |
|---|---|
| `2025_11_25_114617_create_paymentgetways_table.php` | stores gateway credentials in the DB (can use `.env`/config instead) |
| `2026_08_18_150448_create_transactions_table.php` | local transactions table |
| `paymentgetway.php`, `Transaction.php` | Eloquent models |
| `ControllerMethods.txt` | submit / create-payment methods to paste into your controller or Livewire component |
| `GatewayController.php`, `web.php` | callback handler and route |

### Things to fix before using this code
1. **Sandbox is not supported by the sample.** `createPayment()` only sets `$apiUrl` when `getway_mode == 'production'`; otherwise it returns "Your Api Must be Live". The `sandbox` header mapping below that check is therefore never exercised for sandbox mode.
2. **`GatewayController.php` does not parse.** The placeholders `return redirect() #that you want;` put the semicolon inside a PHP `#` comment. Replace each with a real redirect, e.g. `return redirect()->route('your.page');`.
3. **The redirect to ShatajPay is lost.** `createPayment()` returns `redirect()->to($redirectUrl)`, but `paymentGatewaySubmit()` and `submit()` discard that return value, so the customer is never sent to the gateway. Return it all the way up (in Livewire use `return $this->redirect($redirectUrl)`).
4. **Model and migration don't match.** The controller code sets `event_name` and `event_data`, but the migration has no such columns, `Transaction::$fillable` omits them, and `Transaction::participant()` points to a `SeminarParticipant` model from the original project. Remove or add them as needed.
5. **Status flow differs from the doc.** The sample saves new transactions as `pending` (the migration default is `initialize`), and the callback only accepts `pending`.
6. **`getway_status` is a string column** (`default(0)`) but is cast to boolean in the model.
7. **Credentials are stored in plain text** in `paymentgetways`. Encrypt them (Laravel `encrypted` cast) or use `.env`.
8. The callback has no signature or server-side verification (see Security notes above).
9. Uses `http://pay.shataj.com`; try `https` first.

## Corrected implementation
A cleaned-up, ready-to-copy Laravel version that fixes the problems above is in [`laravel-fixed/`](laravel-fixed/README.md). It has not been run against a live ShatajPay account.
