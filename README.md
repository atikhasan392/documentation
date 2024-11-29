## Project: PHP-based Dynamic Website

This project demonstrates how to build a dynamic website using PHP and MySQL. It includes various functionalities such as displaying database records, ordering data, form handling, image uploads, and more.

---

### **Features**
1. **Database Connection**  
   Establish a connection to a MySQL database using PHP.

2. **Retrieve and Display Data**  
   Fetch data from the database with ordering (`ASC` or `DESC`).

3. **Date Formatting and Time Difference**  
   Display formatted dates and calculate time differences (e.g., "2 days ago").

4. **Data Entry via HTML Forms**  
   Submit data such as names, emails, and phone numbers to the database.

5. **Image Uploads**  
   Handle file uploads (images) and save the file path to the database.

6. **Dynamic Menus**  
   Generate navigation menus dynamically from the database.

7. **JOIN Queries**  
   Demonstrate complex queries using SQL `JOIN` to combine data from multiple tables.

8. **Row Count**  
   Display the total number of rows in a database table.

9. **Reusable PHP Functions**  
   Example of reusable functions like `getBlogID()` for modular code.

---

### **Project Structure**
- **Database**: `MySQL`
- **Backend**: `PHP`
- **Frontend**: HTML/CSS (dynamic data rendering via PHP)
- **File Uploads**: Supported

---

### **Setup Instructions**
1. **Clone the Repository**  
   ```bash
   git clone https://github.com/atikhasan27000/php-documentation.git
   cd php-documentation
   ```

2. **Database Setup**  
   - Create a MySQL database (e.g., `databesh`).
   - Import the provided SQL file to set up tables and sample data.

3. **Configure Database Connection**  
   Update `mysqli_connect` in the connection block:
   ```php
   $con = mysqli_connect('localhost', 'root', '', 'databesh');
   ```

4. **Run the Project**  
   Place the project in your web server's root directory (e.g., `htdocs` for XAMPP).  
   Access the site in a browser at: `http://localhost/<project-folder>`

---

### **Usage Examples**
1. **Database Connection**
   ```php
   $con = mysqli_connect('localhost', 'root', '', 'databesh');
   if (!$con) {
       die("Connection failed: " . mysqli_connect_error());
   }
   ```

2. **Fetch and Display Records**
   ```php
   $query = "SELECT * FROM mydb ORDER BY addedOn DESC";
   $result = mysqli_query($con, $query);
   while ($row = mysqli_fetch_assoc($result)) {
       echo $row['addedOn'];
   }
   ```

3. **Insert Data via Form**
   ```php
   if (isset($_POST['fast_name'])) {
       $fast_name = $_POST['fast_name'];
       $query = "INSERT INTO mytable (fast_name) VALUES ('$fast_name')";
       mysqli_query($con, $query);
   }
   ```

4. **Upload Images**
   ```php
   $imgname = $_FILES['img']['name'];
   move_uploaded_file($_FILES['img']['tmp_name'], 'images/' . $imgname);
   ```

5. **Dynamic Menu**
   ```php
   $query = "SELECT * FROM menu ORDER BY order ASC";
   $result = mysqli_query($con, $query);
   while ($menu = mysqli_fetch_assoc($result)) {
       echo '<a href="' . $menu['link'] . '">' . $menu['name'] . '</a>';
   }
   ```

---

### **Dependencies**
- **PHP**: Version 7.4 or higher  
- **MySQL**: Version 5.7 or higher  
- **Web Server**: Apache (Recommended: XAMPP/WAMP/LAMP)

---

### **Key Notes**
- **Security Considerations**:  
   - Use prepared statements to prevent SQL injection.
   - Validate and sanitize user input for forms and file uploads.

- **Error Handling**:  
   Add proper error-handling mechanisms to improve robustness.

- **Styling**:  
   Use CSS to enhance the frontend appearance as per project requirements.

---

### **Contact**
For further questions or contributions, please reach out at [https://atikhasan.com] [atikhasan2700@gmail.com].
