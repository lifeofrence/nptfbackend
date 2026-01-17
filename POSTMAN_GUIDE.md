# NPTF API - Postman Collection Guide

## 📥 Import Instructions

1. **Open Postman**
2. Click **Import** button (top left)
3. Select **File** tab
4. Choose `NPTF-API.postman_collection.json`
5. Click **Import**

## 🔧 Setup

### Set Base URL
The collection uses a variable `{{base_url}}` which is set to `http://127.0.0.1:8000/api/v1` by default.

To change it:
1. Click on the collection name "NPTF Backend API"
2. Go to **Variables** tab
3. Update `base_url` value if needed

### Authentication Token
The collection automatically saves your authentication token when you login!

## 🚀 Quick Start

### Step 1: Login
1. Open **Authentication** → **Admin Login**
2. Click **Send**
3. ✅ Token is automatically saved to collection variables!

### Step 2: Test Protected Endpoints
All admin endpoints will now use the saved token automatically.

Try:
- **Authentication** → **Get Authenticated User**
- **News** → **Create News (Admin)**
- **Contact** → **Get All Contacts (Admin)**

## 📁 Collection Structure

### 1. Authentication
- **Admin Login** - Get authentication token
- **Get Authenticated User** - Verify token
- **Admin Logout** - Invalidate token

### 2. News
**Public:**
- Get All News (with pagination, search, filters)
- Get Single News

**Admin:**
- Create News (with image upload)
- Update News
- Delete News

### 3. Contact
**Public:**
- Submit Contact Form (sends emails)

**Admin:**
- Get All Contacts (with status filter)
- Update Contact Status

### 4. Testimonials
**Public:**
- Get Approved Testimonials
- Submit Testimonial

**Admin:**
- Get All Testimonials (including pending)
- Approve/Update Testimonial
- Delete Testimonial

### 5. Projects
**Public:**
- Get All Projects (with filters)
- Get Single Project

**Admin:**
- Create Project (with image upload)
- Update Project
- Delete Project

### 6. Gallery
**Public:**
- Get All Gallery Items (with filters)
- Get Single Gallery Item
- Get Event Names

**Admin:**
- Upload Gallery Item (image/video with thumbnail)
- Delete Gallery Item

## 💡 Usage Tips

### File Uploads
For endpoints with file uploads (News, Projects, Gallery):
1. Select the request
2. Go to **Body** tab
3. Enable the `image` or `media` field
4. Click **Select Files** to choose your file
5. Click **Send**

### Query Parameters
Many endpoints support optional query parameters:
- **News**: `?search=keyword&category=Budget&page=1&per_page=10`
- **Projects**: `?category=Infrastructure&status=ongoing`
- **Gallery**: `?event=Training&type=image`
- **Contacts**: `?status=pending`
- **Testimonials**: `?status=approved`

### Status Values
- **Contact**: `pending`, `read`, `resolved`
- **Testimonial**: `pending`, `approved`, `rejected`
- **Project**: `completed`, `ongoing`, `upcoming`
- **Gallery Type**: `image`, `video`

## 🔐 Authentication Flow

1. **Login** → Receive token (auto-saved)
2. **Use Protected Endpoints** → Token sent automatically
3. **Logout** → Token invalidated

All admin endpoints require authentication via Bearer token.

## 📊 Response Examples

### Successful Login
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "NPTF Admin",
    "email": "admin@nptf.gov.ng"
  },
  "token": "1|xxxxxxxxxxxxx"
}
```

### News List (Paginated)
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "title": "News Title",
      "excerpt": "Brief description",
      "content": "Full content...",
      "author": "NPTF Admin",
      "category": "Budget",
      "image": "data:image/jpeg;base64,...",
      "published_at": "2026-01-12",
      "created_at": "2026-01-12 10:30:00"
    }
  ],
  "per_page": 10,
  "total": 1
}
```

### Contact Submission
```json
{
  "message": "Thank you for contacting us! We will get back to you soon.",
  "data": {
    "id": 1,
    "name": "John Doe"
  }
}
```

## ⚠️ Important Notes

### Default Credentials
- **Email**: `admin@nptf.gov.ng`
- **Password**: `password123`

⚠️ Change these in production!

### Image Format
Images are returned as base64-encoded strings:
```
data:image/jpeg;base64,/9j/4AAQSkZJRg...
```

You can use these directly in `<img>` tags in HTML.

### Rate Limiting
Consider implementing rate limiting in production to prevent abuse.

### CORS
Update CORS settings in `.env` for frontend integration:
```env
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

## 🧪 Testing Workflow

1. **Login** (Authentication → Admin Login)
2. **Create Content**:
   - Create News Article
   - Create Project
   - Upload Gallery Items
3. **Test Public Endpoints**:
   - Get News
   - Get Projects
   - Get Gallery
4. **Test Contact Form**:
   - Submit Contact
   - Check Admin Contacts
5. **Manage Testimonials**:
   - Submit Testimonial
   - Approve as Admin
6. **Cleanup**:
   - Delete test items
7. **Logout**

## 📝 Environment Variables

You can create a Postman Environment for different setups:

**Local Development:**
```
base_url: http://127.0.0.1:8000/api/v1
```

**Staging:**
```
base_url: https://staging-api.nptf.gov.ng/api/v1
```

**Production:**
```
base_url: https://api.nptf.gov.ng/api/v1
```

## 🔄 Auto-Save Token Script

The login request includes a test script that automatically saves the token:

```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.collectionVariables.set('token', jsonData.token);
    pm.environment.set('token', jsonData.token);
}
```

This means you don't need to manually copy/paste tokens!

## 🎯 Next Steps

1. Import the collection
2. Start the Laravel server: `php artisan serve`
3. Test the endpoints
4. Integrate with your Next.js frontend

Happy testing! 🚀
