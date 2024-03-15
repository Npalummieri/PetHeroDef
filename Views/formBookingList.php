<div class="row d-flex justify-content-center mx-auto w-50 mt-3">
    <form action="<?php echo FRONT_ROOT . "Booking/getMyBookings" ?>" method="POST" id="SearchFormBookings" >
        <h3 class="align-self-center">Look for your bookings by dates :</h3>

        <div class="col-sm-3 col-md-6 col-lg-12">
            <input type="date" name="initDate" class="form-control mb-2" id="InitDate"  >
            <input type="date" name="endDate"class="form-control mb-2"  id="EndDate"  >

            <select name="status" id="Status" class="form-control mb-2" required >
                <option value="">Select type</option>
                <option value="confirmed">confirmed</option>
                <option value="pending">pending</option>
                <option value="finished">finished</option>
                <option value="cancelled">cancelled</option>
                <option value="paidup">paidup</option>
            </select>
        </div>

        <div id="results"></div>
        <!-- Sistema de ordenamiento Puntuacion/Precio,quiza deberia ir en form aparte  <input type="text">-->

        <!-- Sistema de orden ,asc-desc <input type="text">-->

        <!-- Maximo a pagar x hora  <input type="number" name="" id="">-->

        <div class="d-flex justify-content-end">
        <button type="submit" id="FilterButton" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>