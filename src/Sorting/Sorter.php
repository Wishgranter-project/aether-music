<?php 
namespace AdinanCenci\AetherMusic;

class Sorter 
{
    protected Comparer $comparer;

    public function __construct(Description $description) 
    {
        $this->comparer = new Comparer($description);
    }

    public function sort(Resource $resource1, Resource $resource2) : int 
    {
        $score1 = 0;
        $score2 = 0;

        if ($resource1->id == $resource2->id) {
            return $score1 = $score2 = 0;
        }

        $score1 = $this->comparer->getLikenessScore($resource1);
        $score2 = $this->comparer->getLikenessScore($resource2);

        if ($score1 == $score2) {
            return 0;
        }

        return $score1 > $score2
            ? -1
            :  1;
    }
}
