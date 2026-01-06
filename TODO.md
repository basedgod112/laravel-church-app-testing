# TODO: Role-Based Modules for Laravel Church App

## 1. Database Migrations
- [x] Create migration for announcements table
- [x] Create migration for schedules table
- [x] Create migration for sacramental_records table
- [x] Create migration for payments table
- [x] Create migration for documents table
- [x] Run migrations

## 2. Eloquent Models
- [x] Create Announcement model with relationships
- [x] Create Schedule model with relationships
- [x] Create SacramentalRecord model with relationships
- [x] Create Payment model with relationships
- [x] Create Document model with relationships

## 3. Controllers
- [x] Admin Controllers: AccountController, AnnouncementController, RecordController, PaymentController
- [x] Clergy Controllers: ScheduleController, RecordController
- [x] Parishioner Controllers: HomepageController, ScheduleController, AnnouncementController, PaymentController

## 4. Views
- [ ] Create Blade templates for admin modules (CRUD for announcements, records, payments)
- [ ] Create Blade templates for clergy modules (schedules, records)
- [ ] Create Blade templates for parishioner modules (homepage, schedules, announcements, payments)

## 5. Routes
- [ ] Update routes/web.php to use RESTful routes (Route::resource) for new modules
- [ ] Apply appropriate middleware (admin for admin/clergy, auth for parishioner)

## 6. Features
- [ ] Implement file upload functionality for documents
- [ ] Implement payment tracking (online/offline)

## 7. Testing
- [ ] Test modules with seeded accounts (admin, moderator, priest, parishioner)
