<?php namespace Rde\Telegram;

class Structure
{
    public function fill($data)
    {
        if (is_object($data) || is_array($data)) {
            foreach ($data as $k => $v)
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
