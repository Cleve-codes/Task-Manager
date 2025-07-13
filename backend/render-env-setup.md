# Render Environment Variables Setup

To fix the Swagger HTTPS mixed content issues, you need to set these environment variables in your Render dashboard:

## Required Environment Variables

Go to your Render service dashboard and add/update these environment variables:

```
APP_URL=https://task-manager-api-nwrn.onrender.com
FRONTEND_URL=https://task-manager-lac-tau.vercel.app
FORCE_HTTPS=true
L5_SWAGGER_USE_ABSOLUTE_PATH=true
APP_ENV=production
APP_DEBUG=false
SANCTUM_STATEFUL_DOMAINS=task-manager-lac-tau.vercel.app,localhost:3000
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

## Steps to Update Environment Variables on Render:

1. Go to your Render dashboard
2. Select your service (task-manager-api-nwrn)
3. Go to the "Environment" tab
4. Add or update the variables listed above
5. Click "Save Changes"
6. Render will automatically redeploy your service

## What These Variables Do:

- `APP_URL`: Sets the correct HTTPS URL for your application
- `FRONTEND_URL`: Sets the frontend URL for CORS and password reset links
- `FORCE_HTTPS`: Forces Laravel to generate HTTPS URLs
- `L5_SWAGGER_USE_ABSOLUTE_PATH`: Makes Swagger use absolute paths for assets
- `APP_ENV=production`: Sets Laravel to production mode
- `APP_DEBUG=false`: Disables debug mode for security
- `SANCTUM_STATEFUL_DOMAINS`: Configures domains for Sanctum authentication
- `SESSION_SECURE_COOKIE=true`: Ensures cookies are only sent over HTTPS
- `SESSION_SAME_SITE=none`: Allows cross-site cookie usage

## After Setting Variables:

1. Wait for the automatic redeployment to complete
2. Visit: https://task-manager-api-nwrn.onrender.com/api/documentation
3. Swagger UI should now load properly without mixed content errors

## Alternative: Manual Regeneration

If you need to manually regenerate Swagger docs, you can run:
```bash
php artisan l5-swagger:generate
```

This is now automatically included in the startup script.

## Troubleshooting

### CORS Issues
If you still get CORS errors:
1. Verify `FRONTEND_URL` matches your Vercel deployment URL exactly
2. Check that `SANCTUM_STATEFUL_DOMAINS` includes your frontend domain
3. Ensure `SESSION_SAME_SITE=none` and `SESSION_SECURE_COOKIE=true` are set

### 419 CSRF Token Errors
The API is configured for token-based authentication without CSRF tokens:
1. Ensure your frontend sends the `Authorization: Bearer <token>` header
2. Verify the token is obtained from the `/login` endpoint
3. Check that API routes don't include CSRF middleware (already configured)

### Mixed Content Errors (Swagger)
1. Verify `FORCE_HTTPS=true` is set
2. Check `L5_SWAGGER_USE_ABSOLUTE_PATH=true` is configured
3. Ensure `APP_URL` uses HTTPS
