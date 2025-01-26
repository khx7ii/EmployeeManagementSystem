# Employee Management System

## Overview
The Employee Management System (EMS) is a platform designed to simplify workforce management. It allows users to log in and access their personal and job-related information. Employees can submit leave requests and manage their assigned tasks, while managers handle leave approvals, evaluate employee performance, and assign tasks. Admins have full control over managing employee, department, and manager records. The system ensures efficient operations.

## **Project Features**  

### **User Authentication:**  
- Secure login for all users (employees, managers, and admins).  
- Employees log in using their ID, while managers and admins use their email.  
- Users can log out and terminate sessions when needed.  

### **Personal and Job Information:**  
- All users can view their personal and job-related details.  

### **Employee-Specific Features:**  
- Submit day-off requests, specifying start date, end date, and reason.  
- Track vacation requests and view current status.  
- View assigned tasks and update task status (To Do, In Progress, Done).  

### **Manager-Specific Features:**  
- Review and respond to employees’ day-off requests (approve/deny).  
- View employee data, generate performance reports, and recommend promotions.  
- Assign tasks to employees with specified start and end dates.  
- Submit absence times for employees in their department.  

### **Admin-Specific Features:**  
- Perform CRUD operations on:  
  - **Employee Records:** Add, delete, update, promote, and transfer employees.  
  - **Department Records:** Add, delete, and update departments.  
  - **Manager Records:** Add, delete, update, and transfer managers.  

## Installation
1. **Clone the Repository:**
   ```bash
    https://github.com/khx7ii/EmployeeManagementSystem.git
   ```

2. **Set Up the Environment:**
   - Install XAMPP or any preferred PHP server.
   - Place the project folder in the `htdocs` directory.

3. **Database Setup:**
   - Import the provided `emp.sql` file into your MySQL server.

4. **Run the Application:**
   - Open your browser and visit `http://localhost/EmployeeManagementSystem`.

## Technologies Used
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL

## Collaborators
This project was developed by the following team members:

- **[Kholod Elhmamsy](https://github.com/khx7ii)**
- **[Eman Deyab](https://github.com/emandeyab)**
- **[Roaa Mohamed](https://github.com/roaa46)**
- **[Heba Salhien](https://github.com/hebasalhien)**
- **[Omnia Alwan](https://github.com/Omnia-Alwan)**
- **[Alsayed Aldkhakhni](https://github.com/Alsayed-Aldkhakhni)**
