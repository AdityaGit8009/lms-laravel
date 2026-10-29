Applied fix summary:
1. Login now uses only users table.
2. Admin can login using email/password from users table.
3. Admin-added student is created in users table with role=student and approval_status=Approved.
4. Self-registered student is created in users table with role=student and approval_status=Pending.
5. Pending student cannot login until admin approval.
6. Admin approval updates both tbl_register and users.approval_status.
7. Student dashboard now reads logged-in student from users table.
8. Added migration: 2026_04_17_000001_add_login_profile_fields_to_users_table.php
9. Included DB patch file: lms_fixed.sql

Run after extracting:
- composer install
- copy .env and set DB credentials
- php artisan migrate
- php artisan optimize:clear
- php artisan serve

If you are using the provided SQL dump instead of migrate:
- import lms_fixed.sql
- then run php artisan optimize:clear
