# PHP Currency Converter

A clean, framework‑free PHP app that uses **cURL** to fetch live FX rates and **MySQLi (OO)** to log conversions. Built as a portfolio piece to demonstrate API consumption, OOP in PHP, and database integration.

---

## 🚀 Demo

![Converter in action](./screenshots/convert-demo.gif)

---

## 🛠️ Tech Stack

- **PHP 8+** (pure PHP)  
- **HTTP client:** cURL  
- **Database:** MySQL (MySQLi, object‑oriented)  
- **Frontend:** HTML, CSS  

---

## 💾 Installation

1. **Clone** the repo  
2. **Copy** `config.example.php` → `config.php` and fill in your API key & DB creds  
3. **Import** the schema:
   ```sql
   SOURCE sql/conversion_logs.sql;
