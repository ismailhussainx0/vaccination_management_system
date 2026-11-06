<?php

include "../../config/db_connection.php";
include "../admin_auth.php";

$selectAppointments = "SELECT 

                    appointments.appointment_id,
                    child.child_name,
                    parents.name AS parent_name,
                    hospital.hospital_name,
                    vaccines.vaccine_name,
                    appointments.appointment_date,
                    appointments.appointment_time,
                    appointments.appointment_status

                    FROM appointments
                    
                    JOIN child
                    ON appointments.child_id = child.child_id
                    
                    JOIN parents
                    ON child.parent_id = parents.parent_id
                    
                    JOIN hospital
                    ON appointments.hospital_id = hospital.hospital_id

                    JOIN vaccines
                    ON appointments.vaccine_id = vaccines.vaccine_id
                    
                    WHERE appointments.appointment_status = 'Pending'
                    ";

$result = mysqli_query($connect, $selectAppointments);

if(!$result){
    die(mysqli_error($connect));
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="CSS/manage_appointments.css">
</head>
<body>

    <div class="container">

        <div class="page-header">
            <div>
                <h1>Manage Appointments</h1>
                <p>Review and manage pending appointment requests.</p>
            </div>
            <span class="status-badge">Pending Requests</span>
        </div>

        <div class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Child Name</th>
                        <th>Parent Name</th>
                        <th>Hospital</th>
                        <th>Vaccine</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if(mysqli_num_rows($result) > 0): ?>

                        <?php while($row = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?= htmlspecialchars($row["appointment_id"]) ?></td>

                                <td><?= htmlspecialchars($row["child_name"]) ?></td>

                                <td><?= htmlspecialchars($row["parent_name"]) ?></td>

                                <td><?= htmlspecialchars($row["hospital_name"]) ?></td>

                                <td><?= htmlspecialchars($row["vaccine_name"]) ?></td>

                                <td><?= htmlspecialchars($row["appointment_date"]) ?></td>

                                <td><?= htmlspecialchars($row["appointment_time"]) ?></td>

                                <td>
                                    <span class="pending">
                                        <?= htmlspecialchars($row["appointment_status"]) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="actions">

                                        <form action="update_appointment_status.php" method="POST">
                                            <input type="hidden" name="appointment_id"
                                                value="<?= htmlspecialchars($row["appointment_id"]) ?>">

                                            <input type="hidden" name="status" value="Confirmed">

                                            <button type="submit" class="approve-btn">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="update_appointment_status.php" method="POST">
                                            <input type="hidden" name="appointment_id"
                                                value="<?= htmlspecialchars($row["appointment_id"]) ?>">

                                            <input type="hidden" name="status" value="Cancelled">

                                            <button type="submit" class="reject-btn">
                                                Reject
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="no-data">
                                No pending appointments found.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>

</body>
</html>