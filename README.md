📋 Taxi Booking System - Admin Panel Full Specification Document
✅ Technology Stack
Backend: Laravel 11+ with Microservices Architecture


Frontend: Blade + Bootstrap 5 + Tailwind CSS + Custom Design System —> You choose best


Database: MySQL 8.0 (query optimization) + with Redis Caching

Queue System: Laravel Horizon with Redis


Authentication: Laravel Breeze / Laravel Jetstream


Role Management: Laravel Spatie Permissions


Charts: Chart.js or ApexCharts


Notifications: Laravel Notifications (email/SMS support)
File Storage: AWS S3 with CDN integration

Real-time: Laravel WebSockets + Pusher
Mobile: Progressive Web App (PWA) capabilities
Backup: Automated daily backups with point-in-time recovery
SEO & CMS: Multilingual (i18n) + JSON-LD Schema
🎯 Objectives
To build a scalable, customizable, and secure Admin Panel with:
Multi-role access (Super Admin, City Manager, Support Staff)


Full customer and driver management


Real-time ride tracking and analytics


Payment, complaints, and commission management



👤 User Roles & Permissions
In our system, role-based access is not just a static assignment, but a dynamic feature toggler, allowing the Super Admin to tailor module visibility and functionality per client domain. This ensures a world-class, flexible permission model:
Super Admin Dashboard


Client Management: Register each client with a unique domain or subdomain (e.g., client1.quickcab.com).


Feature Selector: For each client, enable or disable any combination of modules (Rides, Payments, Reports, Messaging, etc.) via a toggles UI.


Role Templates: Define “Role Templates” (e.g., Basic, Standard, Premium) that bundle preset permissions and features. Assign templates per client.


Custom Roles: Beyond templates, create fully custom roles scoped to a client, with granular permission checkboxes (view, create, edit, delete) on every module.


Domain Isolation: Each client’s roles and permissions are scoped to their domain, preventing cross-client visibility or data leakage.


Dynamic Permission Engine


Built on Laravel Spatie, extended with a FeatureFlag middleware. Before rendering any menu or action, the system checks both the role permission and the feature flag for that client.


Hierarchical Overrides: Individual user overrides can be applied if a user needs extra access beyond their role template, useful for temporary promotions or support cases.


Predefined Roles & Examples:


Template Name
Includes Modules
Use Case
Basic
Rides (view only), Messaging
Clients with read-only monitoring
Standard
Rides (full), Users, Drivers, Payments
Small companies needing core functionality
Enterprise
All modules + Reports + Notifications + CMS
Full-featured clients
Custom
Fully selected by Super Admin
Tailored offerings


4. Domain-Specific Admins


Client Admins: Admin users under a client’s domain only see and manage resources for their own domain.


City Managers & Regional Admins: Further scope admins per zone or region within a client, with sub-domain level restrictions.


Audit & Logs


Track role/template changes, feature toggles, and login events per domain. Administrators can export an audit trail for compliance.


------------- | ------------------------------------------ | | Super Admin | Full control of entire system | | City Admin | Manage drivers and users for assigned city | | Support Staff | View-only access to monitor issues | | Accountant | Handle payouts, transactions |
Use Laravel Spatie package for dynamic permission creation and assignment.

🧱 Module-wise Features
1. 🔐 Authentication & Dashboard
Secure login/logout


Forgot password


Two-factor authentication (optional)


Dashboard with:


Total rides (daily/weekly/monthly)


Active drivers


Active passengers


Revenue stats


Canceled/completed trips


Heatmap of rides by location


2. 👥 User Management
a. Passengers
List/Search/Filter users


View user profile, trip history


Block/unblock users


Reset password manually


b. Drivers
View all registered drivers


Driver approval/rejection system


View uploaded documents (license, RC, insurance)


View earnings, current trip


Toggle availability status (force offline)


Ratings and reviews from passengers


3. 🚗 Ride Management
View all ride requests


Filter by status (Pending, Accepted, In Progress, Completed, Canceled)


View real-time trip location on map


View ride summary (distance, time, fare, driver & user)


Force cancel ride


4. 📍Zone & Pricing Management
A robust Zone & Pricing Management system allows granular control over fare structures across geographies, time windows, vehicle types, and demand patterns. Key capabilities:
Zones & Sub-Zones


