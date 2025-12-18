# 📤 Styluxe Batch Upload & User Management Guide

## Overview

This guide covers two major admin features in Styluxe:
1. **Batch Upload** - Import multiple items via CSV
2. **User Management** - Create and manage user accounts

---

## 🚀 Batch Upload Feature

### What is Batch Upload?

Batch Upload allows administrators to import multiple items (clothing inventory) into the system using a CSV file. This is ideal for:
- Initial inventory setup
- Adding seasonal collections
- Bulk updates from suppliers
- Data migration from other systems

### How to Use

#### Step 1: Access Batch Upload
1. Login as an **Admin** user
2. Navigate to **Dashboard** → **📤 Batch Upload Items**
   - Or use the admin menu from the items page

#### Step 2: Download Sample CSV
- Click the **⬇️ Download Sample CSV** button
- This provides a pre-formatted template with example items
- The sample includes 10 clothing items showing the correct format

#### Step 3: Prepare Your Data
1. Open the downloaded CSV file in Excel, Google Sheets, or any text editor
2. Keep the header row exactly as shown:
   ```
   item_name,category,size,color,condition,description,quantity,price,status
   ```
3. Add your items row by row
4. Ensure all required columns are filled

#### Step 4: Save and Upload
1. Save your file as `.csv` format (not Excel format)
2. Go back to the Batch Upload page
3. Click **Select CSV File** and choose your prepared file
4. Click **📤 Upload & Import**

#### Step 5: Review Results
- Success message shows number of items imported
- Any validation errors are reported
- Check the **Items Management** page to verify imports

### CSV Column Requirements

| Column | Type | Required | Example |
|--------|------|----------|---------|
| `item_name` | Text | ✅ | Vintage Blue Denim Jacket |
| `category` | Text | ✅ | Outerwear |
| `size` | Text | ✅ | M, L, S, XL, 32, etc. |
| `color` | Text | ✅ | Blue |
| `condition` | Selection | ✅ | New, Pre-Loved, Vintage, Branded |
| `description` | Text | ✅ | Great condition, minimal wear |
| `quantity` | Number | ✅ | 5 |
| `price` | Decimal | ✅ | 299.99 |
| `status` | Selection | ✅ | Available, Out-Of-Stock, Sold Out |

### Sample CSV Row

```csv
Vintage Blue Denim Jacket,Outerwear,M,Blue,Pre-Lived,Classic denim jacket in great condition with minimal wear,5,2500.00,Available
```

### Important Notes

⚠️ **Barcodes**: Each item automatically receives a unique barcode during import. Do NOT include barcodes in your CSV file.

📊 **Low Stock**: Default low stock threshold is 20 units. Items with quantity ≤ 20 will trigger alerts.

🔍 **Validation**: 
- Item names must be unique and under 255 characters
- Prices must be valid decimal numbers
- Quantities must be whole numbers
- Categories must exist in the system

❌ **Error Handling**: If some rows fail validation, the system will:
- Report which rows had errors
- Skip invalid rows but continue processing valid ones
- Let you fix and re-upload the failed rows

### Sample CSV File

A sample CSV file is available at: `/public/samples/batch-upload-sample.csv`

The sample includes:
- Vintage Blue Denim Jacket
- White Cotton T-Shirt
- Black Skinny Jeans
- Floral Summer Dress
- Grey Hoodie
- Red Silk Blouse
- Navy Blue Chinos
- Striped Polo Shirt
- Beige Cardigan
- Green Corduroy Jacket

---

## 👤 User Management Feature

### What is User Management?

User Management allows administrators to:
- Create new admin and client accounts
- Manage user roles and permissions
- Activate/deactivate accounts
- Delete user accounts
- View all user accounts

### User Roles

#### Admin Role
- Full system access
- Manage items, orders, and inventory
- Create and manage users
- View analytics and reports
- Access batch upload feature

#### Client Role
- Browse available items
- Place and track orders
- Manage personal profile
- View order history
- Limited to own data

### How to Create a User

#### Step 1: Access User Creation
1. Login as an **Admin**
2. Navigate to **Settings** → **👤 Create New User**
   - Or use the admin menu from the users page

#### Step 2: Fill in User Details

**Full Name** (Required)
- Enter the user's full name
- Example: "John Doe"

**Email Address** (Required)
- Must be unique (no duplicates)
- Example: "john@example.com"

