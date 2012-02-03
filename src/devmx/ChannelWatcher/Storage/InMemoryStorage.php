<?php
namespace devmx\ChannelWatcher\Storage;

/**
 *
 * @author drak3
 */
class InMemoryStorage implements StorageInterface
{
    /**
     * Array of form id=>time
     * @var array 
     */
    protected $channels = array();
    
    /**
     * Updates the last time seen date of a channel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now 
     */
    public function update($id, $isVisited, \DateTime $time=null) {
        if($time === null) {
            $time = new DateTime('now');
        }
        if(!isset($this->channels[$id])) {
            $this->channels[$id] = $time;
        }
        if($isVisited) {
            $this->channels[$id] = $time;
        }        
    }
    
    /**
     * Returns all channel ids which are empty for a given time 
     * @param $time int the time in seconds 
     */
    public function getChannelsEmptyFor(  \DateInterval $time, \DateTime $now=null) {
        if($now === null) {
            $now = new DateTime('now');
        }
        $maxLastSeen = $now->sub($time);
        $ret = array();
        foreach($this->channels as $id => $lastSeen) {
            if($lastSeen->diff($maxLastSeen)->invert === 1) {
                $ret[] = $id;
            }
        }
        return $ret;
    }
    
    public function getChannels() {
        return $this->channels;
    }
    
    
}

?>
