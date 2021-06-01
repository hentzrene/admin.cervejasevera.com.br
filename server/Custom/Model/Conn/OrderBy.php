<?php

namespace Custom\Model\Conn;

class OrderBy
{
  static function limit(int $limit, int $offset = 0)
  {
    return Conn::limit($limit, $offset);
  }
  static function send()
  {
    return Conn::send();
  }
}
