<?php

/**
 * https://id.linkedin.com/in/abdurrohim-saifi-478838130
*/
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Dome_Location_Model extends Model
{
    protected $table = 'dome_locations';
    protected $primaryKey = 'id';
    protected $attributes = [
        'id', 'location_name', 'estimasi_jarak', 'eto_efo'
    ];

    public function find($id, $attributes= [])
    {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");

        $columns = empty($attributes) ? implode(",", $this->attributes) : implode(",", $attributes);

        return $db->select($columns,  $this->table)->where('id='.$id)->get()[0];
    }

    public function get($attributes = [])
    {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");

        $columns = empty($attributes) ? implode(",", $this->attributes) : implode(",", $attributes);

        return $db->select($columns,  $this->table)->get();
    }

    public function create($data)
    {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");

        $values = [];
        $columns = [];
        foreach ($data as $key => $value) {
          if (in_array($key, $this->attributes)) {
            $values[] = '"'.$value.'"';
            $columns[] = $key;
          }
        }

        $sql = 'INSERT INTO '.$this->table.' ('.implode(",", $columns).') VALUES('.implode(",", $values).');';

        $result = $db->query($sql);
        return $result;
    }

    public function update($id, $data)
    {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");

        $values = [];
        foreach ($data as $key => $value) {
          if (in_array($key, $this->attributes)) {
            $values[] = $key.'="'.$value.'"';
          }
        }

        $sql = 'UPDATE '.$this->table.' SET '.implode(",", $values).' WHERE id='.$id.';';

        $result = $db->query($sql);
        return $result;
    }

    public function delete($id)
    {
      global $dcistem;
      $db = $dcistem->getOption("framework/db");

      $sql = 'DELETE FROM '.$this->table.' WHERE id='.$id.';';

      $result = $db->query($sql);
      return $result;
    }
}
