# 🆓 Free Heroku Deployment Guide

## Quick Setup (5 minutes)

### **Option 1: One-Click Deploy (Easiest)**

1. **Click this button to deploy instantly:**

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/Cleve-codes/Task-Manager)

2. **Fill in the app name** (e.g., `your-task-manager-api`)
3. **Click "Deploy app"**
4. **Wait for deployment to complete**
5. **Click "View app"** to see your API

### **Option 2: Manual Deploy**

1. **Install Heroku CLI**
   - Download from: https://devcenter.heroku.com/articles/heroku-cli

2. **Login to Heroku**
   ```bash
   heroku login
   ```

3. **Navigate to backend directory**
   ```bash
   cd backend
   ```

4. **Create Heroku app**
   ```bash
   heroku create your-app-name
   ```

5. **Add PostgreSQL database**
   ```bash
   heroku addons:create heroku-postgresql:mini
   ```

6. **Set environment variables**
   ```bash
   heroku config:set APP_NAME="Task Management API"
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set LOG_LEVEL=error
   ```

7. **Generate and set APP_KEY**
   ```bash
   php artisan key:generate --show
   heroku config:set APP_KEY="base64:your-generated-key-here"
   ```

8. **Deploy**
   ```bash
   git add .
   git commit -m "Prepare for Heroku deployment"
   git push heroku master
   ```

## 🔗 **Your API Endpoints**

After deployment, your API will be available at:
- **Base URL**: `https://your-app-name.herokuapp.com`
- **Health Check**: `https://your-app-name.herokuapp.com/api/health`
- **Register**: `POST https://your-app-name.herokuapp.com/api/register`
- **Login**: `POST https://your-app-name.herokuapp.com/api/login`

## 🎯 **Update Frontend**

Update your frontend's API base URL:

```typescript
// In frontend/src/services/api.ts
const API_BASE_URL = 'https://your-app-name.herokuapp.com/api'
```

## 💡 **Free Tier Limits**

Heroku free tier includes:
- ✅ **550-1000 dyno hours/month** (enough for development)
- ✅ **Free PostgreSQL database** (10,000 rows)
- ✅ **Custom domain support**
- ✅ **HTTPS included**

## 🔧 **Troubleshooting**

If deployment fails:
1. Check logs: `heroku logs --tail`
2. Ensure all environment variables are set
3. Verify database connection
4. Check Laravel requirements

## 🚀 **Alternative Free Options**

If Heroku doesn't work:

### **InfinityFree**
1. Go to [infinityfree.net](https://infinityfree.net)
2. Create account and upload Laravel files
3. Configure database settings

### **000webhost**
1. Go to [000webhost.com](https://000webhost.com)
2. Create free account
3. Upload Laravel project

Both offer completely free PHP hosting with MySQL databases!

---

**Recommendation**: Try the one-click Heroku deploy first - it's the easiest option! 🎉