Define Geographic Areas: Create zones by drawing polygons on a map or uploading GeoJSON.


Sub-Zones: Nested areas for differentiated pricing within larger cities (e.g., Downtown, Suburbs).


Zone Assignment: Assign drivers and City Managers to specific zones or sub-zones.


Pricing Models


Base Fare Configuration: Set a flat base charge per zone or sub-zone.


Distance & Time Rates: Separate per-km and per-minute rates, adjustable per vehicle category and zone.


Tiered Pricing: Define volume discounts or flat-rate packages (e.g., airport transfers at fixed price).


Surge & Demand Pricing: Automated surge multipliers based on real-time supply-demand ratio. Set surge tiers (1.2x, 1.5x, 2x) with time-of-day caps.


Scheduled Ride Pricing: Option to apply premium (or discount) for pre-booked rides.


Custom Pricing Rules & Overrides


Holiday & Event Pricing: Upload calendar dates or event schedules to apply special rates automatically.


Promotional Codes: Integrate discount coupons or seasonal offers at the zone level.


Client-Specific Overrides: If a client’s feature selector toggles “Premium Pricing Tools,” they gain access to advanced rules (e.g., loyalty-based discounts).


Vehicle Categories & Pricing


Category Management: Sedan, SUV, Minivan, Luxury, Bike, etc.


Category-Specific Rates: Each category can have its own base, per km, per min, and surge thresholds.


Inventory Control: Limit count of active vehicles per category in each zone.


Dynamic Preview & Testing


Fare Estimation Tool in Admin Panel: Select pickup/drop points on a mini-map to preview calculated fare under current rules.


Rule Simulator: Create hypothetical scenarios (e.g., surge at 2.0x, holiday rate) to test impact across zones.


Analytics & Reporting


Zone-Level Dashboards: Visualize average fare, ride count, and revenue per zone/sub-zone.


Surge Heatmap: Live map overlay showing current surge multipliers.


Exportable Data: CSV or PDF reports for zone performance and pricing adjustments.


API & App Integration


REST Endpoints: Endpoints to fetch active zones, pricing rules, and surge status for mobile apps.


Versioned Rules: Maintain history of pricing model changes to allow rollback or A/B testing.



5. 💵 Commission & Earnings 💵 Commission & Earnings
Set driver commission per zone or vehicle type


View driver payout summary


Commission tracking per ride


Export payment history as Excel/PDF


Manual payout entry


6. 💳 Payments & Transactions
View all transactions


Track online vs cash payments


Integration-ready for Stripe/Razorpay/PayPal


Refund initiation panel


Invoices per ride downloadable


7. 📈 Reports & Analytics
Daily/weekly/monthly reports:


Total rides


Cancellations


Earnings


Active drivers


Graphs and charts


CSV/PDF exportable


8. 📢 Notification & Messaging
Send push notifications to all users or selected drivers/passengers


Email marketing integration (Mailchimp/SMTP)


In-app messaging to a driver or user


9. ⚠️ Complaints & Ratings
View all submitted complaints


Filter by type (Driver/User/Trip)


Reply to complaints


Ban users or drivers from panel


View ratings for drivers


10. 🔒 Roles & Access Control
Use Laravel Spatie Roles & Permissions


Create custom roles with selective permissions


Assign roles per staff/admin


Show/hide sections based on access level


11. 📂 Document Verification
Upload driver documents (via app)


Admin panel view & approval system


Email notification on approval/rejection


Expiry alert system for documents


12. 🧑‍💼 Staff Management
Add internal team members


Assign roles (support, admin, accountant)


Login logs for all staff


Session timeout config


13. 🌍 CMS Management
Add/Edit FAQ, Privacy Policy, Terms & Conditions


Manage About Us and Contact pages


Support multilingual content


14. 🕸️ SEO & Marketing
A dedicated SEO & Marketing module to ensure your admin panel drives visibility and growth:
Meta Tag & Open Graph Manager


Customizable <title>, <meta name="description">, <meta name="keywords"> per client domain or page.


Open Graph tags for social sharing (og:title, og:description, og:image).


Sitemap & Robots Control


Auto-generate XML sitemaps for admin documentation and public pages.


