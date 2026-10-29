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


            
            // CHECKING CHILD ROW SAHI PARENT KI HA YA NAHI 
            $checkSql = "SELECT child_id FROM child WHERE child_id = ? AND parent_id = ?";
            $checkStmt = mysqli_prepare($connect, $checkSql);
            $checkStmt->bind_param("ii", $childId, $parentId);
            $checkStmt->execute();

            $checkResult = $checkStmt->get_result();

            // CHECKING HOSPITAL ID IS CORRECT OR WRONG
            $checkHospital = "SELECT hospital_id FROM hospital WHERE hospital_id = ?";
            $checkHospitalStmt = mysqli_prepare($connect, $checkHospital);
            $checkHospitalStmt->bind_param("i", $hospitalId);
            $checkHospitalStmt->execute();

            $checkHospitalResult = $checkHospitalStmt->get_result();

            // CHECKING VACCINE ID IS CORRECT OR WRONG
            $checkVaccine = "SELECT vaccine_id FROM vaccines WHERE vaccine_id = ?";
            $checkVaccineStmt = mysqli_prepare($connect, $checkVaccine);
            $checkVaccineStmt->bind_param("i", $vaccineId);
            $checkVaccineStmt->execute();

            $checkVaccineResult = $checkVaccineStmt->get_result();

            if(mysqli_num_rows($checkResult) > 0 && mysqli_num_rows($checkHospitalResult) > 0 && mysqli_num_rows($checkVaccineResult) > 0){

                try{

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

                    $insertStmt->close();

                } catch (mysqli_sql_exception $e) {
                    echo "Unable to book appointment. Please try again.";
                    }
                
                
            }else{
                echo "Invalid child, hospital, or vaccine selection.";
            }




        }else{
            echo "Please Enter all Fields";
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