**Password** (Required)
- Minimum 6 characters
- Must be confirmed (re-enter in confirmation field)
- Example: "SecurePass123"

**Role** (Required)
- Select **Admin** or **Client**
- Admin: Full system access
- Client: Browse items and place orders

**Account Active** (Optional)
- Check to enable the account (default: enabled)
- Uncheck to create inactive account
- Inactive accounts cannot login

#### Step 3: Create Account
1. Click **✅ Create User**
2. Success message confirms account creation
3. Redirect to user management page

### How to Manage Users

#### View All Users
1. Go to **Settings** → **User Management**
2. See list of all admin and client accounts
3. View account status and roles

#### Deactivate/Reactivate User
1. From user list, click the **Toggle Status** button
2. Account will be deactivated (cannot login)
3. Or reactivated if already inactive

#### Delete User
1. From user list, click the **Delete** button
2. Confirm deletion
3. User account is permanently removed

### Important Notes

⚠️ **Email Uniqueness**: Each email can only be used once in the system

🔐 **Password Security**: 
- Passwords are hashed with bcrypt
- Never share passwords via email or chat
- Users should change password on first login (optional feature)

👤 **Self-Account**: You cannot deactivate or delete your own admin account

📊 **User Counts**: Dashboard shows total admin and client users

---

## 🔧 Troubleshooting

### Batch Upload Issues

**"The csv file field is required."**
- You didn't select a file before uploading
- Choose a CSV file and try again

**"Invalid CSV format"**
- Check column headers match exactly
- Ensure no extra spaces or typos
- Download the sample CSV to verify format

**"Some items failed to import"**
- Check validation rules in error message
- Correct the values in those rows
- Re-upload the fixed CSV

**File size too large**
- Maximum file size is 2MB
- For very large imports, split into multiple files

### User Management Issues

**"Email already exists"**
- Email is already registered in the system
- Use a different email address

**"Password confirmation doesn't match"**
- Re-enter password and confirmation exactly the same
- Check for extra spaces or typos

**"Cannot deactivate own account"**
- You cannot deactivate your own admin account
- Ask another admin to help if needed

**"Cannot delete admin accounts with pending orders"**
- Reassign or complete orders first
- Or contact system administrator

---

## 📝 Best Practices

### For Batch Upload

1. **Test First**: Always test with a small batch before large imports
2. **Backup Data**: Keep a backup of your CSV files
3. **Use Sample**: Start from the provided sample CSV template
4. **Validate Carefully**: Double-check prices, quantities, and spellings
5. **Organize Categories**: Ensure category names are consistent
6. **Size Standards**: Use standard size notations (XS, S, M, L, XL, or numeric)
7. **Batch Reports**: Keep records of import dates and quantities

### For User Management

1. **Role Assignment**: Assign least privilege (don't make everyone admins)
2. **Active Review**: Regularly review and deactivate unused accounts
3. **Email Format**: Use professional email addresses
4. **Documentation**: Document why you created each account
5. **Segregation**: Keep admin and client accounts separate
6. **Testing**: Test new accounts immediately after creation
7. **Backup Access**: Ensure at least 2 admin accounts exist

---

## 📞 Support

For technical issues or questions:
1. Check this guide first
2. Review error messages carefully (they often indicate the solution)
3. Check the dashboard for system status
4. Contact system administrator

---

## 📋 Quick Reference

### File Locations
- **Sample CSV**: `/public/samples/batch-upload-sample.csv`
- **Batch Upload Form**: `/admin/batch-upload`
- **User Management**: `/settings/users`
- **Create User Form**: `/settings/users/create`

### Routes
- **GET /styluxe/admin/batch-upload** - Show upload form
- **POST /styluxe/admin/batch-upload** - Process upload
- **GET /styluxe/settings/users** - User list
- **GET /styluxe/settings/users/create** - Create user form
- **POST /styluxe/settings/users** - Store new user

### Default Values
- **Low Stock Threshold**: 20 units
- **Default Status**: Available
- **Account Active**: Yes (by default)
- **Password Min Length**: 6 characters

---

## Version Information

- **Feature Version**: 1.0
- **Last Updated**: December 2024
- **Requires**: Laravel 11+, PHP 8.2+
- **Database**: MySQL/MariaDB with proper migrations

---

**Happy importing and user management! 🎉**