Robots.txt editor to allow/disallow crawlers.


Structured Data & Schema


JSON-LD generator for LocalBusiness, WebSite, BreadcrumbList.


Schema templates for articles, FAQs, and events.


Analytics & Tracking Integration


Insert tracking codes (Google Analytics, Google Tag Manager, Facebook Pixel).


Preview and test events (pageviews, button clicks).


Marketing Campaigns & Email SEO


Manage newsletter templates with SEO-friendly content.


A/B test email subject lines and monitor open rates.


URL Slug & Redirects


Friendly URL slug editor for public-facing docs.


301/302 redirect manager.



15. 🔧 Settings
System config (timezone, currency, etc.)


Email & SMS settings


Firebase configuration (for app integration)


Notification toggle (email/SMS/in-app)


16. 📱 App Integration Panel
Our App Integration module transforms the admin panel into a full control center for mobile apps, from design to release:
API & Token Management


API Key Generation: Issue versioned API tokens with scoped permissions (read, write, admin).


Webhook Configurator: Define endpoints for ride events (start, end, cancel).


Theming & Branding


Theme Builder: Upload logos, set primary/secondary colors, typography.


Prebuilt Templates: Choose Light, Dark, or Custom presets.


Live Preview: Real-time preview in app mockup.


Feature Flags & Beta Releases


Toggle new features per client or user group.


Manage staged rollouts with percentage-based releases.


Design Asset Manager


Repository for app icons, splash screens, onboarding images.


Auto-generate required sizes for iOS and Android.


Version & Release Control


Register new APK/IPA builds, add release notes, set force-update rules.


Maintain changelog history and rollback capabilities.


Push Notification Setup


Manage FCM Server Keys and APNs certificates.


Predefined templates for ride requests, promos, and alerts.


In-App Analytics


Integrate Firebase Analytics.


Dashboard for active users, session durations, and screen flows.## 📐 UI Suggestions (HTML + CSS)


Use Bootstrap 5 for layout


Blade templating with reusable components


Sidebar with detailed menus:


Dashboard


Overview


Heatmaps


Quick Stats


Clients


Client List


Domains & Subdomains


Client Feature Selector


Users


Passengers


All Passengers


Blocked Users


Verification Status


Drivers


All Drivers


Pending Approvals


Document Verification


Ratings & Reviews


Rides


All Rides


Pending Requests


In Progress


Completed Rides


Cancellations


Force Cancel


Zone & Pricing


Zones & Sub-Zones


GeoJSON Import


Pricing Models


Base Fares


Distance/Time Rates


Surge Rules


Holiday/Event Pricing


Fare Simulator


Payments


Transactions


Refunds


Payouts & Commissions


Payment Gateways


Reports & Analytics


Ride Reports


Revenue Reports


Zone Performance


Export Data


Notifications & Messaging


Push Notification Templates


Email Campaigns


SMS Campaigns


Webhooks


SEO & Marketing


Meta Manager


Sitemap & Robots


Structured Data


Analytics Integrations


Redirects


CMS


FAQ


Privacy Policy


Terms & Conditions


Content Blocks


Roles & Access


Role Templates


Custom Roles


Feature Flags


Audit Logs


App Integration


API Tokens


Webhooks Config


Theme & Branding


Release Control


Design Assets


Staff Management


Team Members


Activity Logs


Login Audit


Settings


System Config


Email/SMS Settings


Firebase Keys


Feature Toggles


Use Laravel Mix for asset compilation

🔑 Security Practices
CSRF protection (default in Laravel)


Password hashing (bcrypt)


HTTPS-only deployment


Permission middleware on all admin routes


Secure document uploads (file validation)



📦 Folder Structure (Laravel)
app/
  ├── Http/Controllers/Admin/
  ├── Models/
  ├── Policies/
resources/views/admin/
  ├── layouts/
  ├── dashboard.blade.php
  ├── users/
  ├── drivers/
  ├── rides/
  ├── payments/


📈 Future Enhancements
Multi-language admin panel


AI-based demand forecasting


Heatmap for driver allocation


Driver incentive module


Subscription system for partners



✅ Conclusion
This Admin Panel is the central brain of your taxi system. It should be:
Robust and secure


Easy to navigate


Flexible for scaling



