<?php
include "parent_auth.php";
include "../confing/db_connection.php";


$shwoAppointmentsSql = "SELECT 

                        

                        FROM appointments
                        JOIN child
                        ON appointments.child_id = child.child_id
                        
                        JOIN vaccines
                        ON appointments.vaccine_id = vaccines.vaccine_id
                        
                        JOIN hospital
                        ON appointments.hospital_id = hospital.hospital_id"












?>