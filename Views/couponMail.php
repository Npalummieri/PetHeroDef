<div style=" margin: auto;">
<div style="display: inline; text-align: center;">
<h1>COUPON INFORMATION</h1>
<p>Coupon code : [ <?php echo $fullCoup["couponCode"] ; ?> ]</p>
</div>
<table style="margin: auto; width: 100%; border: 2px solid black;">
    <tbody>
        <tr style="text-align: center;">
            <td colspan="12"><h4>WWW.PETHERO.COM</h4></td>
        </tr>

        <tr style="background-color: lightgray;">
            <th colspan="12" style="border : 1px solid black; ">
                <h3 style="margin-top: 1em;">Booking Information</h3>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Init Date</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">End Date</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Total price</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Visits per day</th>
        </tr>
        <tr style="text-align: center;">
            <td style="text-align: center;">
                <?php echo $fullCoup["initDate"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["endDate"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["totalPrice"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["visitPerDay"] ; ?>
            </td>
        
        </tr>

        <tr style=" background-color: lightgray;">
            <th colspan="12" style="border : 1px solid black; ">
                <h3 style="margin-top: 1em;">Pet Information</h3>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Pet Name</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Type Pet</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Breed</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Size</th>
        </tr>
        
        <tr style="text-align: center;">
            <td style="text-align: center;">
                <?php echo $fullCoup["namePet"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["typePet"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["breed"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["size"] ; ?>
            </td>
        </tr>


        <tr style=" background-color: lightgray;">
            <th colspan="12" style="border : 1px solid black; ">
                <h3 style="margin-top: 1em;">Contact information</h3>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Owner name</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Owner Email</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Keeper name</th>
            <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Keeper Email</th>
        </tr>

        <tr style="text-align: center;">
            <td style="text-align: center;">
                <?php echo $fullCoup["ownerName"].$fullCoup["olastname"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["emailOwner"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["kname"].$fullCoup["klastname"] ; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $fullCoup["emailKeeper"] ; ?>
            </td>

        </tr>



        
    </tbody>
    <tfoot style="border: 3px solid black; background-color: lightgray; text-align: center;">

        <tr>
            <td colspan="12">For any inquire contact support at <a href="">www.pethero.com/supportCli</a> - Ignore this mail if you don't recognize where this come from</td>
        </tr>


    </tfoot>
</table>
</div>

