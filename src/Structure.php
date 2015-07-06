<?php namespace Rde\Telegram;

abstract class Structure
{
    public function fill($obj)
    {
        if (is_object($obj)) {
            foreach ($obj as $k => $v)
            {
                $this->{$k} = $v;
            }
        }

        return $this;
    }

    public function __toString()
    {
        return http_build_query($this);
    }
}
