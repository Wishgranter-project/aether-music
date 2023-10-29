<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Source\Resource;
use AdinanCenci\AetherMusic\Helper\Text;

/**
 * Compares the description of a music against online resources.
 */
class Comparer 
{
    /**
     * @var AdinanCenci\AetherMusic\Description
     *   The description of a music.
     */
    protected Description $description;

    /**
     * @var string[]
     *   An array of strings that we want to avoid in a resource, they will
     *   contribuite negatively against the score.
     */
    protected array $undesirables;

    /**
     * @param AdinanCenci\AetherMusic\Description $description
     *   The description of a music.
     * @param string[] $undesirables
     *   An array of strings that we rather not see in a resource.
     */
    public function __construct(
        Description $description, 
        array $undesirables = ['cover', 'acoustic', 'live', 'demo', 'demotape', 'remixed', 'remix']
    ) 
    {
        $this->description  = $description;
        $this->undesirables = $this->compileUndesirables($undesirables);
    }

    /**
     * Returns a LikenessScore of how much a Resource matches our Description.
     *
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return AdinanCenci\AetherMusic\Sorting\LikenessScore
     *   A score of how closely $resource matches the $description.
     */
    public function getLikenessScore(Resource $resource) : LikenessScore 
    {
        $score = new LikenessScore();

        //                weight * score
        $score->setParameters([
            'title'        => 10 * $this->scoreOnTitle($resource),
            'artist'       => 10 * $this->scoreOnArtist($resource),
            'soundtrack'   => 10 * $this->scoreOnSoundtrack($resource),
            'undesirables' =>  1 * $this->scoreOnUndesirables($resource),
            'leftover'     =>  1 * $this->scoreOnLeftover($resource),
        ]);

        return $score;
    }

    /**
     * Scores
     * +1 if $description's title is in the resource.
     * -1 if it is not.
     *  0 if $description has no title.
     *
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return int
     */
    protected function scoreOnTitle(Resource $resource) : int
    {
        if (!$this->description->title) {
            return 0;
        }

        return Text::substrCount($resource->title, $this->description->title)
            ?  1
            : -1;
    }

    /**
     * same as ::scoreOnTitle()
     * 
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return int
     */
    protected function scoreOnArtist(Resource $resource) : int
    {
        if (!$this->description->artist) {
            return 0;
        }

        return Text::substrCount($resource->title, $this->description->artist)
            ?  1
            : -1;
    }

    /**
     * same as ::scoreOnTitle()
     * 
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return int
     */
    protected function scoreOnSoundtrack(Resource $resource) : int
    {
        if (!$this->description->soundtrack) {
            return 0;
        }

        return Text::substrCount($resource->title, $this->description->soundtrack)
            ?  1
            : -1;
    }

    /**
     * Score negative points when matching undesirable terms.
     *
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return int
     */
    protected function scoreOnUndesirables(Resource $resource) : int 
    {
        return Text::substrCount($resource->title, $this->undesirables) * -1;
    }

    /**
     * Score negative points on everything else present on the $resource.
     *
     * @param AdinanCenci\AetherMusic\Source\Resource $resource
     *
     * @return int
     */
    protected function scoreOnLeftover(Resource $resource) : int 
    {
        $rest = $resource->title;

        if ($resource->title) {
            $rest = str_ireplace($this->description->title, '', $rest);
        }

        if ($this->description->artist) {
            $rest = str_ireplace($this->description->artist, '', $rest);
        }

        if ($this->description->soundtrack) {
            $rest = str_ireplace($this->description->soundtrack, '', $rest);
        }

        if ($resource->undesirables) {
            $rest = str_ireplace($this->undesirables, '', $rest);
        }

        $rest = trim($rest);

        $split = preg_split('/[^\w\' ]/', $rest);
        $split = array_filter($split);

        return count($split) * -1;
    }

    /**
     * It is possible for $description to include common undesirable terms.
     * e.g. "Live And Let Die" contains "live".
     * So we need to white-list them.
     *
     * @param string[] $base
     *
     * @return string[]
     *   The array filtered.
     */
    protected function compileUndesirables(array $base) : array
    {
        $undesirables = [];

        foreach ($base as $term) {
            if ($this->description->title && Text::substrCount($term, $this->description->title)) {
                continue;
            }

            if ($this->description->artist && Text::substrCount($term, $this->description->artist)) {
                continue;
            }

            if ($this->description->soundtrack && Text::substrCount($term, $this->description->soundtrack)) {
                continue;
            }

            $undesirables[] = $term;
        }

        return $undesirables;
    }

    /**
     * Basically substr_count but case insensitive and accept arrays.
     *
     * @param string $haystack
     *
     * @param string|array $needles
     *   The terms to search in $haystack.
     *
     * @return int
     *   The number of occurences.
     */
    protected function substrCount(string $haystack, $needles) : int
    {
        $needles = (array) $needles;

        $count = 0;
        foreach ($needles as $needle) {
            $count += substr_count(strtolower($haystack), strtolower($needle));
        }

        return $count;
    }
}
