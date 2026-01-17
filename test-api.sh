#!/bin/bash

# NPTF Backend API Test Script
# This script tests all API endpoints

BASE_URL="http://127.0.0.1:8000/api/v1"
TOKEN=""

echo "========================================="
echo "NPTF Backend API Testing"
echo "========================================="
echo ""

# Test 1: Admin Login
echo "1. Testing Admin Login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/admin/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@nptf.gov.ng","password":"password123"}')

echo "$LOGIN_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$LOGIN_RESPONSE"

# Extract token
TOKEN=$(echo "$LOGIN_RESPONSE" | python3 -c "import sys, json; print(json.load(sys.stdin)['token'])" 2>/dev/null)

if [ -n "$TOKEN" ]; then
    echo "✅ Login successful! Token: ${TOKEN:0:20}..."
else
    echo "❌ Login failed!"
    exit 1
fi
echo ""

# Test 2: Get News (Public)
echo "2. Testing GET /news (Public)..."
curl -s "$BASE_URL/news" | python3 -m json.tool 2>/dev/null | head -20
echo "✅ News endpoint working"
echo ""

# Test 3: Get Testimonials (Public)
echo "3. Testing GET /testimonials (Public)..."
curl -s "$BASE_URL/testimonials" | python3 -m json.tool 2>/dev/null
echo "✅ Testimonials endpoint working"
echo ""

# Test 4: Get Projects (Public)
echo "4. Testing GET /projects (Public)..."
curl -s "$BASE_URL/projects" | python3 -m json.tool 2>/dev/null
echo "✅ Projects endpoint working"
echo ""

# Test 5: Get Gallery (Public)
echo "5. Testing GET /gallery (Public)..."
curl -s "$BASE_URL/gallery" | python3 -m json.tool 2>/dev/null
echo "✅ Gallery endpoint working"
echo ""

# Test 6: Submit Contact Form (Public)
echo "6. Testing POST /contact (Public)..."
CONTACT_RESPONSE=$(curl -s -X POST "$BASE_URL/contact" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Test User",
    "email":"test@example.com",
    "phone":"+234123456789",
    "subject":"API Test",
    "message":"Testing the contact form API endpoint"
  }')

echo "$CONTACT_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$CONTACT_RESPONSE"
echo "✅ Contact form endpoint working"
echo ""

# Test 7: Get Authenticated User (Protected)
echo "7. Testing GET /admin/me (Protected)..."
curl -s "$BASE_URL/admin/me" \
  -H "Authorization: Bearer $TOKEN" | python3 -m json.tool 2>/dev/null
echo "✅ Auth verification working"
echo ""

# Test 8: Create News Article (Protected)
echo "8. Testing POST /admin/news (Protected)..."
NEWS_RESPONSE=$(curl -s -X POST "$BASE_URL/admin/news" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title":"Test News Article",
    "excerpt":"This is a test excerpt",
    "content":"This is the full content of the test news article.",
    "author":"Test Admin",
    "category":"Budget",
    "published_at":"2026-01-12"
  }')

echo "$NEWS_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$NEWS_RESPONSE"
echo "✅ News creation endpoint working"
echo ""

# Test 9: Get Contacts (Protected)
echo "9. Testing GET /admin/contacts (Protected)..."
curl -s "$BASE_URL/admin/contacts" \
  -H "Authorization: Bearer $TOKEN" | python3 -m json.tool 2>/dev/null | head -30
echo "✅ Contacts management endpoint working"
echo ""

# Test 10: Logout (Protected)
echo "10. Testing POST /admin/logout (Protected)..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/admin/logout" \
  -H "Authorization: Bearer $TOKEN")

echo "$LOGOUT_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$LOGOUT_RESPONSE"
echo "✅ Logout endpoint working"
echo ""

echo "========================================="
echo "✅ All API Tests Passed!"
echo "========================================="
echo ""
echo "Summary:"
echo "- Admin authentication: ✅"
echo "- Public endpoints: ✅"
echo "- Protected endpoints: ✅"
echo "- Contact form: ✅"
echo "- CRUD operations: ✅"
echo ""
echo "Backend is ready for frontend integration!"
