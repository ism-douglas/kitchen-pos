# Kitchen Meal POS System

A modern web-based kitchen meal tracking system built with PHP, MySQL, and Bootstrap. Designed for staff, interns, and guests to efficiently record meal pickups (Breakfast, Tea Break, Lunch, Supper) with a smooth, POS-style interface and searchable dropdowns.

---

## Features

* Responsive, modern UI with Bootstrap 5 and icons for a clean POS look
* Four meal categories: Breakfast, Tea Break, Lunch, Supper
* Searchable, paginated dropdowns to select people who haven't taken a specific meal today
* Real-time validation to prevent duplicate meal pickups per day
* AJAX-powered form submissions with user-friendly alerts
* Backend API built with secure PDO prepared statements and JSON responses
* Easy to deploy and customize for different kitchen environments

---

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/ism-douglas/kitchen-pos.git
   cd kitchen-meal-pos
   ```

2. Import the provided SQL schema into your MySQL server:

   ```sql
   CREATE DATABASE kitchen_system;
   USE kitchen_system;
   ```

   
   -- Tables
    meals

    id	int(11)	NO	PRI	NULL	auto_increment	
    person_id	int(11)	NO	MUL	NULL		
    meal_type	enum('breakfast','tea','lunch','supper')	NO		NULL		
    served_at	datetime	NO		NULL		

    people
    
    id	int(11)	NO	PRI	NULL	auto_increment	
    full_name	varchar(100)	NO		NULL		
    category	enum('staff','intern','guest')	NO		NULL	

3. Configure database credentials in `db.php`.

4. Serve the project with PHP and access `index.php` in your browser.

---

## Usage

* Click on a meal card to open the modal form.
* Search or select the person picking the meal.
* Confirm to record the meal in the system.
* The system prevents recording the same meal twice for the same person on the same day.

---

## Technologies Used

* PHP 7+ with PDO
* MySQL / MariaDB
* Bootstrap 5
* jQuery & Select2
* AJAX for asynchronous data fetching and submission

---

## Contributing

Contributions are welcome! Please fork the repo and submit a pull request with improvements or bug fixes.


---

## Contact

For questions or support, please open an issue or contact ism.douglas@gmail.com.

---