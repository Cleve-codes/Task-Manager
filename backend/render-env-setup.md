# Render Environment Variables Setup

To fix the Swagger HTTPS mixed content issues, you need to set these environment variables in your Render dashboard:

## Required Environment Variables

Go to your Render service dashboard and add/update these environment variables:

```
APP_URL=https://task-manager-api-nwrn.onrender.com
FORCE_HTTPS=true
L5_SWAGGER_USE_ABSOLUTE_PATH=true
APP_ENV=production
APP_DEBUG=false
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
- `FORCE_HTTPS`: Forces Laravel to generate HTTPS URLs
- `L5_SWAGGER_USE_ABSOLUTE_PATH`: Makes Swagger use absolute paths for assets
- `APP_ENV=production`: Sets Laravel to production mode
- `APP_DEBUG=false`: Disables debug mode for security

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
