<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendenceapp/database/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

function clearTable($dbo, $tabName)
{
    // Use parameterized queries to avoid SQL injection vulnerabilities.
    $c = "DELETE FROM " . $tabName;
    $s = $dbo->getConnection()->prepare($c); // Use getConnection() here
    try {
        $s->execute();
        echo "<br>Table $tabName cleared successfully.";
    } catch (PDOException $oo) {
        echo "Error clearing table $tabName: " . $oo->getMessage();
    }
}

$dbo = new Database();

// Creating student_details table
$c = "CREATE TABLE IF NOT EXISTS student_details
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) UNIQUE,
    name VARCHAR(50)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>student_details created or already exists.");
} catch (PDOException $o) {
    echo ("<br>student_details not created: " . $o->getMessage());
}

// Creating course_details table
$c = "CREATE TABLE IF NOT EXISTS course_details
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE,
    title VARCHAR(50),
    credit INT
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>course_details created or already exists.");
} catch (PDOException $o) {
    echo ("<br>course_details not created: " . $o->getMessage());
}

// Creating faculty_details table
$c = "CREATE TABLE IF NOT EXISTS faculty_details
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(20) UNIQUE,
    name VARCHAR(100),
    password VARCHAR(50)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>faculty_details created or already exists.");
} catch (PDOException $o) {
    echo ("<br>faculty_details not created: " . $o->getMessage());
}

// Creating session_details table
$c = "CREATE TABLE IF NOT EXISTS session_details
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    year INT,
    term VARCHAR(50),
    UNIQUE (year, term)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>session_details created or already exists.");
} catch (PDOException $o) {
    echo ("<br>session_details not created: " . $o->getMessage());
}

// Creating course_registration table
$c = "CREATE TABLE IF NOT EXISTS course_registration
(
    student_id INT,
    course_id INT,
    session_id INT,
    PRIMARY KEY (student_id, course_id, session_id)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>course_registration created or already exists.");
} catch (PDOException $o) {
    echo ("<br>course_registration not created: " . $o->getMessage());
}

// Creating course_allotment table
$c = "CREATE TABLE IF NOT EXISTS course_allotment
(
    faculty_id INT,
    course_id INT,
    session_id INT,
    PRIMARY KEY (faculty_id, course_id, session_id)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>course_allotment created or already exists.");
} catch (PDOException $o) {
    echo ("<br>course_allotment not created: " . $o->getMessage());
}

// Creating attendance_details table
$c = "CREATE TABLE IF NOT EXISTS attendance_details
(
    faculty_id INT,
    course_id INT,
    session_id INT,
    student_id INT,
    on_date DATE,
    status VARCHAR(10),
    PRIMARY KEY (faculty_id, course_id, session_id, student_id, on_date)
)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>attendance_details created or already exists.");
} catch (PDOException $o) {
    echo ("<br>attendance_details not created: " . $o->getMessage());
}

