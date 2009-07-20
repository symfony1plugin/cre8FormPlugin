<?php use_helper('Date'); ?>
<table class="title-container" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="arrow-left" valign="middle" onclick="dp_prevMonth('<?php echo date('d.m.Y', strtotime(date('d', $time).'.'.Datepicker::getPrevMonth($time).'.'.Datepicker::getYearPrevMonth($time))); ?>')"></td>
        <td class="title" valign="middle">
            <?php echo ucfirst(format_date($time, 'MMMM, yyyy', $sf_user->getCulture())); ?>
        </td>
        <td class="arrow-right" valign="middle" onclick="dp_nextMonth('<?php echo date('d.m.Y', strtotime(date('d', $time).'.'.Datepicker::getNextMonth($time).'.'.Datepicker::getYearNextMonth($time))); ?>')"></td>
    </tr>
</table>
<table class="weekdays-container" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td><?php echo $dayNames[1]; ?></td>
        <td><?php echo $dayNames[2]; ?></td>
        <td><?php echo $dayNames[3]; ?></td>
        <td><?php echo $dayNames[4]; ?></td>
        <td><?php echo $dayNames[5]; ?></td>
        <td><?php echo $dayNames[6]; ?></td>
        <td><?php echo $dayNames[0]; ?></td>
    </tr>
</table>
<table class="days-container" cellpadding="0" cellspacing="0" align="center">
    <tr>
    <?php $line = 0; ?>
    <?php foreach($month_matrix as $date => $num): ?>
        <?php if(date('m', $time) != date('m', $date)): ?>
            <?php echo '<td onclick="dp_updateField(\'' . date(sfConfig::get('app_datepicker_output_format'), $date) . '\')" class="inactive">' . $num . '</td>'; ?>
        <?php elseif(date('d.m.Y', $time) == date('d.m.Y', $date)): ?>
            <?php echo '<td onclick="dp_updateField(\'' . date(sfConfig::get('app_datepicker_output_format'), $date) . '\')" class="selected">' . $num . '</td>'; ?>
        <?php else: ?>
            <?php echo '<td onclick="dp_updateField(\'' . date(sfConfig::get('app_datepicker_output_format'), $date) . '\')">' . $num . '</td>'; ?>
        <?php endif; ?>
        <?php if($line != 6): ?>
            <?php $line++; ?>
        <?php else: ?>
            <?php $line = 0; ?>
            <?php echo '</tr><tr>'; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    </tr>
</table>