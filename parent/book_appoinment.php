<?php
include "parent_auth.php";
include "../config/db_connection.php";


$parentId = $_SESSION["parent_id"];

// SELECT CHILD DATA
$childSql = "SELECT child_id, child_name FROM child WHERE parent_id = ?";
$childStmt = mysqli_prepare($connect, $childSql);
$childStmt->bind_param("i", $parentId);
$childStmt->execute();

$childResult = $childStmt->get_result();


// SELECT HOSPITAL DATA
$hospitalSql = "SELECT hospital_id, hospital_name FROM hospital";
$hospitalResult = mysqli_query($connect, $hospitalSql);


// // SELECT VACCINE DATA
$vaccineSql = "SELECT vaccine_id, vaccine_name FROM vaccines";
$vaccineResult = mysqli_query($connect, $vaccineSql);


if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["child_id"], $_POST["hospital_id"], $_POST["vaccine_id"], $_POST["appointment_date"], $_POST["appointment_time"])){

        $childId = $_POST["child_id"];
        $hospitalId = $_POST["hospital_id"];
        $vaccineId = $_POST["vaccine_id"];
        $appointmentDate = $_POST["appointment_date"];
        $appointmentTime = $_POST["appointment_time"];

        if(!empty($childId) && !empty($hospitalId) && !empty($vaccineId) && !empty($appointmentDate) && !empty($appointmentTime)){

            // APPOINTMENT INSERT
            $insertSql = "INSERT INTO appointments
            (child_id, hospital_id, vaccine_id, appointment_date, appointment_time)
            VALUES (?, ?, ?, ?, ?)";

            $insertStmt = mysqli_prepare($connect, $insertSql);
            $insertStmt->bind_param("iiiss", $childId, $hospitalId, $vaccineId, $appointmentDate, $appointmentTime);
            $result = $insertStmt->execute();

            if($result){
                echo "Appointment Booked";
            }




        }







    }else{
        echo "The Values is Not Set Properly";
    }


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<form action="" method="post">

<!-- CHILD DROPDOWN -->
<select name="child_id" required>
    
    <?php
    while($row = mysqli_fetch_assoc($childResult)){
    ?>

    <option value="<?php echo $row["child_id"]?>">
        <?php echo htmlspecialchars($row["child_name"])?> 
    </option>

    <?php
    }
    ?>

</select>


<!-- HOSPITAL DROPDOWN -->
<select name="hospital_id" required>

    <?php
    while ($row = mysqli_fetch_assoc($hospitalResult)) {
    ?>

    <option value="<?php echo $row["hospital_id"]; ?>">
        <?php echo htmlspecialchars($row["hospital_name"]); ?>
    </option>

    <?php } ?>

</select>




<!-- VACCINE DROPDOWN -->
<select name="vaccine_id" required>

    <?php
    while ($row = mysqli_fetch_assoc($vaccineResult)) {
    ?>

    <option value="<?php echo $row["vaccine_id"]; ?>">
        <?php echo htmlspecialchars($row["vaccine_name"]); ?>
    </option>

    <?php } ?>

</select>


<input type="date" name="appointment_date" required>
<input type="time" name="appointment_time" required>


<button type="submit">Book appoinment</button>

</form>


</body>
</html>