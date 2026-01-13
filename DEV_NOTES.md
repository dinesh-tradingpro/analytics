Changes made:

- Updated dashboard to display USD explicitly and use USD fields.
- Updated `TransactionDetail` model to include USD fields earlier.
- Added migration to add currency/user fields to `transaction_details`.
- Added Artisan command `currency:update-rates` at `app/Console/Commands/UpdateCurrencyRates.php` to fetch rates from exchangerate.host and write `config/currency_rates.php`.

Notes:
- The `currency:update-rates` command failed to fetch rates in this environment (unexpected response). You can run it locally; it hits `https://api.exchangerate.host/latest`.
- Config cache was cleared. Update `config/currency_rates.php` with authoritative rates if needed.

Commands:

php artisan currency:update-rates
php artisan config:clear

