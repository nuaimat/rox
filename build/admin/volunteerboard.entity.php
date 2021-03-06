<?php

class VolunteerBoard extends RoxEntityBase
{
    protected $_table_name = 'volunteer_boards';

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id)
        {
            $this->findById($id);
        }
    }

    /**
     * fetches a board by name
     *
     * @param string $name - name of board
     *
     * @access public
     * @return false|VolunteerBoard
     */
    public function findByName($name)
    {
        return $this->findByWhere("name = '{$this->dao->escape($name)}'");
    }

    /**
     * updates the text of the board
     *
     * @param string $text
     * @param Member $member
     *
     * @access public
     * @return bool
     */
    public function updateText($text, Member $member)
    {
        $this->TextContent = date("Y/n/j H:i ") . "{$member->Username} wrote:\n{$text}";
        return $this->update();
    }
}
