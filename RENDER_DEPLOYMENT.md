# 🚀 Render Docker Deployment Guide

## Fixed Docker Setup for Render

### **What I Fixed:**
- ✅ **Package name issue**: Changed `liboniguruma-dev` to `libonig-dev`
- ✅ **Simplified approach**: Using Apache instead of Nginx+PHP-FPM
- ✅ **Better startup script**: Handles Laravel setup automatically
- ✅ **Optimized build**: Added .dockerignore for faster builds

## Step-by-Step Deployment

### **Step 1: Prepare Repository**
```bash
git add .
git commit -m "Fix Docker setup for Render deployment"
git push
```

### **Step 2: Create Render Service**
1. Go to [render.com](https://render.com)
2. Sign up/Login with GitHub
3. Click "New" → "Web Service"
4. Connect your GitHub repository

### **Step 3: Configure Service**
- **Name**: `task-management-api`
- **Language**: `Docker`
- **Branch**: `master` (or your main branch)
- **Root Directory**: `backend`
- **Dockerfile Path**: `./Dockerfile`

### **Step 4: Set Environment Variables**
Add these in Render dashboard:

```
APP_NAME=Task Management API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

LOG_CHANNEL=stack
LOG_LEVEL=error
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Task Management"
```

### **Step 5: Add Database (Optional)**
If you need a database:
1. In Render dashboard, click "New" → "PostgreSQL"
2. Create database
3. Copy connection details to environment variables

### **Step 6: Generate APP_KEY**
After first deployment:
1. Go to service dashboard
2. Open "Shell" tab
3. Run: `php artisan key:generate --show`
4. Copy the key and update `APP_KEY` environment variable
5. Redeploy

### **Step 7: Deploy**
1. Click "Create Web Service"
2. Wait for build and deployment (5-10 minutes)
3. Check logs for any issues

## 🔗 **Test Your API**

Once deployed, test these endpoints:
- **Health Check**: `https://your-app.onrender.com/api/health`
- **Register**: `POST https://your-app.onrender.com/api/register`
- **Login**: `POST https://your-app.onrender.com/api/login`

## 🎯 **Update Frontend**

Update your frontend's API base URL:

```typescript
// In frontend/src/services/api.ts
const API_BASE_URL = 'https://your-app.onrender.com/api'
```

## 🔧 **Troubleshooting**

### **Common Issues:**

1. **Build fails with package errors**:
   - Check the build logs
   - Verify all packages are correctly named

2. **Database connection fails**:
   - Verify database credentials
   - Check if database allows external connections

3. **APP_KEY errors**:
   - Generate APP_KEY as described in Step 6
   - Ensure it starts with `base64:`

4. **Permission errors**:
   - The startup script handles permissions automatically
   - Check if storage directories exist

### **View Logs:**
- Go to your service dashboard
- Click "Logs" tab
- Check both build and runtime logs

## 💡 **Database Options**

### **Option A: Render PostgreSQL**
- Create PostgreSQL service in Render
- Use provided connection details

### **Option B: PlanetScale (Free)**
- Create free database at [planetscale.com](https://planetscale.com)
- Use MySQL connection details
- 5GB free storage

### **Option C: Railway Database**
- Create database at [railway.app](https://railway.app)
- Connect to your Render app

## 🚀 **Expected Results**

After successful deployment:
- ✅ **Laravel migrations run automatically**
- ✅ **Configuration cached for performance**
- ✅ **API endpoints accessible**
- ✅ **Health check returns status**

## 📝 **Next Steps**

1. **Test all API endpoints**
2. **Update frontend configuration**
3. **Set up email notifications** (optional)
4. **Configure custom domain** (optional)

The Docker setup should now work reliably on Render! 🎉
