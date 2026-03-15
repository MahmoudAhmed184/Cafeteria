# **Cafeteria Management System \- Project Requirements**

Based on the provided wireframes, this document outlines the functional requirements, user roles, and specific features needed to build the Cafeteria Management System.

## **1\. System Roles**

The system has two distinct user roles with different access levels:

* **User (Employee/Customer):** Can browse products, place orders, and view their own order history.  
* **Admin:** Can manage products, users, categories, view all incoming orders, place manual orders for users, and view financial checks/reports.

## **2\. General Requirements & Authentication (Page 1\)**

* **Login System:** \* Authentication using Email and Password.  
  * "Forget Password?" functionality for account recovery.  
  * Role-based redirection upon successful login (Admin vs. Regular User).

## **3\. User Panel Features**

### **3.1 User Dashboard / Home (Page 2\)**

* **Product Catalog:** View available products with images, names, and prices.  
* **Search:** Search bar to filter products by name.  
* **Shopping Cart:**  
  * Add items to the cart.  
  * Adjust quantities using \+ and \- buttons.  
  * Remove items using the X button.  
  * Displays individual item totals and the grand total price (e.g., "EGP 55").  
* **Order Placement:**  
  * "Room ComboBox": Select a delivery room from a dropdown list.  
  * "Notes": Text area to add special instructions (e.g., "1 Tea Extra Sugar").  
  * "Confirm" button to submit the order.  
* **Latest Order Widget:** A section displaying the user's most recent order for quick re-ordering or reference.

### **3.2 My Orders (Page 4\)**

* **Order History Table:** Displays previous orders with Order Date, Status, Amount, and Action.  
* **Order Statuses:** Needs to support at least three states: Processing, Out for delivery, and Done.  
* **Order Cancellation:** Users can cancel an order (via a CANCEL button) **only** if the status is still "Processing".  
* **Order Details Expansion:** Users can click a \+ icon on a row to expand it and view the specific quantities of each item in that order.  
* **Filtering & Pagination:**  
  * Filter orders by "Date from" and "Date to".  
  * Pagination controls (\< 1 \>) for long lists.

## **4\. Admin Panel Features**

### **4.1 Admin Orders Dashboard (Page 10\)**

* **Incoming Orders View:** Displays incoming orders that need to be processed.  
* **Order Details:** Shows Order Date, Name (of the user), Room, Ext. (Phone Extension), and the total amount.  
* **Action:** A deliver action button to change the order status (e.g., from Processing to Out for delivery).  
* **Expanded View:** Admins can see the exact breakdown of items and quantities for each order.

### **4.2 Manual Order (Page 3\)**

* *Similar to the User Dashboard, but designed for Admins to place orders on behalf of users.*  
* **User Selection:** Includes an "Add to user" searchable dropdown/combobox to select which user the order belongs to.  
* Includes all standard cart features (search, quantity adjustments, room selection, notes, confirmation).

### **4.3 Products Management (Pages 5 & 8\)**

* **All Products List:** Table displaying Product, Price, Image, and Action.  
* **Product Status:** Ability to mark products as available or unavailable.  
* **Product Actions:** Edit or Delete existing products.  
* **Add Product Form:**  
  * Fields: Product (Name), Price (EGP), Category (Combobox), Product picture (File upload).  
  * "Add Category" link/button to dynamically add new product categories.  
  * Save and Reset buttons.

### **4.4 Users Management (Pages 6 & 7\)**

* **All Users List:** Table displaying Name, Room, Image (Profile thumbnail), Ext., and Action.  
* **User Actions:** Edit or Delete existing users.  
* **Add User Form:**  
  * Fields: Name, Email, Password, Confirm Password, Room No., Ext. (Extension), Profile picture (File upload).  
  * Save and Reset buttons.

### **4.5 Checks / Reporting (Page 9\)**

* **Date & User Filters:** Filter checks by "Date from", "Date to", and a specific "User" dropdown.  
* **Summary Table:** Displays a list of users and their "Total amount" spent within the selected criteria.  
* **Detailed Breakdown:** Selecting a user expands to show their specific orders (Order Date, Amount).  
* **Item Breakdown:** Expanding a specific order shows the exact items and quantities purchased.

## **5\. Technical Considerations for PHP Implementation**

* **Database (MySQL):**  
  * **Users Table:** id, name, email, password (hashed), room\_no, ext, profile\_pic, role\_id.  
  * **Products Table:** id, name, price, image, category\_id, is\_available.  
  * **Categories Table:** id, name.  
  * **Orders Table:** id, user\_id, room\_no, notes, total\_amount, status, created\_at.  
  * **Order\_Items Table:** id, order\_id, product\_id, quantity, price\_at\_time\_of\_order.  
  * **Rooms Table** (Optional but recommended for the ComboBox): id, room\_number.  
* **File Handling:** PHP scripts needed to handle secure image uploads for both User Profiles and Products.  
* **Session Management:** PHP $\_SESSION to track logged-in users, roles, and potentially cart states before database insertion.  
* **AJAX/Fetch:** Strongly recommended for the shopping cart (+/- buttons, X button) and order expansion (+ icons) to prevent full page reloads and match modern UI expectations.