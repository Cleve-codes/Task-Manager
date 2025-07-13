# 🚂 Railway Deployment Guide

## Quick Setup Steps

### 1. **Go to Railway**
- Visit [railway.app](https://railway.app)
- Sign up/Login with your GitHub account

### 2. **Create New Project**
- Click "New Project"
- Select "Deploy from GitHub repo"
- Choose your `Task-Manager` repository
- Railway will auto-detect Laravel

### 3. **Configure Environment Variables**
Add these environment variables in Railway dashboard:

```
APP_NAME=Task Management API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here
APP_URL=https://your-app-name.up.railway.app

DB_CONNECTION=mysql
# Railway will auto-provide database credentials

LOG_CHANNEL=stack
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Task Management"
```

### 4. **Generate APP_KEY**
After first deployment:
1. Go to your Railway project dashboard
2. Open the "Deploy Logs" or use Railway CLI
3. Run: `php artisan key:generate --show`
4. Copy the generated key and update the `APP_KEY` environment variable

### 5. **Add Database (if needed)**
- In Railway dashboard, click "New Service"
- Select "Database" → "MySQL" or "PostgreSQL"
- Railway will automatically connect it to your Laravel app

### 6. **Deploy**
- Railway will automatically deploy when you push to your main branch
- Check the deployment logs for any issues

## 🔗 **API Endpoints**

Once deployed, your API will be available at:
- **Health Check**: `https://your-app.up.railway.app/api/health`
- **Register**: `POST https://your-app.up.railway.app/api/register`
- **Login**: `POST https://your-app.up.railway.app/api/login`

## 🎯 **Update Frontend**

Update your frontend's API base URL to point to your Railway deployment:

```typescript
// In frontend/src/services/api.ts
const API_BASE_URL = 'https://your-app.up.railway.app/api'
```

## 🚀 **Benefits of Railway**

- ✅ **No Docker needed** - Native PHP support
- ✅ **Auto-deployment** - Deploys on git push
- ✅ **Built-in database** - MySQL/PostgreSQL included
- ✅ **Environment variables** - Easy configuration
- ✅ **Custom domains** - Free subdomain included
- ✅ **Logs & monitoring** - Built-in debugging tools

## 🔧 **Troubleshooting**

If you encounter issues:
1. Check the deployment logs in Railway dashboard
2. Verify all environment variables are set
3. Ensure `APP_KEY` is generated and set
4. Check database connection settings

Railway's Laravel support is excellent - you should have a smooth deployment experience! 🎉
