<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6  rounded text-white m-4 p-4" style="background-color: #110257;">
            <form action="<?php echo FRONT_ROOT . "Booking/getMyBookings" ?>" method="POST" id="SearchFormBookings">
                <h3 class="text-center mb-4">Filtra tus reservas :</h3>
                <div class="form-group">
                    <label for="InitDate">Desde :</label>
                    
                    <input type="date" name="initDate" class="form-control mb-3" id="InitDate" placeholder="Desde" value="<?php echo isset($initDate) ? htmlentities($initDate) : '' ?>">
                    <label for="EndDate">Hasta :</label>
                    <input type="date" name="endDate" class="form-control mb-3" id="EndDate" placeholder="Hasta" value="<?php echo isset($endDate) ? htmlentities($endDate) : '' ?>">
                    <label for="Status">Estado :</label>
                    <?php $status =  isset($status) ? htmlentities($status) : '' ?>
                    <select name="status" id="Status" class="form-control mb-3" >
                        <option value="">Seleccione tipo</option>
                        <option value="confirmed" <?php if($status == 'confirmed'){ ?> selected = 'true' <?php } ?>>Confirmado</option>
                        <option value="pending" <?php if($status == 'pending'){ ?> selected = 'true' <?php } ?>>Pendiente</option>
                        <option value="finished" <?php if($status == 'finished'){ ?> selected = 'true' <?php } ?>>Finalizado</option>
                        <option value="rejected" <?php if($status == 'rejected'){ ?> selected = 'true' <?php } ?>>Rechazado</option>
                        <option value="cancelled" <?php if($status == 'cancelled'){ ?> selected = 'true' <?php } ?>>Cancelado</option>
                        <option value="paidup" <?php if($status == 'paidup'){ ?> selected = 'true' <?php } ?>>Pagado</option>
                    </select>
                </div>
                <div id="results"></div>

                <div class="text-end">
                    <button type="submit" id="FilterButton" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
