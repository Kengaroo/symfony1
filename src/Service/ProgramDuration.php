<?php
namespace App\Service;
use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $duration = 0;
        $dayHour= 24*60;
        $seasons = $program->getSeasons();
        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $duration += $episode->getDuration();
            }
        }
        
        $days = floor($duration/$dayHour);
        $hours = floor($duration%$dayHour/60);
        $minutes = $duration - $days*$dayHour - $hours*60;
        $duration = ($days ? $days . 'jour(s) ' : '') . ($hours ? $hours . 'heure(s) ' : '') . ($minutes ? $minutes . 'minute(s)': '');
                    
        return $duration;
    }
}