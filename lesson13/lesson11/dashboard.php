   <?php 


            foreach ($users as $user ) {
            
        ?>
            <tr> 
                <td> <?= $user['id'] ?> </td>
                <td> <?= $user['username'] ?> </td>
                <td> <?= $user['name']  ?> </td> 
                <td> <?= $user['surname']  ?> </td> 
                <td> <?= $user['email']  ?> </td>
                <td> <?= "<a href='delete.php?id=$user[id]'> Delete</a>| <a href='edit.php?id=$user[id]'> Update </a>"?></td>


            </tr>
        
        <?php 


            }


         ?>



     </table>


     <a href="index.php">Add User</a>
    
</body>
</html>