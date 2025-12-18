# 🚀 Quick Start Reference - Batch Upload & User Management

## 📌 Admin Quick Links

### Batch Upload
- **URL:** `http://yoursite.com/styluxe/admin/batch-upload`
- **Access:** Admin only
- **Methods:** 
  - GET - View upload form
  - POST - Upload CSV file

### Create User
- **URL:** `http://yoursite.com/styluxe/settings/users/create`
- **Access:** Admin only
- **Methods:**
  - GET - View user creation form
  - POST - Create new user

---

## 📥 Batch Upload Quick Start

### 1️⃣ Download Sample
Click "⬇️ Download Sample CSV" button on the batch upload page.

### 2️⃣ Prepare CSV
Open in Excel or Google Sheets. Minimum columns:
```
item_name, category, size, color, condition, description, quantity, price, status
```

### 3️⃣ Upload
Select your CSV file and click "📤 Upload & Import"

### 4️⃣ Verify
Check success message. Items appear in Items Management.

---

## 👤 Create User Quick Start

### 1️⃣ Go to Create User
Settings → User Management → Create New User

### 2️⃣ Fill Form
- **Name:** Full name
- **Email:** Unique email (must not exist)
- **Password:** Min 6 characters
- **Confirm:** Re-enter password exactly
- **Role:** Admin or Client
- **Active:** Check to enable

### 3️⃣ Create
Click "✅ Create User"

### 4️⃣ Share
Share login email and initial password with user.

---

## 📝 CSV Column Guide

| # | Column | Type | Example |
|---|--------|------|---------|
| 1 | item_name | Text | Vintage Blue Denim Jacket |
| 2 | category | Text | Outerwear |
| 3 | size | Text | M, L, 32, XL |
| 4 | color | Text | Blue |
| 5 | condition | New/Pre-Loved/Vintage/Branded | Pre-Loved |
| 6 | description | Text | Great condition, minimal wear |
| 7 | quantity | Number | 5 |
| 8 | price | Decimal | 299.99 |
| 9 | status | Available/Out-Of-Stock/Sold Out | Available |

---

## ⚠️ Common Errors & Fixes

### Batch Upload Errors

| Error | Fix |
|-------|-----|
| "CSV file field is required" | Select a file before upload |
| "Invalid CSV format" | Check headers match exactly |
| "File too large" | Max 2MB; split into smaller files |
| "Some items failed" | Check validation rules; retry failed rows |

### User Creation Errors

| Error | Fix |
|-------|-----|
| "Email already exists" | Use different email address |
| "Password confirmation doesn't match" | Re-enter same password exactly |
| "Role is required" | Select Admin or Client |
| "Name is required" | Enter user's full name |

---

## 🔐 User Roles

### Admin Access
- ✅ View/Create/Edit/Delete items
- ✅ Batch upload items
- ✅ View/Create/Edit/Delete users
- ✅ View all orders
- ✅ View analytics
- ✅ System settings

### Client Access
- ✅ View available items
- ✅ Create and place orders
- ✅ View own orders
- ✅ Update profile
- ❌ Cannot access admin features

---

## 💾 Sample Data

**File:** `public/samples/batch-upload-sample.csv`

**Included Items:**
1. Vintage Blue Denim Jacket (Outerwear)
2. White Cotton T-Shirt (Top)
3. Black Skinny Jeans (Bottom)
4. Floral Summer Dress (Dresses)
5. Grey Hoodie (Outerwear)
6. Red Silk Blouse (Top)
7. Navy Blue Chinos (Bottom)
8. Striped Polo Shirt (Top)
9. Beige Cardigan (Outerwear)
10. Green Corduroy Jacket (Outerwear)

---

## 🔗 Useful Routes

```
GET  /styluxe/admin/batch-upload
POST /styluxe/admin/batch-upload

GET  /styluxe/settings/users/create
POST /styluxe/settings/users

GET  /styluxe/settings/users
POST /styluxe/settings/users/{id}/toggle
DELETE /styluxe/settings/users/{id}
```

---

## ✅ Validation Rules

### Batch Upload
- File type: CSV or TXT only
- File size: Max 2MB
- Headers: Must match column names exactly
- Each row: Validated independently
- Items: Quantity must be numeric, price must be decimal

### User Creation
- Email: Valid email format, must be unique
- Name: Required, max 255 characters
- Password: Min 6 characters, must be confirmed
- Role: Must be "admin" or "client"
- Active: Boolean value (checked = active)

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| BATCH_UPLOAD_GUIDE.md | Complete user guide (200+ lines) |
| USER_MANAGEMENT_COMPLETION.md | Technical checklist |
| FEATURE_SUMMARY.md | Overview of both features |

---

## 🎯 Tips & Best Practices

### For Batch Upload
1. **Start Small:** Test with 5-10 items first
2. **Use Sample:** Always start from provided sample CSV
3. **Check Twice:** Verify prices and quantities before upload
4. **Backup:** Keep copy of your CSV files
5. **Schedule:** Do bulk imports during off-hours

### For User Management
1. **Unique Emails:** Each email can only be used once
2. **Strong Passwords:** Encourage users to change password after first login
3. **Role Assignment:** Don't make everyone admin
4. **Backup Admin:** Keep 2+ admin accounts for access
5. **Regular Review:** Check and deactivate unused accounts

---

## 🆘 Troubleshooting

### Can't Access Features
- [ ] Are you logged in as Admin?
- [ ] Check your user role in profile
- [ ] Clear browser cache and refresh

### CSV Won't Upload
- [ ] Is file under 2MB?
- [ ] Check file extension (.csv)
- [ ] Verify headers match exactly
- [ ] No extra spaces or special chars in headers

### User Won't Create
- [ ] Email unique? (not used before)
- [ ] Password 6+ characters?
- [ ] Passwords match in confirmation?
- [ ] Role selected?

---

## 📞 Quick Support

**For detailed help:**
- See BATCH_UPLOAD_GUIDE.md for comprehensive guide
- Check USER_MANAGEMENT_COMPLETION.md for technical details
- Review error messages - they indicate the problem

**If stuck:**
1. Read the guide section
2. Check the error message
3. Review the sample CSV
4. Try with test data first

---

## ✨ Features at a Glance

**Batch Upload** 📤
- CSV import for bulk items
- Auto-barcode generation
- Partial import (skip invalid, keep valid)
- Stock logging
- Validation and error reporting

**User Management** 👤
- Create admin and client accounts
- Email validation and uniqueness
- Password hashing (bcrypt)
- Account active/inactive toggle
- Delete users
- View user list

---

**You're all set! Happy managing! 🎉**

Read BATCH_UPLOAD_GUIDE.md for more details.