// Inserting student_details
$c = "INSERT IGNORE INTO student_details (roll_no, name) VALUES 
('123B1D001', 'Devraj Bafna'),
('123B1D002', 'Adwait Kamble'),
('123B1D003', 'Vardhan Khinvasara'),
('123B1D004', 'Kaiwalya Ladkhedkar'),
('123B1D005', 'Anuj Ahire'),
('123B1D006', 'Samir Babar'),
('123B1D007', 'Prasad Bange'),
('123B1D008', 'Shreya Bhagat'),
('123B1D009', 'Vishwajeet Bhamre'),
('123B1D010', 'Prathamesh Bhangari'),
('123B1D011', 'Aarush Borkar'),
('123B1D012', 'Shreyash Chaudhari'),
('123B1D013', 'Aniket Damedhar'),  
('123B1D014', 'Vijay Dhame'),  
('123B1D015', 'Sushant Didwagh'),  
('123B1D016', 'Tanaya Gaikwad'),  
('123B1D017', 'Virendra Gaikwad'),  
('123B1D018', 'Pratik Ghavate'),  
('123B1D019', 'Digvijay Gund'),  
('123B1D020', 'Amrita Iyer'),  
('123B1D021', 'Joel Emmanuel'),  
('123B1D022', 'Dhanashri Joshi'),  
('123B1D023', 'Shivam Kasodekar'),  
('123B1D024', 'Rajvardhan Khartode'),  
('123B1D025', 'Prathmesh Mane'),
('123B1D026', 'Anushka Misal'),  
('123B1D027', 'Advait Nathe'),  
('123B1D028', 'Pankaj Gill'),  
('123B1D029', 'Ajim Pathan'),  
('123B1D030', 'Laxman Patil'),  
('123B1D031', 'Mihir Patil'),  
('123B1D032', 'Yadnesh Patil'),  
('123B1D033', 'Shashwati Pawar'),  
('123B1D034', 'Tushar Pawar'),  
('123B1D035', 'Prem Chaudhari'),  
('123B1D036', 'Pritesh Bagul'),  
('123B1D037', 'Dhruv Pujari'),  
('123B1D038', 'Nikhil Puppalwar'),  
('123B1D039', 'Atharv Sakhare'),  
('123B1D040', 'Shivam Salve'),  
('123B1D041', 'Sarthak Bagde'),  
('123B1D042', 'Yuvraj Shembale'),  
('123B1D043', 'Shruti Dhupad'),  
('123B1D044', 'Ishwari Shukla'),  
('123B1D045', 'Ishwar Sonawane'),  
('123B1D046', 'Pratiksha Talole'),  
('123B1D047', 'Tanyya Balwir'),  
('123B1D048', 'Tushar Nagwani'),  
('123B1D049', 'Keshav Valvi'),  
('123B1D050', 'Hiral Vende'),  
('123B1D051', 'Siddhesh Sarphale'),  
('123B1D052', 'Shivanee Surajiwale'),  
('123B1D053', 'Adhya Bhagat'),  
('123B1D054', 'Abhay Bormale'),  
('123B1D055', 'Onkar Chand'),  
('123B1D056', 'Atharva Desai'),  
('123B1D057', 'Janvee Ghadge'),  
('123B1D058', 'Yadnesh Neel'),  
('123B1D059', 'Nihar Salvi'),  
('123B1D061', 'Sakshi Patil'),  
('123B1D062', 'Mihik Shah'),  
('123B1D063', 'Suhani Thakare'),  
('123B1D064', 'Atharva Zope'),  
('123B1D065', 'Sagar Aradhye'),  
('123B1D066', 'Tanishka Kadam'),  
('123B1D067', 'Pooja Chile'),  
('123B1D068', 'Sanika Dethe'),  
('123B1D069', 'Sakshi Sankaye'),  
('123B1D070', 'Pallavi More'),  
('123B1D071', 'Siddharth Patil'),  
('123B1D072', 'Shivam Patil'),  
('123B1D073', 'Shivam Shinde'),  
('123B1D074', 'Siddharth Shinde'),  
('123B1D075', 'Shivam Thakur'),  
('123B1D076', 'Shivam Waghmare'),  
('123B1D077', 'Shivam Yadav'),  
('123B1D078', 'Raj Dalal'),  
('123B1D079', 'Mayur Katole'),
('123B1D080', 'Harshvardhan Patil')";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>student_details inserted");
} catch (PDOException $o) {
    echo ("<br>Duplicate entry: " . $o->getMessage());
}

// Inserting faculty_details
$c = "INSERT IGNORE INTO faculty_details (user_name, password, name) VALUES
('GD','123','Ganesh Deshmukh'),
('MS','123','Minal Shahakar'),
('SP','123','Sandeep Patil'),
('AV','123','Ashwini Vaze'),
('BD','123','Brijesh Deshmukh'),
('RS','123','Rucha Shinde')";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>faculty_details inserted");
} catch (PDOException $o) {
    echo ("<br>Duplicate entry: " . $o->getMessage());
}

// Inserting session_details
$c = "INSERT IGNORE INTO session_details (year, term) VALUES
(2025, 'EVEN SEMESTER'),
(2025, 'ODD SEMESTER')";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>session_details inserted");
} catch (PDOException $o) {
    echo ("<br>Duplicate entry: " . $o->getMessage());
}

// Inserting course_details
$c = "INSERT IGNORE INTO course_details (title, code, credit) VALUES
('Database Management System', 'CO321', 2),
('Advanced Data Structure', 'CO215', 3),
('Constitution Of India', 'CS112', 4),
('Computational Techniques', 'CS670', 4),
('Business Finance', 'CO432', 3),
('Microprocessor Architecture', 'CS673', 1)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
try {
    $s->execute();
    echo ("<br>course_details inserted");
} catch (PDOException $o) {
    echo ("<br>Duplicate entry: " . $o->getMessage());
}

// Clearing and inserting course_registration
clearTable($dbo, "course_registration");
$c = "INSERT INTO course_registration (student_id, course_id, session_id) VALUES (:sid, :cid, :sessid)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
for ($i = 1; $i <= 24; $i++) {
    for ($j = 0; $j < 3; $j++) {
        try {
            $s->execute([":sid" => $i, ":cid" => rand(1, 6), ":sessid" => 1]);
            $s->execute([":sid" => $i, ":cid" => rand(1, 6), ":sessid" => 2]);
        } catch (PDOException $pe) {}
    }
}

// Clearing and inserting course_allotment
clearTable($dbo, "course_allotment");
$c = "INSERT INTO course_allotment (faculty_id, course_id, session_id) VALUES (:fid, :cid, :sessid)";
$s = $dbo->getConnection()->prepare($c); // Use getConnection() here
for ($i = 1; $i <= 6; $i++) {
    for ($j = 0; $j < 2; $j++) {
        try {
            $s->execute([":fid" => $i, ":cid" => rand(1, 6), ":sessid" => 1]);
            $s->execute([":fid" => $i, ":cid" => rand(1, 6), ":sessid" => 2]);
        } catch (PDOException $pe) {
            // Handle any potential exceptions here, if necessary
        }
    }
}
?>
