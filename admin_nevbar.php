
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    
<style>
body {
            background-color: #f4f7f8;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background:#1a1a1a;
            color: white;
            padding: 20px;
            font-size: 2rem;
            position: fixed;
    width: 100%; 
    top: 0; 
    z-index: 1000; 
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); 
        }

        .sidebar {
            background-color: #1a1a1a;
            position: fixed;
            padding: 20px;
            height: 100vh;
            width: 250px;
            color: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
            top: 70px;
        }

        .sidebar h4 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
        }

        .sidebar h4:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #007bff, #00d1ff);
   
           bottom: -10px;
            left: 0;
        }

        .sidebar a.nav-link {
            color: #dcdcdc;
            font-size: 1rem;
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            border-radius: 4px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a.nav-link:hover, .sidebar a.nav-link.active {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: #fff;
        }

        .content {
            margin-left: 270px;
            padding: 40px;
            padding-top: 110px;

        }

        .content h1 {
            font-size: 2.5rem;
            color: #1a73e8;
            text-align: center;
            margin-bottom: 20px;
        }</style>
<div class="header">
        Admin Panel
    </div>

    <div class="sidebar">
        <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
        <a href="user_profile.php" class="nav-link">User Management</a>
        <a href="vacancies.php" class="nav-link">Vacancy Management</a>
        <a href="applications.php" class="nav-link">Application Management</a>
        <a href="events.php" class="nav-link">Event Management</a>
        <a href="event_registrations.php" class="nav-link">Event Registration</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>
