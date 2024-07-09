<div style=" margin: auto;">
    <div style="display: inline; text-align: center;">
        <h1>INFORMACIÓN DE CUPON</h1>
        <p>Codigo de cupón : [ <?php echo $fullCoup["couponCode"]; ?> ]</p>
    </div>
    <table style="margin: auto; width: 100%; border: 2px solid black;">
        <tbody>
            <tr style="text-align: center;">
                <td colspan="12">
                    <h4>WWW.PETHERO.COM</h4>
                </td>
            </tr>

            <tr style="background-color: lightgray;">
                <th colspan="12" style="border : 1px solid black; ">
                    <h3 style="margin-top: 1em;">Información de reserva</h3>
                </th>
            </tr>
            <tr style="text-align: center;">
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Desde</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Hasta</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Precio total</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Visitas por dia</th>
            </tr>
            <tr style="text-align: center;">
                <td style="text-align: center;">
                    <?php echo $fullCoup["initDate"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["endDate"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["totalPrice"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["visitPerDay"]; ?>
                </td>

            </tr>

            <tr style=" background-color: lightgray;">
                <th colspan="12" style="border : 1px solid black; ">
                    <h3 style="margin-top: 1em;">Información de mascota</h3>
                </th>
            </tr>
            <tr style="text-align: center;">
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Nombre</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Tipo</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Raza</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Tamaño</th>
            </tr>

            <tr style="text-align: center;">
                <td style="text-align: center;">
                    <?php echo $fullCoup["namePet"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["typePet"] === "cat" ? "Gato" : "Perro"; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["breed"]; ?>
                </td>
                <td style="text-align: center;">
                <?php if($fullCoup["size"] === "big")
              { echo "Grande";}
              else if($fullCoup["size"] === "medium")
              {echo "Mediano";}
              else {echo "Pequeño";}
              ;?>
                </td>
            </tr>


            <tr style=" background-color: lightgray;">
                <th colspan="12" style="border : 1px solid black; ">
                    <h3 style="margin-top: 1em;">Información de contacto</h3>
                </th>
            </tr>
            <tr style="text-align: center;">
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Nombre dueño</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Email dueño</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Nombre cuidador</th>
                <th style="margin: auto; padding: 1em; text-align: center; font-size: larger;">Email cuidador</th>
            </tr>

            <tr style="text-align: center;">
                <td style="text-align: center;">
                    <?php echo $fullCoup["ownerName"] . $fullCoup["olastname"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["emailOwner"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["kname"] . $fullCoup["klastname"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $fullCoup["emailKeeper"]; ?>
                </td>

            </tr>




        </tbody>
        <tfoot style="border: 3px solid black; background-color: lightgray; text-align: center;">

            <tr>
                <td colspan="12">Por cualquier consulta a <a href="">www.pethero.com/supportCli</a> - Ignorar este email si no reconoce su origen.</td>
            </tr>


        </tfoot>
    </table>
</div>