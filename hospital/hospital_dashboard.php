<?php

include "../config/db_connection.php";
include "hospital_auth.php";

try{

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
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>
                <td><?php echo $row["appointment_id"]; ?></td>

                <td><?php echo htmlspecialchars($row["child_name"]); ?></td>

                <td><?php echo htmlspecialchars($row["vaccine_name"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_time"]); ?></td>

                <td><?php echo htmlspecialchars($row["appointment_status"]); ?></td>
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





<?php



?>







