<?php 
include('./connection.php');
if(isset($_POST["query"])){  
    $output = '';  
    $query = "SELECT * FROM users WHERE name LIKE '%".$_POST["query"]."%'";  
    $result = mysqli_query($conn, $query);  
    $output = '<ul class="list-unstyled">';  
    
    if(mysqli_num_rows($result) > 0){  
        while($row = mysqli_fetch_array($result)){ 
            $output .= '<tr>';
            $output .= '<td>'.$row["id"].'</td>';
            $output .= '<td>'.$row["name"].'</td>';
            $output .= '<td>'.$row["email"].'</td>';
            $output .= '<td>'.$row["phone"].'</td>';
            $output .= '<td><img src="../image/users/'.$row["image"].'" class="img-thumbnail" width="70" height="30" /></td>';
            $output .= '<td>'.$row["phone"].'</td>';
            $output .= '<td><a href="#viewEmployeeModal" class="btn btn-success btn-sm" data-toggle="modal" onclick=viewEmployee("' .$row["id"].'")>View</a></td>';
            $output .= '<td><a href="#deleteEmployeeModal" class="btn btn-danger  btn-sm" data-toggle="modal" onclick=$("#delete_id").val("' .$row["id"].'")>Delete</a></td>';
            $output .= '<td><a href="#editEmployeeModal" class="btn btn-primary  btn-sm" data-toggle="modal" onclick=viewEmployee("' .$row["id"]. '")>Edit</a></td>';
            $output .='</tr>'; 
        }  
    }else{  
        $output .= '<td>User Not Found</td>';  
    }  


echo $output;  
} 
?>