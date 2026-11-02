<?php

include "../config/db_connection.php";
include "hospital_auth.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["appointment_id"], $_POST["appointment_status"])){

    echo "Ismail";
        $hospitalId = $_SESSION["hospitalId"];
        $updatedStatus = $_POST["appointment_status"];
        $appointmentId = $_POST["appointment_id"];


        if($updatedStatus === "Pending" || $updatedStatus === "Confirmed" || $updatedStatus === "Cancelled" || $updatedStatus === "Completed"){

            
            $updateSql = "UPDATE appointments 
            SET appointment_status = ? 
            WHERE appointment_id = ? AND hospital_id = ?";

            $updateStmt = mysqli_prepare($connect, $updateSql);
            $updateStmt->bind_param("sii", $updatedStatus, $appointmentId, $hospitalId);
            $updateResult = $updateStmt->execute();

            if($updateResult){
                
                header("Location: hospital_dashboard.php");
                exit;
                
            }
    
        }else{

            echo "Wrong Status Chosen";

        }



        
    }else{
        echo "Values is not set Properly";
    }


    // VACCINATION_RECORD INSERTION ON CONDITION
    if($updatedStatus === "Completed"){

        $vaccinationDate = date("Y-m-d");
        $vaccinationStatus = "Vaccinated";

        $vaccinationRecordSql = "INSERT INTO vaccination_record 
        (appointment_id, vaccination_status, vaccination_date)
        VALUES (?,?,?)";

        $recordStmt = mysqli_prepare($connect, $vaccinationRecordSql);
        $recordStmt->bind_param("iss", $appointmentId, $vaccinationStatus, $vaccinationDate);
        $recordStmt->execute();

    }



}

try{

    // APPOINTMENT TABLE SHOW QUERY 
    $appointmentSql = "SELECT

            appointments.appointment_id,
            child.child_name,
            vaccines.vaccine_name,
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.appointment_status

            FROM appointments

            JOIN child
            ON appointments.child_id = child.child_id

            JOIN vaccines
            ON appointments.vaccine_id = vaccines.vaccine_id

            WHERE appointments.hospital_id = ?";


        $appointmentStmt = mysqli_prepare($connect, $appointmentSql);
        $appointmentStmt->bind_param("i", $_SESSION["hospitalId"]);
        $appointmentStmt->execute();
        
        $result = $appointmentStmt->get_result();


}catch(mysqli_sql_exception $error){

    echo "Something Went Wrong!";

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard</title>
</head>
<body>

    <h1>Hospital Dashboard</h1>

    <h2>Appointments</h2>

    <?php

        if(mysqli_num_rows($result) > 0){

    ?>

    <table border="1" cellpadding="10">

        <tr>
            <th>Appointment ID</th>
            <th>Child Name</th>
            <th>Vaccine</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>
                <td><?php echo $row["appointment_id"]; ?></td>

                <td><?php echo htmlspecialchars($row["child_name"]); ?></td>

                <td><?php echo htmlspecialchars($row["vaccine_name"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_time"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_status"]); ?></td>

                <td>

                    <form action="" method="post">

                        <input type="hidden" name="appointment_id" value="<?php echo $row["appointment_id"]; ?>">

                        <select name="appointment_status" required>

                            <!-- pending -->
                            <option value="Pending" <?php if($row["appointment_status"] === "Pending") echo "selected"; ?> >
                                Pending
                            </option>


                            <!-- Confirmed Option -->
                            <option value="Confirmed" <?php if($row["appointment_status"] === "Confirmed") echo "selected"; ?>>
                                Confirmed
                            </option>

                                <!-- Confirmed Option -->
                            <option value="Cancelled" <?php if($row["appointment_status"] === "Cancelled") echo "selected"; ?>>
                                Cancelled
                            </option>

                                <!-- Confirmed Option -->
                            <option value="Completed" <?php if($row["appointment_status"] === "Completed") echo "selected"; ?>>
                                Completed
                            </option>

                        </select>

                        <button type="submit" name="update_status">
                            Update
                        </button>

                    </form>

                </td>

            </tr>

        <?php } ?>


    </table>

<?php

    }else{
            echo "No Appointment Found!";  
        }

?>
         

</body>
</html>







