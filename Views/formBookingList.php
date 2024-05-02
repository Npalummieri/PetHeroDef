<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6  rounded text-white m-4 p-4" style="background-color: #110257;">
            <form action="<?php echo FRONT_ROOT . "Booking/getMyBookings" ?>" method="POST" id="SearchFormBookings">
                <h3 class="text-center mb-4">Look for your bookings by dates:</h3>
                <div class="form-group">
                    <label for="InitDate">Initial date :</label>
                    
                    <input type="date" name="initDate" class="form-control mb-3" id="InitDate" placeholder="Start Date" value="<?php echo isset($initDate) ? htmlentities($initDate) : '' ?>">
                    <label for="EndDate">End date :</label>
                    <input type="date" name="endDate" class="form-control mb-3" id="EndDate" placeholder="End Date" value="<?php echo isset($endDate) ? htmlentities($endDate) : '' ?>">
                    <label for="Status">Status :</label>
                    <?php $status =  isset($status) ? htmlentities($status) : '' ?>
                    <select name="status" id="Status" class="form-control mb-3" >
                        <option value="">Select type</option>
                        <option value="confirmed" <?php if($status == 'confirmed'){ ?> selected = 'true' <?php } ?>>confirmed</option>
                        <option value="pending" <?php if($status == 'pending'){ ?> selected = 'true' <?php } ?>>pending</option>
                        <option value="finished" <?php if($status == 'finished'){ ?> selected = 'true' <?php } ?>>finished</option>
                        <option value="rejected" <?php if($status == 'rejected'){ ?> selected = 'true' <?php } ?>>rejected</option>
                        <option value="cancelled" <?php if($status == 'cancelled'){ ?> selected = 'true' <?php } ?>>cancelled</option>
                        <option value="paidup" <?php if($status == 'paidup'){ ?> selected = 'true' <?php } ?>>paidup</option>
                    </select>
                </div>
                <div id="results"></div>

                <div class="text-end">
                    <button type="submit" id="FilterButton" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
