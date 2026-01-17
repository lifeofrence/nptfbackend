# Cloudinary Setup Guide

## 1. Create Cloudinary Account

1. Go to https://cloudinary.com
2. Sign up for a free account
3. Verify your email

## 2. Get Your Credentials

After logging in to your Cloudinary dashboard:

1. Go to **Dashboard** (home page)
2. You'll see your credentials in the **Account Details** section:
   - **Cloud Name**
   - **API Key**
   - **API Secret**

## 3. Add Credentials to .env

Add these lines to your `/backend/.env` file:

```env
CLOUDINARY_CLOUD_NAME=your_cloud_name_here
CLOUDINARY_API_KEY=your_api_key_here
CLOUDINARY_API_SECRET=your_api_secret_here
```

Replace `your_cloud_name_here`, `your_api_key_here`, and `your_api_secret_here` with your actual credentials from the Cloudinary dashboard.

## 4. Test the Integration

After adding the credentials:

1. Restart your Laravel server: `php artisan serve`
2. Try uploading an image through the admin dashboard
3. Check your Cloudinary dashboard to see the uploaded images in the `nptf/` folder

## Folder Structure in Cloudinary

Images will be organized as follows:
- `nptf/news/` - News article images
- `nptf/projects/` - Project images
- `nptf/gallery/` - Gallery images and videos
- `nptf/gallery/thumbnails/` - Video thumbnails

## Benefits

✅ No more UTF-8 encoding errors
✅ Faster database queries
✅ Automatic image optimization
✅ CDN delivery for faster loading
✅ Unlimited storage (on paid plans)
✅ Image transformations available

## Troubleshooting

**Error: "Failed to upload image"**
- Check that your credentials are correct in `.env`
- Make sure you've restarted the Laravel server after adding credentials
- Check the Laravel logs: `tail -f storage/logs/laravel.log`

**Images not appearing**
- Check the Cloudinary dashboard to confirm uploads
- Verify the URL is being saved in the database
- Check browser console for CORS errors
