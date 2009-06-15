<?php
/**
* datepicker actions
*
* @author   David Zeller <zellerda01@gmail.com>
*/
class datepickerActions extends sfActions
{
    public function executeDays($request)
    {
        $inputDate = $request->getParameter('date');
        
        // Check the input value and convert it to time
        if($inputDate == '' || is_null($inputDate))
        {
            $inputTime = time();
        }
        else
        {
            $inputTime = strtotime($inputDate);
            
            if($inputTime == '')
            {
                $inputTime = time();
            }
        }
        
        $matrix = array();
        $dayNames = array();
        $matrixTotal = 1;
        
        $firstDayOfMonth =  Datepicker::getFirstDayOfMonth($inputTime);
        
        if($firstDayOfMonth == -1)
        {
            $firstDayOfMonth = 6;
        }
        
        for($i = (Datepicker::getPrevMonthMaxDays($inputTime) - $firstDayOfMonth); $i <= Datepicker::getPrevMonthMaxDays($inputTime); $i++)
        {
            $matrix[strtotime($i.'.'.Datepicker::getPrevMonth($inputTime).'.'.Datepicker::getYearPrevMonth($inputTime))] = $i;
            $matrixTotal++;
        }
        
        for($i = 1; $i <= date('t', $inputTime); $i++)
        {
            $matrix[strtotime($i.'.'.date('m', $inputTime).'.'.date('Y', $inputTime))] = $i;
            $matrixTotal++;
        }
        
        if($matrixTotal != 42)
        {
            for($i = 1; $i <= 43 - $matrixTotal; $i++)
            {
                $matrix[strtotime($i.'.'.Datepicker::getNextMonth($inputTime).'.'.Datepicker::getYearNextMonth($inputTime))] = $i;
            }
        }
        
        foreach(sfDateTimeFormatInfo::getInstance($this->getUser()->getCulture())->getDayNames() as $days)
        {
            $dayNames[] = ucfirst(substr($days, 0, 2));
        }
        
        $this->month_matrix = $matrix;
        $this->time = $inputTime;
        $this->dayNames = $dayNames;
    }
}