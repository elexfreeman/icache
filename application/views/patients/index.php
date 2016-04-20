<div class="container">
<h2>Пациенты</h2>


<div class="panel panel-default">
    <div class="panel-body">
        <?php print_r($test); ?>
            <table class="table table-hover">
                <?php foreach ($patients as $patient) {
                    ?>
                    <tr>
                        <td><?php echo mb_convert_encoding($patient['FirstName'], "UTF-8", "Windows-1251"); ?></td>
                        <td><?php echo mb_convert_encoding($patient['MiddleName'], "UTF-8", "Windows-1251"); ?></td>
                        <td><?php echo mb_convert_encoding($patient['LastName'], "UTF-8", "Windows-1251"); ?></td>
                        <td><?php  echo $patient['BirthDate']." ";?> </td>
                        <td><?php echo mb_convert_encoding($patient['Enp'], "UTF-8", "Windows-1251"); ?></td>
                        <td><?php echo mb_convert_encoding($patient['Sex'], "UTF-8", "Windows-1251"); ?></td>
                        <td><?php echo mb_convert_encoding($patient['Snils'], "UTF-8", "Windows-1251"); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>