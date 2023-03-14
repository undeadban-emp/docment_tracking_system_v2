<?php

function secondsToTime($seconds_time)
{
    if($seconds_time != ''){

        // dd($seconds_time);
        if ($seconds_time < 24 * 60 * 60) {
            // return "Duration: ".gmdate('H:i:s', $seconds_time);
            if($seconds_time  <= 59){
                return "Duration: ".gmdate('s', $seconds_time).'s';
            }else if($seconds_time >= 60 && $seconds_time <= 3600){
                return "Duration: ".gmdate('i', $seconds_time).'m:'.gmdate('s', $seconds_time).'s';
            }else{
                return "Duration: ".gmdate('H', $seconds_time).'h:'.gmdate('i', $seconds_time).'m:'.gmdate('s', $seconds_time).'s';
            }
        } else {
            // $hours = floor($seconds_time / 3600);
            // $minutes = floor(($seconds_time - $hours * 3600) / 60);
            // $seconds = floor($seconds_time - ($hours * 3600) - ($minutes * 60));
            return "Duration:"."00:00:00";
        }
    }
}
