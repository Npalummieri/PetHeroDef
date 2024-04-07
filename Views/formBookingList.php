<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6  rounded text-white m-4 p-4" style="background-color: #110257;">
            <form action="<?php echo FRONT_ROOT . "Booking/getMyBookings" ?>" method="POST" id="SearchFormBookings">
                <h3 class="text-center mb-4">Look for your bookings by dates:</h3>
                <div class="form-group">
                    <input type="date" name="initDate" class="form-control mb-3" id="InitDate" placeholder="Start Date">
                    <input type="date" name="endDate" class="form-control mb-3" id="EndDate" placeholder="End Date">
                    <select name="status" id="Status" class="form-control mb-3" required>
                        <option value="">Select type</option>
                        <option value="confirmed">confirmed</option>
                        <option value="pending">pending</option>
                        <option value="finished">finished</option>
                        <option value="rejected">rejected</option>
                        <option value="cancelled">cancelled</option>
                        <option value="paidup">paidup</option>
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
