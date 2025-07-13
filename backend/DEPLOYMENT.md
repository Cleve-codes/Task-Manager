# Laravel API Deployment on Render

## 🚀 Quick Deployment Steps

### 1. Prepare Your Repository
- Ensure all files are committed to your Git repository
- Push to GitHub/GitLab

### 2. Create Database (Optional)
If you need a database, create one first:
- Go to Render Dashboard
- Click "New" → "PostgreSQL" or use external MySQL service
- Note down the connection details

### 3. Deploy on Render

1. **Go to [render.com](https://render.com) and sign in**

2. **Click "New" → "Web Service"**

3. **Connect your repository**

4. **Configure the service:**
   - **Name**: `task-management-api`
   - **Language**: `Docker`
   - **Branch**: `main` (or your default branch)
   - **Root Directory**: `backend` (if your Dockerfile is in backend folder)
   - **Dockerfile Path**: `./Dockerfile`

5. **Set Environment Variables:**
   ```
   APP_NAME=Task Management API
   APP_ENV=production
   APP_KEY=base64:YOUR_GENERATED_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://your-app-name.onrender.com
   
   DB_CONNECTION=mysql
   DB_HOST=your-database-host
   DB_PORT=3306
   DB_DATABASE=your-database-name
   DB_USERNAME=your-database-username
   DB_PASSWORD=your-database-password
   
   LOG_LEVEL=error
   SESSION_DRIVER=file
   CACHE_DRIVER=file
   ```

6. **Click "Create Web Service"**

### 4. Generate APP_KEY

After first deployment, you can generate the APP_KEY:

1. Go to your service dashboard
2. Open the "Shell" tab
3. Run: `php artisan key:generate --show`
4. Copy the generated key
5. Update the `APP_KEY` environment variable with the generated key
6. Redeploy the service

### 5. Database Setup

If using a database:
1. The migrations will run automatically on deployment
2. To seed data, use the Shell tab: `php artisan db:seed`

## 🔧 Troubleshooting

### Common Issues:

1. **Build fails**: Check Dockerfile syntax and ensure all files are committed
2. **Database connection**: Verify database credentials and host accessibility
3. **APP_KEY error**: Generate and set the APP_KEY as described above
4. **Permission errors**: The entrypoint script handles permissions automatically

### Logs:
- Check the "Logs" tab in Render dashboard for detailed error messages
- Use `LOG_LEVEL=debug` temporarily for more verbose logging

## 📝 Post-Deployment

1. **Test your API endpoints**:
   - `GET https://your-app-name.onrender.com/api/health` (if you have a health check)
   - `POST https://your-app-name.onrender.com/api/register`

2. **Update your frontend**:
   - Update API base URL in your frontend application
   - Update CORS settings if needed

3. **Set up monitoring**:
   - Enable health checks in Render
   - Set up error tracking (optional)

## 🔒 Security Notes

- Never commit `.env` files with real credentials
- Use strong database passwords
- Enable HTTPS (Render provides this automatically)
- Consider setting up rate limiting for production

## 📞 Support

If you encounter issues:
1. Check Render documentation
2. Review application logs
3. Verify environment variables
4. Test locally with Docker first
