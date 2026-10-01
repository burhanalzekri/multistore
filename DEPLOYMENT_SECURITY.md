# Deployment and Security Notes

## Required before production

1. Run Composer and frontend installation/build in CI.
2. Run migrations against PostgreSQL, not only SQLite.
3. Configure persistent object storage for uploaded media.
4. Configure a real backup destination and test restore.
5. Keep all demo and test routes disabled in production.
6. Configure rate limits for login, checkout, tracking, coupons, reviews and webhooks.
7. Set `APP_DEBUG=false` and rotate all credentials.
8. Run the multi-tenant feature tests before deployment.

## Cart policy

The hardened build uses a single-shop cart. A cart cannot mix products from different shops. This avoids cross-tenant orders while keeping checkout simple. A future multi-vendor cart should split one checkout into one order per shop with independent shipping and payment state.

## Validation limitation

The uploaded source did not include `vendor` or `node_modules`, and the current sandbox lacks PHP/Composer. The code must be validated in CI or Docker with PHP 8.3+, Composer, PostgreSQL and Node before deployment.
