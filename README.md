# Online-Food-Ordering-System
Online food ordering system with an admin panel to manage the menu and orders.
1. Install and setup XAMPP 
2. Create a database(using code provided in spicevilla_db.sql)
3. Download all files from this repo.
4. Store all the files in xampp/htdocs/spicevilla.
5. Start php and sql services in XAMPP.
6. Run the project in your browser using localhost.

Features
Customer Module
User Authentication: Secure registration, login, and logout functionalities.

Dynamic Menu Display: Browse food items categorized by type, with descriptions, prices, and images.

Interactive Shopping Cart: Add/remove items, adjust quantities, and view real-time order totals.

Order Placement: Seamless checkout process with delivery address and contact information.

Order History & Tracking: View past and current orders, including detailed breakdowns and current status (pending, processing, completed, cancelled).

Admin Module
Admin Authentication: Secure login for restaurant staff with role-based access.

Menu Management (CRUD): Add new menu items, edit existing details (name, description, price, category, availability, image URL), and delete items.

Order Viewing: Comprehensive list of all customer orders.

Order Status Updates: Change order status (e.g., from 'pending' to 'processing', 'completed', or 'cancelled').

Order Deletion: Permanently delete 'completed' or 'cancelled' orders for archival purposes.

Technologies Used
Backend:

PHP: Server-side scripting for all business logic, database interactions, and dynamic content generation.

MySQL: Relational database management system for storing all application data (users, menu items, orders, order items).

Frontend:

HTML: Structure and content of all web pages.

CSS: Styling and responsive design (using custom CSS).

JavaScript: Client-side interactivity, shopping cart logic, and asynchronous communication (AJAX).

Other:

AJAX: For asynchronous communication between frontend and backend (e.g., placing orders, updating status).

Font Awesome: Used for icons (e.g., shopping cart, social media).
