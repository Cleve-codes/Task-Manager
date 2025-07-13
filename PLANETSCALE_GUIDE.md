# 🌟 PlanetScale + Free Hosting Setup

## Step 1: Create PlanetScale Database

### **Sign Up for PlanetScale**
1. Go to [planetscale.com](https://planetscale.com)
2. Click "Sign up for free"
3. Use GitHub/Google login (no credit card required)

### **Create Database**
1. Click "Create database"
2. Name: `task-management`
3. Region: Choose closest to you
4. Click "Create database"

### **Get Connection Details**
1. Go to your database dashboard
2. Click "Connect"
3. Select "Laravel" from framework dropdown
4. Copy the connection string

## Step 2: Configure Laravel for PlanetScale

### **Update Database Config**
In `backend/config/database.php`, add PlanetScale configuration:

```php
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
    'sslmode' => env('DB_SSLMODE', 'prefer'),
],
```

### **Environment Variables**
Use these in your `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=your-planetscale-host
DB_PORT=3306
DB_DATABASE=task-management
DB_USERNAME=your-planetscale-username
DB_PASSWORD=your-planetscale-password
DB_SSLMODE=require
```

## Step 3: Hosting Options with PlanetScale

### **Option A: InfinityFree + PlanetScale**
1. **Database**: PlanetScale (free 5GB)
2. **Hosting**: InfinityFree (free PHP hosting)
3. **Setup**: Upload Laravel files to InfinityFree, connect to PlanetScale

### **Option B: Vercel + PlanetScale**
1. **Database**: PlanetScale
2. **API**: Vercel Serverless Functions
3. **Setup**: Deploy Laravel as serverless functions

### **Option C: Netlify + PlanetScale**
1. **Database**: PlanetScale
2. **API**: Netlify Functions
3. **Setup**: Convert Laravel routes to serverless functions

## Step 4: Deploy to Vercel (Recommended)

### **Install Vercel CLI**
```bash
npm i -g vercel
```

### **Create vercel.json**
```json
{
  "version": 2,
  "builds": [
    {
      "src": "backend/public/index.php",
      "use": "vercel-php@0.6.0"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "backend/public/index.php"
    }
  ],
  "env": {
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "DB_CONNECTION": "mysql",
    "DB_HOST": "your-planetscale-host",
    "DB_DATABASE": "task-management",
    "DB_USERNAME": "your-planetscale-username",
    "DB_PASSWORD": "your-planetscale-password"
  }
}
```

### **Deploy**
```bash
vercel --prod
```

## Step 5: Run Migrations

### **Using PlanetScale CLI**
1. Install PlanetScale CLI
2. Connect to your database
3. Run migrations:

```bash
pscale connect task-management main --port 3309
php artisan migrate --force
```

### **Using Online Tool**
1. Use PlanetScale web console
2. Run SQL commands manually
3. Copy migration SQL from Laravel

## 🎯 **Complete Free Stack**

```
Frontend: Vercel (free)
    ↓
API: Vercel Serverless (free)
    ↓
Database: PlanetScale (free)
```

## 🔧 **Environment Variables for Production**

```env
APP_NAME="Task Management API"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
APP_URL=https://your-vercel-app.vercel.app

DB_CONNECTION=mysql
DB_HOST=your-planetscale-host.psdb.cloud
DB_PORT=3306
DB_DATABASE=task-management
DB_USERNAME=your-planetscale-username
DB_PASSWORD=your-planetscale-password
DB_SSLMODE=require

LOG_CHANNEL=stack
LOG_LEVEL=error
CACHE_DRIVER=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

## 💡 **Benefits of This Setup**

- ✅ **Completely free** (no credit card anywhere)
- ✅ **Scalable** (PlanetScale + Vercel scale automatically)
- ✅ **Fast** (Global CDN and edge database)
- ✅ **Reliable** (Enterprise-grade infrastructure)
- ✅ **Easy deployment** (Git-based deployments)

## 🚀 **Quick Start**

1. **Create PlanetScale database** (5 minutes)
2. **Deploy to Vercel** (2 minutes)
3. **Connect database** (1 minute)
4. **Run migrations** (1 minute)

**Total setup time: ~10 minutes!**

Would you like me to help you set this up step by step?
