<form action="<?php echo FRONT_ROOT."Booking/manageBooking" ?>">
<table>
    <thead>My Booking</thead>
    <tbody>
        <th>Owner</th>
        <th>Pet</th>
        <th>initDate</th>
        <th>endDate</th>
        <th>Init Hour</th>
        <th>End Hour</th>
        <th>Status</th>
        <th>Total price</th>
        <th>-</th>
        <tr>
            <td><?php echo $booking->getOwnerCode(); ?></td>
            <td><?php echo $booking->getPetCode(); ?> </td>
            <td><?php echo $booking->getInitDate(); ?> </td>
            <td><?php echo $booking->getEndDate(); ?> </td>
            <td><?php echo $booking->getInitHour(); ?> </td>
            <td><?php echo $booking->getEndHour(); ?> </td>
            <td><?php echo $booking->getStatus(); ?> </td>
            <td><?php echo $booking->getTotalPrice(); ?> </td>
            <td><input type="radio" name="mngBook" id="confirm" value="confirmed">Confirm</td>
            <td><input type="radio" name="mngBook" id="decline" value="declined">Decline</td> 
        </tr>

        <button type="submit" name="codeBook" value="<?php echo $booking->getBookCode() ?>">Apply Changes</button>
    
        
    </tbody>
</table>
</form>