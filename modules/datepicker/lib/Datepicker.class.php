<?php
/**
 * The class is an catalog of functions used to generate the Ajax Calendar
 * 
 * @author   David Zeller <zellerda01@gmail.com>
 */
class Datepicker
{
    /**
     * Get the first day of a month
     * monday => 0, sunday => 6
     * 
     * @param mixed $date The date or a time
     */
    public static function getFirstDayOfMonth($date)
    {        
        return date('N', strtotime('1' . date('.m.Y', Datepicker::getTime($date)))) - 2;
    }
    
    /**
     * Get the number of days for the previous month
     * 
     * @param mixed $date The date or a time
     * @return integer the number of days
     */
    public static function getPrevMonthMaxDays($date)
    {
        return date('t', strtotime(date('d', Datepicker::getTime($date)) . '.' . Datepicker::getPrevMonth($date) . '.' .  date('Y', Datepicker::getTime($date))));
    }
    
    /**
     * Get the previous month number
     * 
     * @param mixed $date The date or a time
     * @return integer the month number
     */
    public static function getPrevMonth($date)
    {
        $currentMonth = date('n', Datepicker::getTime($date));
        
        if($currentMonth == 1)
        {
            $lastMonth = 12;
        }
        else
        {
            $lastMonth = $currentMonth - 1;
        }
        
        return $lastMonth;
    }
    
    /**
     * Get the next month number
     * 
     * @param mixed $date The date or a time
     * @return integer the month number
     */
    public static function getNextMonth($date)
    {
        $currentMonth = date('n', Datepicker::getTime($date));
        
        if($currentMonth == 12)
        {
            $nextMonth = 1;
        }
        else
        {
            $nextMonth = $currentMonth + 1;
        }
        
        return $nextMonth;
    }
    
    /**
     * Get the next year if the month is december
     * 
     * @param mixed $date The date or a time
     * @return integer the next year or the current year
     */
    public static function getYearNextMonth($date)
    {
        $currentMonth = date('n', Datepicker::getTime($date));
        
        if($currentMonth == 12)
        {
            $nextYear = date('Y', Datepicker::getTime($date)) + 1;
        }
        else
        {
            $nextYear = date('Y', Datepicker::getTime($date));
        }
        
        return $nextYear;
    }
    
    /**
     * Get the previous year if the month is january
     * 
     * @param mixed $date The date or a time
     * @return integer the previous year or the current year
     */
    public static function getYearPrevMonth($date)
    {
        $currentMonth = date('n', Datepicker::getTime($date));
        
        if($currentMonth == 1)
        {
            $lastYear = date('Y', Datepicker::getTime($date)) - 1;
        }
        else
        {
            $lastYear = date('Y', Datepicker::getTime($date));
        }
        
        return $lastYear;
    }
    
    /**
     * Get the time form a date
     * 
     * @param mixed $date The date or a time
     * @return integer the time from the date
     */
    public static function getTime($date)
    {
        if(!is_numeric($date))
        {
            return $date = strtotime($date);
        }
        else
        {
            return $date;
        }
    }
}